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
 * <f:format.crop maxCharacters="10">This is some very long text</f:format.crop>
 * </code>
 * <output>
 * This is…
 * </output>
 *
 * <code title="Custom suffix">
 * <f:format.crop maxCharacters="17" append="&nbsp;[more]">This is some very long text</f:format.crop>
 * </code>
 * <output>
 * This is some&nbsp;[more]
 * </output>
 *
 * <code title="Inline notation">
 * {someLongText -> f:format.crop(maxCharacters: 10)}
 * </code>
 * <output>
 * someLongText cropped after 10 characters…
 * (depending on the value of {someLongText})
 * </output>
 */
class CropViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * The output may contain HTML and can not be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments.
     *
     * @api
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        $this->registerArgument('maxCharacters', 'int', 'Place where to truncate the string', true);
        $this->registerArgument('append', 'string', 'What to append, if truncation happened', false, '&hellip;');
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
        $maxCharacters = $arguments['maxCharacters'];
        $append = $arguments['append'];

        $stringToTruncate = $renderChildrenClosure();

        return substr($stringToTruncate, 0, $maxCharacters) . $append;
    }
}
