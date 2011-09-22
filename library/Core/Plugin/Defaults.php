<?php
namespace Core\Plugin;

class Defaults extends \Zend_Controller_Plugin_Abstract
{

    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
	//get view
	$viewRenderer = \Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer');
	$view = $viewRenderer->view;
	$em = \Zend_Registry::get('doctrine')->getEntityManager();
	$auth = \Zend_Auth::getInstance();
	
	if ($request->isXMLHttpRequest()) {
	    $layout = \Zend_Layout::getMvcInstance();
	    $layout->disableLayout();
	    $viewRenderer->setNoRender(true);
	} else {   
	    if ($auth->hasIdentity()) {
		$usuarioSessao = $auth->getIdentity();
		$usuario = $em->getRepository('wms:Usuario')
			      ->find($usuarioSessao->getId());
		
		$view->nomeUsuario = $usuario->getPessoa()->getNome();
		$view->perfilUsuario = $usuario->getPerfil()->getNome();
	    }
	    
	    $view->request = $request;
	}
    }

}