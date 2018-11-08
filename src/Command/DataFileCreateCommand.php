<?php
/**
 * Created by kay.
 */

namespace KayStrobach\Liquefy\Command;


use KayStrobach\Liquefy\Service\ConfigurationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DataFileCreateCommand extends Command
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
            ->setName('datafile:create')
            ->setDescription('Create a DataFile for a template')
            ->addArgument('controllerAndAction', InputArgument::REQUIRED, 'controller name')
            ->addArgument('dataFileName', InputArgument::OPTIONAL, 'action name','Default')
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

        $controllerAction = $input->getArgument('controllerAndAction');
        $dataFileName = $input->getArgument('dataFileName');
        $directory = BASE_DIRECTORY . '/Resources/Private/TemplatesExampleData/' . $controllerAction;
        $fileName = $directory . DIRECTORY_SEPARATOR . $dataFileName . '.json';
        exec('mkdir -p ' . $directory);

        if (!file_exists($fileName)) {
            le_put_contents(
                $fileName,
                json_encode(
                    [
                        'value' => 1,
                        'otherValue' => [
                            'whatever' => 0
                        ]
                    ],
                    JSON_PRETTY_PRINT
                )
            );
        } else {
            $output->writeln('<error>File exists:</error>');
            $output->writeln(' ... ' . $fileName);
        }

        return 0;
    }
}