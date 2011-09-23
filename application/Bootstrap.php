<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Load namespaced libraries
     */
    protected function _initAutoloaderNamespaces()
    {
	require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';
        
	//third party namespaced libraries
	$vendors = array(
	    'Core'    => null,
	    'Zend'    => null,
	    'ZendX'   => null,
	    'App'     => null
	);

	$autoloader = \Zend_Loader_Autoloader::getInstance();

	foreach ($vendors as $vendor => $path) {
	    $fmmAutoloader = new \Doctrine\Common\ClassLoader($vendor, $path);
	    $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), $vendor);
	}
    }

    /**
     * 
     */
    protected function _initAutoLoader()
    {
	$autoloader = \Zend_Loader_Autoloader::getInstance();
	$autoloader->registerNamespace('Core');
	$autoloader->registerNamespace('App');
    }

    /**
     * Load acl
     */
    protected function _initAclNav()
    {
        /*
	// acl
	$aclSetup = new \Core\Acl\Setup;
	// navigation
	$navSetup = new \Core\View\Navigation\Setup;
        */
    }

    /**
     * Load Views' Configs
     */
    protected function _initViews()
    {
	$this->bootstrap('view');
	$view = $this->getResource('view');

	$view->addHelperPath("Core/View/Helper", "Core_View_Helper");
	$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");

	$view->doctype("XHTML1_STRICT");
	$view->headTitle("Titulo")->setSeparator(" | ");
	$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');

	Zend_Registry::set('view', $view);
    }

    /**
     * 
     */
    protected function _initViewHelpers()
    {
	$layout = $this->getResource('layout');
	$view = $this->getResource('view');
        /*
	$navConfig = \Zend_Registry::get('navConfig');
	$navContanier = new \Zend_Navigation($navConfig);
	$nav = $view->navigation($navContanier);
	$auth = \Zend_Auth::getInstance();

	if ($auth->hasIdentity() && \Zend_Registry::isRegistered('acl')) {
	    $role = $auth->getIdentity()->getRoleId();
	    $acl = \Zend_Registry::get('acl');
	    $nav->setAcl($acl)->setRole($role);
	}
         * 
         */
    }

    /**
     * 
     */
    protected function _initFlashMessenger()
    {
        /*
	// @var $flashMessenger Zend_Controller_Action_Helper_FlashMessenger
	$flashMessenger = \Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

	if ($flashMessenger->hasMessages()) {
	    $view = $this->getResource('view');
	    $view->messages = $flashMessenger->getMessages();
	}

        */
    }
}

