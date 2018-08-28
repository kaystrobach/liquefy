<?php
/**
 * Created by kay.
 */

namespace KayStrobach\Liquefy\ViewHelpers\Link;


use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class ActionViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * Name of the tag to be created by this view helper
     *
     * @var string
     * @api
     */
    protected $tagName = 'a';

    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the render-result of this ViewHelper
     * @see isOutputEscapingEnabled()
     *
     * @var boolean
     * @api
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        parent::registerUniversalTagAttributes();
        $this->registerArgument('target', 'string', '');
        $this->registerArgument('action', 'string', '');
        $this->registerArgument('controller', 'string', '');
        $this->registerArgument('package', 'string', '');
        $this->registerArgument('subpackage', 'string', '');
        $this->registerArgument('arguments', 'array', '');
    }

    public function render()
    {
        $this->tag->addAttribute(
            'href',
            $this->arguments['controller'] . '.' . $this->arguments['action'] . '.Default.html'
        );
        $this->tag->addAttribute(
            'target',
            $this->arguments['target']
        );
        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();
    }
}