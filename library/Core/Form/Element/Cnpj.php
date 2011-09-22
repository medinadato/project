<?php
class Core_Form_Element_Cnpj extends \Core\Form\Element\Xhtml
{
  public function init()
  {
	$this->setAttribs(array(
		'size'      => 18,
		'maxLength' => 18,
		'alt'       => 'cnpj',
		'class'     => 'cnpj ' . $this->getAttrib('class')
	));
  }
}