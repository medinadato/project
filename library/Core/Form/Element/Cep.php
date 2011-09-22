<?php
class Core_Form_Element_Cep extends \Core\Form\Element\Xhtml
{
  public function init()
  {
	$this->setAttribs(array(
		'size'      => 9,
		'maxLength' => 9,
		'alt'       => 'cep',
		'class'     => 'cep ' . $this->getAttrib('class')
	));
  }
  
  public function setRequired($flag = true)
  {
	$this->_required = (bool) $flag;
	if ($this->_required)
	  $this->setAttrib('class', $this->getAttrib('class') . 'required');

	return $this;
  }
}