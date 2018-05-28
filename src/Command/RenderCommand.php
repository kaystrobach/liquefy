<?php
namespace KayStrobach\Liquefy\Command;

use KayStrobach\Liquefy\Service\ConfigurationService;
use KayStrobach\Liquefy\Service\RenderService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class RenderCommand extends Command
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var ConfigurationService
     */
    protected $configurationService;

    /**
     * RenderCommand constructor.
     * @param string $name
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('render:all')
            ->setDescription('render fluid templates')
            ->addOption('watch', 'w', InputOption::VALUE_NONE, 'Watch for file changes')
            ->addOption('serve', 's', InputOption::VALUE_NONE, 'Serve with php internal webserver')
        ;
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $this->configurationService = new ConfigurationService();
        $this->configurationService->applySpecialConfiguration(LIQUEFY_CWD . '/.liquefy.yaml');

        $jobs = $this->configurationService->getByPath('configuration.jobs');
        foreach($jobs as $key => $job) {
            $renderService = new RenderService(
                [
                    'name' => 'default',
                    'baseDirectory' => $this->configurationService->getByPath('configuration.jobs.' . $key . '.paths.rootPath'),
                    'layoutRootPaths' => $this->configurationService->getByPath('configuration.jobs.' . $key . '.paths.layoutRootPaths'),
                    'partialRootPaths' => $this->configurationService->getByPath('configuration.jobs.' . $key . '.paths.partialRootPaths'),
                    'partialsDataRootPaths' => $this->configurationService->getByPath('configuration.jobs.' . $key . '.paths.partialDataRootPaths'),
                    'templateRootPaths' => $this->configurationService->getByPath('configuration.jobs.' . $key . '.paths.templateRootPaths'),
                    'templateDataRootPaths' => $this->configurationService->getByPath('configuration.jobs.' . $key . '.paths.templateDataRootPaths'),
                ]
            );
            $this->executeOneTime($renderService, $input, $output);
        }

        if ($input->getOption('watch')) {
            $this->watchForChangesAndExecute($renderService, $input, $output);
        }

        return 0;
    }

    protected function executeOneTime(RenderService $renderService, InputInterface $input, OutputInterface $output)
    {
        $renderService->render($input, $output);
    }

    protected function watchForChangesAndExecute(RenderService $renderService, InputInterface $input, OutputInterface $output)
    {
        $files = new \Illuminate\Filesystem\Filesystem;
        $tracker = new \JasonLewis\ResourceWatcher\Tracker;
        $watcher = new \JasonLewis\ResourceWatcher\Watcher($tracker, $files);

        $watchDirectory = BASE_DIRECTORY . '/Resources/';
        $listener = $watcher->watch($watchDirectory);

        $output->writeln('<info>Now watching</info>:');
        $output->writeln(' ... ' . $watchDirectory);
        $output->writeln('----------');

        $listener->modify(function($resource, $path) use ($renderService, $input, $output) {
            $output->writeln('<info>Changed</info>:');
            $output->writeln(' ... ' . $path);
            $renderService->render($input, $output);
            $output->writeln('----------');

        });
        $watcher->start();
    }
}