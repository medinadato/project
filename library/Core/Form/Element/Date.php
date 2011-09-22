<?php
class Core_Form_Element_Date extends \Core\Form\Element\Xhtml
{
  public function init()
  {
	$this->setAttribs(array(
		'size'      => 11,
		'maxLength' => 10,
		'class'     => 'date ' . $this->getAttrib('class')
	));
  }
}