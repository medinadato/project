<?php
class Core_Form_Element_Dob extends \Core\Form\Element\Xhtml
{
    public $helper = "formDob";
    
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'Core_Form_Decorator', 
            'Core/Form/Decorator', 
            'decorator'
        );
        
        parent::__construct($spec, $options);
    }
}