<?php
class Core_Form_Element_Link extends \Core\Form\Element\Xhtml
{
  public function init()
  {
	  $this->setAttrib('class', '{url:true} ' . $this->getAttrib('class'));
  }
  public function setRequired($flag = true)
  {
	$this->_required = (bool) $flag;
	if ($this->_required)
	  $this->setAttrib('class', $this->getAttrib('class') . 'required');

	return $this;
  }
}