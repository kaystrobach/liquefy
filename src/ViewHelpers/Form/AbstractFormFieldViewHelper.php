<?php

namespace KayStrobach\Liquefy\ViewHelpers\Form;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class AbstractFormFieldViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'input';

    /**
     * Initialize the arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('type', 'string', 'Name of input tag');
        $this->registerArgument('name', 'string', 'Name of input tag');
        $this->registerArgument('value', 'mixed', 'Value of input tag');
        $this->registerArgument('property', 'string', 'Name of Object Property. If used in conjunction with <f:form object="...">, "name" and "value" properties will be ignored.');
    }
    
    /**
     * Get the name of this form element.
     * Either returns arguments['name'], or the correct name for Object Access.
     *
     * In case property is something like bla.blubb (hierarchical), then [bla][blubb] is generated.
     *
     * @return string Name
     */
    protected function getName()
    {
        return $this->arguments['name'];
    }

    protected function getValueAttribute($ignoreSubmittedFormData = false)
    {
        return $this->arguments['value'] ?? $this->arguments['property'];
    }
}
