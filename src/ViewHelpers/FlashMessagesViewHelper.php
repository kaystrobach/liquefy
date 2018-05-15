<?php

namespace KayStrobach\Liquefy\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class FlashMessagesViewHelper extends AbstractViewHelper
{
    /**
     * Specifies whether the escaping interceptors should be disabled or enabled for the result of renderChildren() calls within this ViewHelper
     * @see isChildrenEscapingEnabled()
     *
     * Note: If this is NULL the value of $this->escapingInterceptorEnabled is considered for backwards compatibility
     *
     * @var boolean
     * @api
     */
    protected $escapeChildren = false;

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
        $this->registerArgument('as', 'string', '');
    }

    /**
     * @return integer
     */
    public function render()
    {
        $this->renderingContext->getVariableProvider()->add('arguments', $this->arguments);

        if (!$this->renderingContext->getVariableProvider()->exists($this->arguments['as'])) {
            $settings = $this->renderingContext->getVariableProvider()->get('__flashmessages__');
            if (isset($settings)) {
                $this->renderingContext->getVariableProvider()->add(
                    $this->arguments['as'],
                    $settings
                );
            }
        }
        $buffer = $this->renderChildren();
        $this->renderingContext->getVariableProvider()->remove($this->arguments['as']);
        return $buffer;
    }
}