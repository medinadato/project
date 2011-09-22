<?php

class Core_Form_Element_Cpf extends \Core\Form\Element\Xhtml
{

    public function init()
    {
	$this->setAttribs(array(
	    'size' => 18,
	    'maxLength' => 14,
	    'alt' => 'cpf',
	    'class' => 'cpf ' . $this->getAttrib('class')
	));
    }

}