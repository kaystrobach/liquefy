<?php
/**
 * Created by kay.
 */

namespace KayStrobach\Liquefy\ViewHelpers;


use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class FormViewHelper extends AbstractTagBasedViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the render-result of this ViewHelper
     * @see isOutputEscapingEnabled()
     *
     * @var boolean
     * @api
     */
    protected $escapeOutput = false;

    /**
     * Name of the tag to be created by this view helper
     *
     * @var string
     * @api
     */
    protected $tagName = 'form';

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('action', 'string', '');
        $this->registerArgument('method', 'string', '');
        $this->registerArgument('object', 'string', '');
        $this->registerArgument('objectName', 'string', '');
        $this->registerArgument('controller', 'string', '');
    }

    public function render()
    {
        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();
    }
}
