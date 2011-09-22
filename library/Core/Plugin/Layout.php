<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Core\Plugin;

/**
 * Description of Acl
 *
 * @link    www.servfacil.com
 * @since   1.0
 * @version $Revision$
 * @author Desenvolvimento
 */
class Layout extends \Zend_Controller_Plugin_Abstract
{

    public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
    {
	$layout = \Zend_Layout::getMvcInstance();
	$layout->setLayout('layout')
		->setLayoutPath(APPLICATION_PATH . "/modules/" . $request->getModuleName() . "/views/layout/");
    }

    /**
     * @param Zend_Controller_Request_Abstract $request 
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
	// view user infos
	/* $identity = \Zend_Auth::getInstance()->getStorage()->read();
	  $this->view->userFullName = $identity->getFullName();
	  $this->view->userName = $identity->getUserName();
	  $this->view->userRole = $identity->getRoleId(); */
	
	//disable view & layout for ajax requests
	if ($request->isXmlHttpRequest()) {
	    \Zend_Layout::getMvcInstance()->disableLayout();
	    \Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->setNoRender(true);
	}
    }

}