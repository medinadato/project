<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Core;

/**
 * Form
 *
 * @author Administrator
 */
class Form extends \ZendX_JQuery_Form
{

    public function __construct($options = null)
    {
	$this->addPrefixPath('Core_Form', 'Core/Form/');
	$this->addElementPrefixPath('Core_Validate', 'Core/Validate/', 'validate');

	$this->setAttrib('accept-charset', 'UTF-8');

	$this->setDecorators(array('FormElements', 'Form'));

	parent::__construct($options);
    }

    public function loadDefaultDecorators()
    {
	if ($this->loadDefaultDecoratorsIsDisabled()) {
	    return;
	}

	$decorators = $this->getDecorators();
	if (empty($decorators)) {
	    $this->addDecorator('FormElements');
	    $this->removeDecorator('DtDdWrapper');
	}
    }

    /**
     * Gets the entity manager
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm($name = null)
    {
	/** @var \Doctrine\ORM\EntityManager */
	$em = \Zend_Registry::get('doctrine')->getEntityManager();
	return $em;
    }

}