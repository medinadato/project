<?php

class Core_Form_Element_Phone extends \Core\Form\Element\Xhtml
{

    public function init()
    {
	$this->setAttribs(array(
	    'size' => 14,
	    'maxLength' => 14,
	    'alt' => 'phone',
	    'class' => 'phone ' . $this->getAttrib('class')
	));
    }
}