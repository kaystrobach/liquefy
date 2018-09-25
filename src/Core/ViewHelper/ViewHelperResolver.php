<?php

namespace KayStrobach\Liquefy\Core\ViewHelper;

use KayStrobach\Liquefy\ViewHelpers\Fallback\FallbackViewHelper;
use Symfony\Component\Yaml\Exception\ParseException;
use TYPO3Fluid\Fluid\Core\Parser\Exception as ParserException;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;
use \TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperResolver as FluidCoreViewHelperResolver;

class ViewHelperResolver extends FluidCoreViewHelperResolver
{
    /**
     * Resolves a ViewHelper class name by namespace alias and
     * Fluid-format identity, e.g. "f" and "format.htmlspecialchars".
     *
     * Looks in all PHP namespaces which have been added for the
     * provided alias, starting in the last added PHP namespace. If
     * a ViewHelper class exists in multiple PHP namespaces Fluid
     * will detect and use whichever one was added last.
     *
     * If no ViewHelper class can be detected in any of the added
     * PHP namespaces a Fluid Parser Exception is thrown.
     *
     * @param string $namespaceIdentifier
     * @param string $methodIdentifier
     * @return string|NULL
     * @throws ParserException
     */
    public function resolveViewHelperClassName($namespaceIdentifier, $methodIdentifier)
    {
        try {
            return parent::resolveViewHelperClassName($namespaceIdentifier, $methodIdentifier);
        } catch (ParserException $exception) {
            if (isset($this->namespaces[$namespaceIdentifier])) {
                $className = end($this->namespaces[$namespaceIdentifier]) . '\\' . implode('\\', array_map('ucfirst', explode('.', $methodIdentifier))) . 'ViewHelper';
                class_alias(FallbackViewHelper::class, $className);
                return $className;
            }
        }
        return parent::resolveViewHelperClassName($namespaceIdentifier, $methodIdentifier);
    }

    /**
     * Wrapper to create a ViewHelper instance by class name. This is
     * the final method called when creating ViewHelper classes -
     * overriding this method allows custom constructors, dependency
     * injections etc. to be performed on the ViewHelper instance.
     *
     * @param string $viewHelperClassName
     * @return ViewHelperInterface
     */
    public function createViewHelperInstanceFromClassName($viewHelperClassName)
    {
        $viewHelper = parent::createViewHelperInstanceFromClassName($viewHelperClassName);
        if ($viewHelper instanceof FallbackViewHelper){
            $viewHelper->setOriginalClassName($viewHelperClassName);
        }
        return $viewHelper;
    }

}