<?php
namespace KayStrobach\Liquefy\Command;

use KayStrobach\Liquefy\Service\RenderService;
use KayStrobach\Liquefy\Service\ViewService;
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
            ->addOption('watch', 'w', InputOption::VALUE_NONE, 'Should we watch file changes?')
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

        $renderService = new RenderService();
        $renderService->setBaseDirectory(BASE_DIRECTORY);

        if ($input->getOption('watch')) {
            $this->watchForChangesAndExecute($renderService, $input, $output);
        } else {
            $this->executeOneTime($renderService, $input, $output);
        }

        return 0;
    }

    protected function executeOneTime(RenderService $renderService, InputInterface $input, OutputInterface $output)
    {
        $renderService->render($input, $output);
    }

    protected function watchForChangesAndExecute(RenderService $renderService, InputInterface $input, OutputInterface $output)
    {
        $files = new Illuminate\Filesystem\Filesystem;
        $tracker = new JasonLewis\ResourceWatcher\Tracker;
        $watcher = new JasonLewis\ResourceWatcher\Watcher($tracker, $files);

        $listener = $watcher->watch(BASE_DIRECTORY);

        $listener->modify(function($resource, $path) {
            echo "{$path} has been modified.".PHP_EOL;
        });
    }
}