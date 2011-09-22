<?php
namespace Core\Form\Element;

class Xhtml extends \Zend_Form_Element_Xhtml
{
    /**
     * 
     * @param type $flag
     * @return Xhtml 
     */
    public function setRequired($flag = true)
    {
	$this->_required = (bool) $flag;

	if ($this->_required) {
	    $this->setAttrib('class', $this->getAttrib('class') . ' required');
	}

	return $this;
    }
    
    public function setMaxlength($maxlength)
    {
	$this->addValidator('StringLength', false, array(0, $maxlength));
	$this->setAttrib('maxlength', $maxlength);
    }
}