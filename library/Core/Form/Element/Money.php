<?php

class Core_Form_Element_Money extends \Core\Form\Element\Xhtml
{

    public function init()
    {
	$this->setAttribs(array(
	    'size' => 12,
	    'maxLength' => 12,
	    'class' => 'money ' . $this->getAttrib('class')
	));
    }

}