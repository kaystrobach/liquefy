<?php

namespace KayStrobach\Liquefy\ViewHelpers\Fallback;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class FallbackViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;
    /**
     * @var bool
     */
    protected $escapeChildren = false;
    /**
     * @var string
     */
    protected $originalClassName = self::class;

    public function validateArguments()
    {

    }
    public function handleAdditionalArguments(array $arguments)
    {

    }
    public function validateAdditionalArguments(array $arguments)
    {

    }

    /**
     * @return string
     */
    public function getOriginalClassName()
    {
        return $this->originalClassName;
    }

    /**
     * @param string $originalClassName
     */
    public function setOriginalClassName($originalClassName)
    {
        $this->originalClassName = $originalClassName;
    }

    /**
     * @return string
     */
    public function render()
    {
        return '<!-- ViewHelper: ' . htmlspecialchars($this->getOriginalClassName()) . '-->'
            . $this->renderChildren()
            . '<!-- END: ' . htmlspecialchars($this->getOriginalClassName()) . '-->';
    }
}