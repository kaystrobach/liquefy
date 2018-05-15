<?php

namespace KayStrobach\Liquefy\Service;


use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class ConfigurationService
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $defaultConfiguration;

    public function __construct()
    {
        $this->readDefaultConfiguration();
    }

    public function readDefaultConfiguration()
    {
        $this->defaultConfiguration = Yaml::parseFile(LIQUEFY_DIRECTORY . '/Configuration/.liquefy.yaml');
    }

    public function applySpecialConfiguration($additionalFile = null)
    {
        $this->configuration = $this->defaultConfiguration;
        $this->mergeConfigurationFromFile(LIQUEFY_CWD . '/.liquefy.yaml');
        $this->mergeConfigurationFromFile($additionalFile);
    }

    protected function mergeConfigurationFromFile($file)
    {
        if (is_readable($file)) {
            $this->configuration = array_replace_recursive(
                $this->configuration,
                Yaml::parseFile($file)
            );
        }
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }
}