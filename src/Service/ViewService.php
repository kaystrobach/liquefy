<?php

namespace KayStrobach\Liquefy\Service;

use KayStrobach\Liquefy\Core\ViewHelper\ViewHelperResolver;
use TYPO3Fluid\Fluid\View\TemplateView;

class ViewService
{
    function initializeView($templateRootPaths, $layoutRootPaths, $partialRootPaths, $namespaces = [])
    {
        $view = new TemplateView();
        $paths = $view->getTemplatePaths();
        $paths->setTemplateRootPaths(
            $templateRootPaths
        );
        $paths->setLayoutRootPaths(
            $layoutRootPaths
        );
        $paths->setPartialRootPaths(
            $partialRootPaths
        );

        $view->getRenderingContext()->setViewHelperResolver(
            new ViewHelperResolver()
        );

        $view->getRenderingContext()->getViewHelperResolver()->addNamespace(
            'f', 'KayStrobach\\Liquefy\\ViewHelpers'
        );

        foreach ($namespaces as $namespace => $classPath) {
            $view->getRenderingContext()->getViewHelperResolver()->addNamespace(
                $namespace,
                $classPath
            );
        }

        return $view;
    }

    function getView($controller = 'Default', $variables = [])
    {
        $view = $this->initializeView(
            [
                BASE_DIRECTORY . '/Resources/Private/Templates/'
            ], [
                BASE_DIRECTORY . '/Resources/Private/Layouts/'
            ], [
                BASE_DIRECTORY . '/Resources/Private/Partials/'
            ]
        );
        $view->getRenderingContext()->setControllerName($controller);
        if (is_array($variables)) {
            $view->assignMultiple($variables);
        }
        return $view;
    }

    function getViewFromFile($templatePathAndFilename, $layoutRootPaths = [], $partialRootPaths = [], $variables = [])
    {
        $view = $this->initializeView(
            [],
            $layoutRootPaths,
            $partialRootPaths
        );
        $view->getTemplatePaths()->setTemplatePathAndFilename($templatePathAndFilename);
        if (is_array($variables)) {
            $view->assignMultiple($variables);
        }
        return $view;
    }

    function getViewFromFileInternal($templatePathAndFilename, $variables = [])
    {
        return $this->getViewFromFile(
            $templatePathAndFilename,
            [
                LIQUEFY_DIRECTORY . '/Resources/Private/Layouts'
            ],
            [
                LIQUEFY_DIRECTORY . '/Resources/Private/Partials'
            ],
            $variables
        );
    }
}