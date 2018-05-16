<?php

namespace KayStrobach\Liquefy\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Use this view helper to crop the text between its opening and closing tags.
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <f:format.nl2br value="{object}" />
 * </code>
 * <output>
 * This is…
 * </output>
 */
class Nl2brViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments.
     *
     * @api
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        $this->registerArgument('value', 'mixed','Value Attribute',  false, null);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $buffer = $arguments['value'];
        if ($buffer === null) {
            $buffer = $renderChildrenClosure();
        }
        return $buffer;
    }
}
