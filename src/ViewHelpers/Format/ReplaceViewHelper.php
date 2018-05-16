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
 * <f:format.replace values="{object}" />
 * </code>
 * <output>
 * This is…
 * </output>
 */
class ReplaceViewHelper extends AbstractViewHelper
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
        $this->registerArgument('values', 'mixed','Value Attribute',  false, null);
        $this->registerArgument('wrap', 'string', 'Wraper', false, '%');
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
        $buffer = $renderChildrenClosure();
        $keys = [];
        foreach (array_keys($arguments['values']) as $value) {
            $keys[] = $arguments['wrap'] . $value . $arguments['wrap'];
        }

        return str_replace(
            $keys,
            array_values($arguments['values']),
            $buffer
        );
    }
}
