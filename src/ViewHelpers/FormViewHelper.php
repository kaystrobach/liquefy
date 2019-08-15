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
        $this->registerTagAttribute('enctype', 'string', 'MIME type with which the form is submitted');
        $this->registerTagAttribute('method', 'string', 'Transfer type (GET or POST)');
        $this->registerTagAttribute('name', 'string', 'Name of form');
        $this->registerTagAttribute('onreset', 'string', 'JavaScript: On reset of the form');
        $this->registerTagAttribute('onsubmit', 'string', 'JavaScript: On submit of the form');
        $this->registerArgument('arguments', 'array', '');
        $this->registerArgument('action', 'string', '');
        $this->registerArgument('object', 'string', '');
        $this->registerArgument('objectName', 'string', '');
        $this->registerArgument('controller', 'string', '');
        $this->registerArgument('package', 'string', '');
        $this->registerArgument('subpackage', 'string', '');
        $this->registerArgument('section', 'string', '');
        $this->registerArgument('format', 'string', '');
        $this->registerArgument('additionalParams', 'array', '');
        $this->registerArgument('absolute', 'boolean', '');
        $this->registerArgument('addQueryString', 'boolean', '');
        $this->registerArgument('argumentsToBeExcludedFromQueryString', 'array', '');
        $this->registerArgument('fieldNamePrefix', 'string', '');
        $this->registerArgument('actionUri', 'string', '');
        $this->registerArgument('useParentRequest', 'string', '');
    }

    public function render()
    {
        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();
    }
}
