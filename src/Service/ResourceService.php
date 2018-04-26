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
        if ((bool)getenv('SYMLINK')) {
            return $this->symlinkResources($directory);
        }
        return $this->copyResources($directory);
    }

    /**
     * @param $directory
     * @return string
     */
    protected function symlinkResources($directory)
    {
        return exec ('ln -s ' . $directory . '/../Resources/Public ' .  $directory . '/../Web/Resources');
    }

    /**
     * @param $directory
     * @return string
     */
    protected function copyResources($directory)
    {
        return exec ('cp -R ' . $directory . '/../Resources/Public ' .  $directory . '/../Web/Resources');
    }
}