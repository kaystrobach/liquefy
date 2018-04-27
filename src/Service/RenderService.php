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
     * @param string $baseDirectory
     */
    public function setBaseDirectory($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
        $this->viewService = new ViewService();
        $this->resourceService = new ResourceService();
    }

    public function render(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $controllerActions = [
            'Authentication/Index',
            'Application/Index'
        ];

        $this->cleanup();
        $this->renderTemplates($controllerActions);
        $this->renderIndex($controllerActions);
        $this->publishResources();
    }

    protected function cleanup()
    {
        exec('rm -rf ' . $this->baseDirectory . '/../Web/Resources');
        exec ('mkdir -p ' . $this->baseDirectory . '/../Web/Resources');
    }

    protected function getControllerAndActions()
    {
        $controllersAndActions = [];
        $finder = new Finder();
        $files = $finder->files()->in($this->baseDirectory. '/Resources/Private/Templates')->name('*.html');
        /** @var \SplFileInfo $file */
        foreach($finder as $file) {
            $controllersAndActions[] = [
                'controller' => basename($file->getPathname()),
                'action' => $file->getBasename('.html')
            ];
            // add reading json and yaml file
        }
        return $controllersAndActions;
    }

    /**
     * @param array $controllerActions
     */
    protected function renderTemplates($controllerActions)
    {
        $this->output->writeln('<info>Rendering:</info>');
        foreach ($controllerActions as $controllerAndAction) {
            list($controller, $action) = explode('/', $controllerAndAction);
            $outputFileName = $this->baseDirectory . '/../Web/' . $controller . '_' . $action. '.html';
            $jsonFileName = $this->baseDirectory . '/../Resources/Private/Templates/' . $controllerAndAction . '.json';
            $variables = [];
            if (file_exists($jsonFileName)) {
                $variables = json_decode(file_get_contents($jsonFileName), true);
            }
            $view = $this->viewService->getView($controller, $variables);
            file_put_contents($outputFileName  , $view->render($action));
            $this->output->writeln(' ... ' . realpath($outputFileName));
        }
    }

    protected function publishResources()
    {
        $this->resourceService->publishResources($this->baseDirectory);
    }

    /**
     * @param array $controllerActions
     */
    protected function renderIndex($controllerActions)
    {
        $variables = [
            'files' => []
        ];
        foreach ($controllerActions as $controllerAndAction) {
            list($controller, $action) = explode('/', $controllerAndAction);
            $variables['files'][] = [
                'controller' => $controller,
                'action' => $action,
                'input' => [
                    'filename' => ''
                ],
                'output' => [
                    'filename' => $controller . '_' . $action . '.html'
                ]
            ];
        }

        $view = $this->viewService->getViewFromFile(
            __DIR__ . '/../../Resources/Private/Templates/Overview/Index.html',
            $variables
        );

        file_put_contents(
            $this->baseDirectory . '/../Web/index.html',
            $view->render()
        );
    }

    protected function getControllersAndActions()
    {
        $controllersAndActions = [];
        $finder = new Finder();
        $files = $finder->files()->in($this->baseDirectory . '/../Resources/Private/Templates')->notName('*.json')->getIterator();
        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $controllersAndActions[] = new Action($file);
        }
        return $controllersAndActions;
    }
}