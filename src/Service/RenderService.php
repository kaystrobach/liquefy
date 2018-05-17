<?php
/**
 * Created by kay.
 */

namespace KayStrobach\Liquefy\Service;


use KayStrobach\Liquefy\Domain\Model\Action;
use KayStrobach\Liquefy\Service\ResourceService;
use KayStrobach\Liquefy\Service\ViewService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\FilenameFilterIterator;
use Symfony\Component\Yaml\Yaml;

class RenderService
{
    protected $settings = [];

    /**
     * @var string
     */
    protected $baseDirectory;

    /**
     * @var ViewService
     */
    protected $viewService;

    /**
     * @var ResourceService
     */
    protected $resourceService;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var ConfigurationService
     */
    protected $configurationService;

    public function __construct()
    {
        $this->viewService = new ViewService();
        $this->resourceService = new ResourceService();
        $this->configurationService = new ConfigurationService();
        $this->configurationService->applySpecialConfiguration(LIQUEFY_CWD . '/.liquefy.yaml');
        $this->baseDirectory = $this->configurationService->getByPath('configuration.paths.rootPath');
    }

    /**
     * @param string $baseDirectory
     */
    public function setBaseDirectory($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    public function render(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $controllerActions = $this->getControllerAndActions();

        $this->cleanup();
        $this->renderTemplates($controllerActions);
        $this->renderIndex($controllerActions);
        $this->publishResources();
    }

    protected function cleanup()
    {
        exec('rm -rf ' . $this->baseDirectory . '/Web/');
        exec ('mkdir -p ' . $this->baseDirectory . '/Web/Resources');
        exec ('mkdir -p ' . $this->baseDirectory . '/Web/Templates');
    }

    protected function getControllerAndActions()
    {
        $templateDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR
            . $this->configurationService->getByPath('configuration.paths.templatesRootPaths.default');
        $templateDataDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR
            . $this->configurationService->getByPath('configuration.paths.templatesDataRootPaths.default');

        $controllersAndActions = [];
        $finder = new Finder();
        /** @var FilenameFilterIterator $templateFiles */
        $templateFiles = $finder->files()->in($templateDirectory)->name('*.html')->sortByName();

        /** @var \SplFileInfo $file */
        foreach($templateFiles as $file) {
            $controller = basename($file->getPath());
            $action  = $file->getBasename('.html');

            $data = $this->getProvidedData($templateDataDirectory, $controller, $action);

            foreach($data as $dataFileName => $dataFileContent) {
                $controllersAndActions[] = [
                    'controller' => $controller,
                    'action' => $action,
                    'controllerAction' => $controller . ' / ' . $action,
                    'input' => [
                        'dataFileName' => $dataFileName,
                        'data' => $dataFileContent
                    ],
                    'output' => [
                        'outputFileName' => 'Templates/' . $controller . '.' . $action . '.' . $dataFileName . '.html'
                    ]
                ];
            }
        }

        usort(
            $controllersAndActions,
            function($a, $b) {
                if ($a['controller'] === $b['controller']) {
                    if ($a['action'] < $b['action']) {
                        return -1;
                    }
                    return 1;
                }
                if ($a['controller'] < $b['controller']) {
                    return -1;
                }
                return 1;
            }
        );

        return $controllersAndActions;
    }

    protected function getPartials()
    {
        $templateDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR
            . $this->configurationService->getByPath('configuration.paths.partialsRootPaths.default');
        $templateDataDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR
            . $this->configurationService->getByPath('configuration.paths.partialsDataRootPaths.default');
        $partials = [];

        $finder = new Finder();
        /** @var FilenameFilterIterator $templateFiles */
        $templateFiles = $finder->files()->in($templateDirectory)->name('*.html')->sortByName();

        /** @var \SplFileInfo $file */
        foreach($templateFiles as $file) {
            $partial = $file->getBasename('.html');
            $data = $this->getProvidedData($templateDataDirectory, $partial, null);

            foreach($data as $dataFileName => $dataFileContent) {
                $partials[] = [
                    'partial' => $partial,
                    'input' => [
                        'dataFileName' => $dataFileName,
                        'data' => $dataFileContent
                    ],
                    'code' => file_get_contents($file->getRealPath()),
                    'output' => [
                        'outputFileName' => 'Partials/' . $partial . '.' . $dataFileName . '.html'
                    ]
                ];
            }
        }

        usort(
            $partials,
            function($a, $b) {
                if ($a['controller'] === $b['controller']) {
                    return 1;
                }
                if ($a['controller'] < $b['controller']) {
                    return -1;
                }
                return 1;
            }
        );

        return $partials;
    }

    /**
     * @param $templateExampleDataDirectory
     * @param $controller
     * @param $action
     * @return array
     */
    protected function getProvidedData($templateExampleDataDirectory, $controller, $action)
    {
        $data = [];
        $finder = new Finder();

        $directory = $templateExampleDataDirectory . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $action;

        if (is_dir($directory)) {
            /** @var FilenameFilterIterator $dataFiles */
            $dataFiles = $finder->files()->in($directory)->name('/.[json|yaml|yml]$/')->sortByName();

            /** @var \SplFileInfo $dataFile */
            foreach ($dataFiles as $dataFile) {
                switch ($dataFile->getExtension()) {
                    case 'json':
                        $data[$dataFile->getBasename('.' . $dataFile->getExtension())] = json_decode(file_get_contents($dataFile->getRealPath()), true);
                        break;
                    case 'yaml':
                    case 'yml':
                        $data[$dataFile->getBasename('.' . $dataFile->getExtension())] = Yaml::parseFile($dataFile->getRealPath());
                        break;
                    default:
                        break;
                }
            }
        }

        if (count($data) === 0) {
            return [
                'Default' => []
            ];
        }

        return $data;
    }

    /**
     * @param array $controllerActions
     */
    protected function renderTemplates($controllerActions)
    {
        $this->output->writeln('<info>Rendering:</info>');
        foreach ($controllerActions as $controllerAndAction) {
            $outputFileName = $this->baseDirectory . '/Web/' . $controllerAndAction['output']['outputFileName'];
            $view = $this->viewService->getView(
                $controllerAndAction['controller'],
                $controllerAndAction['input']['data']
            );
            file_put_contents(
                $outputFileName,
                $view->render($controllerAndAction['action'])
            );
            $this->output->writeln(' ... ' . realpath($outputFileName));
        }
    }

    protected function renderPartials($partials)
    {
        $this->output->writeln('<info>Rendering:</info>');
        foreach ($partials as $partial) {
            $outputFileName = $this->baseDirectory . '/Web/' . $partial['output']['outputFileName'];
            $view = $this->viewService->getView(
                $partial['partial'],
                $partial['input']['data']
            );
            file_put_contents(
                $outputFileName,
                $view->renderPartial($partial['partial'], null, $partial['input']['data'])
            );
            $this->output->writeln(' ... ' . realpath($outputFileName));
        }
    }

    protected function publishResources()
    {
        $this->resourceService->publishResources($this->baseDirectory);
    }

    /**
     * @param array $templates
     * @param array $partials
     */
    protected function renderIndex($templates, $partials = [])
    {
        $view = $this->viewService->getViewFromFile(
            LIQUEFY_DIRECTORY . '/Resources/Private/Templates/Overview/Index.html',
            [
                LIQUEFY_DIRECTORY . '/Resources/Private/Partials'
            ],
            [
                'templates' => $templates,
                'partials' => $partials,
                'settings' => $this->configurationService->getConfiguration()
            ]
        );

        file_put_contents(
            $this->baseDirectory . '/Web/index.html',
            $view->render()
        );
    }
}