<?php

namespace KayStrobach\Liquefy\ViewHelpers\Form;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class TextfieldViewHelper extends AbstractFormFieldViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');
        $this->registerTagAttribute('maxlength', 'int', 'The maxlength attribute of the input field (will not be validated)');
        $this->registerTagAttribute('readonly', 'string', 'The readonly attribute of the input field');
        $this->registerTagAttribute('size', 'int', 'The size of the input field');
        $this->registerTagAttribute('placeholder', 'string', 'The placeholder of the input field');
        $this->registerTagAttribute('autofocus', 'string', 'Specifies that a input field should automatically get focus when the page loads');
        $this->registerTagAttribute('type', 'string', 'InputType', false, 'text');
        $this->registerTagAttribute('required', 'boolean', 'name');
        $this->registerTagAttribute('autocomplete', 'string', 'autocomplete');
        $this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', false, 'f3-form-error');
        $this->registerUniversalTagAttributes();
    }

    protected $tagName = 'input';

    /**
     * Renders the textfield.
     *
     * @return string
     * @api
     */
    public function render()
    {
        if (!$this->tag->hasAttribute('name')) {
            $this->tag->addAttribute('name', $this->arguments['property']);
        }

        if ($this->arguments['value'] !== null) {
            $this->tag->addAttribute('value', $this->arguments['value']);
        }

        if ($this->arguments['required'] === true) {
            $this->tag->addAttribute('required', 'required');
        }

        return $this->tag->render();
    }
}
