<?php

namespace KayStrobach\Liquefy\Service;

use TYPO3Fluid\Fluid\View\TemplateView;

class ViewService
{
    function getView($controller = 'Default', $variables = []) {
        $view = new TemplateView();
        $paths = $view->getTemplatePaths();
        $paths->setTemplateRootPaths(
            [
                BASE_DIRECTORY . '/../Resources/Private/Templates/'
            ]
        );
        $paths->setLayoutRootPaths(
            [
                BASE_DIRECTORY . '/../Resources/Private/Layouts/'
            ]
        );
        $paths->setPartialRootPaths(
            [
                BASE_DIRECTORY . '/../Resources/Private/Partials/'
            ]
        );
        $view->getRenderingContext()->setControllerName($controller);
        $view->getRenderingContext()->getViewHelperResolver()->addNamespace(
            'f',
            'KayStrobach\\Liquefy\\ViewHelpers'
        );
        $view->assignMultiple($variables);
        return $view;
    }

    function getViewFromFile($string, $variables = [])
    {
        $view = new TemplateView();
        $paths = $view->getTemplatePaths();
        $paths->setTemplatePathAndFilename($string);
        $view->assignMultiple($variables);
        return $view;
    }
}