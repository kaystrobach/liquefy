<?php
namespace KayStrobach\Liquefy\Service;

use Neos\Utility\Files;

class ResourceService
{
    /**
     * @param $directory
     * @return string
     * @throws \Neos\Utility\Exception\FilesException
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
     * @throws \Neos\Utility\Exception\FilesException
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
     * @throws \Neos\Utility\Exception\FilesException
     */
    protected function copyResources($source, $dest)
    {
        $this->copyRecursive(
            Files::getNormalizedPath($source),
            Files::getNormalizedPath($dest)
        );
    }

    protected function copyRecursive($source, $dest)
    {
        @\mkdir($dest, 0755);
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                @mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                @copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }
}