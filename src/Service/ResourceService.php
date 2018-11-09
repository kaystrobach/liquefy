<?php
namespace KayStrobach\Liquefy\Service;

use Neos\Flow\Utility\Files;

class ResourceService
{
    /**
     * @param $directory
     * @return string
     */
    public function publishResources($directory)
    {
        $source = $directory . '/Resources/Public';
        $dest = $directory . '/Web/Resources';
        if ((bool)getenv('SYMLINK')) {
            return $this->symlinkResources($source, $dest);
        }
        return $this->copyResources($source, $dest);
    }

    /**
     * @param $source
     * @param $dest
     * @return string
     */
    protected function symlinkResources($source, $dest)
    {
        return Files::createRelativeSymlink(
            $source,
            $dest
        );
    }

    /**
     * @param $source
     * @param $dest
     * @return string
     */
    protected function copyResources($source, $dest)
    {
        Files::copyDirectoryRecursively(
            Files::getNormalizedPath($source),
            Files::getNormalizedPath($dest)
        );
    }
}