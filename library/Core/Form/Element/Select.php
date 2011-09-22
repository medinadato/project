<?php

class Core_Form_Element_Select extends Zend_Form_Element_Select
{

    public function setMultiOptions(array $options)
    {
	$this->clearMultiOptions();
	$this->options[NULL] = 'Selecione...';
	return $this->addMultiOptions($options);
    }

    public function setRequired($flag = true)
    {
	$this->_required = (bool) $flag;
	if ($this->_required)
	    $this->setAttrib('class', $this->getAttrib('class') . 'required');

	return $this;
    }

}