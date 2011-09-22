<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Core\Plugin;

use \Zend_Controller_Action_HelperBroker as HelperBroker;
/**
 * Description of Auth
 *
 * @link    www.servfacil.com
 * @since   1.0
 * @version $Revision$
 * @author Desenvolvimento
 */
class Auth extends \Zend_Controller_Plugin_Abstract
{

    /**
     * @var Zend_Auth
     */
    protected $auth = null;
    /**
     * @var Zend_Acl
     */
    protected $acl = null;
    /**
     * @var array
     */
    protected $notLoggedRoute = array(
	'controller' => 'auth',
	'action' => 'login',
	'module' => 'web'
    );
    /**
     * @var array
     */
    protected $forbiddenRoute = array(
	'controller' => 'error',
	'action' => 'forbidden',
	//'module' => 'admin'
    );

    public function __construct()
    {
	$this->auth = \Zend_Auth::getInstance();
	$this->acl = \Zend_Registry::get('acl');
	$this->usuario = $this->auth->getStorage()->read();
    }
    
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
	$controller = "";
	$action = "";
	$module = "";
	
	if (!$this->auth->hasIdentity()) {
	    $controller = $this->notLoggedRoute['controller'];
	    $action = $this->notLoggedRoute['action'];
	} elseif (!$this->isAuthorized($request->getControllerName(), $request->getActionName())) {
	    $controller = $this->forbiddenRoute['controller'];
	    $action = $this->forbiddenRoute['action'];
	} else {
	    
	    $rotasPermitidas = array(
		'controllers' => array('usuario', 'error'),
		'actions' => array('mudar-senha-provisoria', 'error')
	    );
	    
	    //se usuario logado está com senha provisoria
	    if ($this->usuario->getIsSenhaProvisoria() == 'S') {
		$controller = 'usuario';
		$action = 'mudar-senha-provisoria';
		//redireciona o usuário para a página de mudança de senha
		if (
		    !in_array($request->getControllerName(), $rotasPermitidas['controllers']) && 
		    !in_array($request->getActionName(), $rotasPermitidas['actions'])
		    ){
		     HelperBroker::addHelper(
			new \Zend_Controller_Action_Helper_Redirector()
		    );

		    $helper = HelperBroker::getExistingHelper('redirector');
		    $helper->gotoSimple('mudar-senha-provisoria', 'usuario');
		}
	    } else {
		$controller = $request->getControllerName();
		$action = $request->getActionName();	
	    }
	}
	
	$request->setControllerName($controller);
	$request->setActionName($action);
    }

    protected function isAuthorized($controller, $action)
    {
	$role = $this->usuario->getRoleId();
	
	if (!$this->acl->has($controller) || !$this->acl->isAllowed($role, $controller, $action))
	    return false;

	return true;
    }

}