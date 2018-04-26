<?php
/**
 * Created by kay.
 */

namespace KayStrobach\Liquefy\Controller;


use Sbs\SchulloginLayout\Domain\Model\Action;
use Sbs\SchulloginLayout\Service\ViewService;
use Symfony\Component\Finder\Finder;


class RenderController
{
    /**
     * @var ViewService
     */
    private $viewService;

    public function __construct(ViewService $service)
    {
        $this->viewService = $service;
    }

    public function renderAction($controllerActions)
    {
        foreach ($controllerActions as $controllerAndAction) {
            list($controller, $action) = explode('/', $controllerAndAction);
            $outputFileName = BASE_DIRECTORY . '/../Web/' . $controller . '_' . $action. '.html';
            $jsonFileName = BASE_DIRECTORY . '/../Resources/Private/Templates/' . $controllerAndAction . '.json';
            $variables = [];
            if (file_exists($jsonFileName)) {
                $variables = json_decode(file_get_contents($jsonFileName), true);
            }
            $view = $this->viewService->getView($controller, $variables);
            file_put_contents($outputFileName  , $view->render($action));
            echo 'Rendering: ' . realpath($outputFileName) . PHP_EOL;
        }
    }

    protected function getControllersAndActions()
    {
        $controllersAndActions = [];
        $finder = new Finder();
        $files = $finder->files()->in(BASE_DIRECTORY . '/../Resources/Private/Templates')->notName('*.json')->getIterator();
        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $controllersAndActions[] = new Action($file);
        }
        return $controllersAndActions;
    }
}