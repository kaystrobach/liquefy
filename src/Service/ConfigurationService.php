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
        $this->defaultConfiguration = Yaml::parseFile(
            LIQUEFY_DIRECTORY . '/Configuration/.liquefy.yaml',
            Yaml::PARSE_CONSTANT
        );
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
                Yaml::parseFile(
                    $file,
                    Yaml::PARSE_CONSTANT
                )
            );
        }
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function getByPath($path)
    {
        $pathSegments = explode('.', $path);
        return $this->getByPathRecursive(
            $pathSegments,
            $this->configuration
        );
    }

    protected function getByPathRecursive($pathSegments, $array)
    {
        if (count($pathSegments) === 0) {
            return $array;
        }

        $nextKey = array_shift ($pathSegments);
        if (array_key_exists($nextKey, $array)) {
            return $this->getByPathRecursive($pathSegments, $array[$nextKey]);
        }

        return null;
    }
}