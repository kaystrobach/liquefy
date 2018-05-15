<?php
/**
 * Created by kay.
 */

namespace KayStrobach\Liquefy\Service;


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
        return exec ('ln -s ' . $source . ' ' .  $dest);
    }

    /**
     * @param $source
     * @param $dest
     * @return string
     */
    protected function copyResources($source, $dest)
    {
        return exec ('cp -R ' . $source . ' ' .  $dest);
    }
}