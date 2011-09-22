<?php

class Core_Form_Element_Radio extends Zend_Form_Element_Radio
{
  public function init()
  {
	$this->setDecorators(array(
		'ViewHelper',
		'Label',
		array('HtmlTag', array('tag' => '<dd>', 'class' => 'radio'))
	));

	$this->setAttrib('class', 'radio ' . $this->getAttrib('class'));
	// remove separator <br />
	//$this->setSeparator('');
  }
}
?>