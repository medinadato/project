<?php
namespace Core\Controller;

/**
 * 
 */
abstract class Action extends \Zend_Controller_Action
{

    /**
     * Doctrine EntityManager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em, $_em;
    /**
     *
     * @var type 
     */
    protected $conn, $_conn;

    public function init()
    {
	$this->_em = $this->em = $this->getDoctrineContainer()->getEntityManager();
	$this->_conn = $this->conn = $this->getDoctrineContainer()->getConnection();
    }

    /**
     * Retrieve the Doctrine Container.
     *
     * @return Bisna\Doctrine\Container
     */
    public function getDoctrineContainer()
    {
	return $this->getInvokeArg('bootstrap')->getResource('doctrine');
    }
    
    /**
     * Retrieve the ServiceLocator Container.
     *
     * @return Bisna\Base\Service\ServiceLocator
     */
    public function getServiceLocator()
    {
	return $this->getInvokeArg('bootstrap')->getResource('serviceLocator');
    }

    /**
     * redirects
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param string $options 
     */
    public function redirect($action = null, $controller = null, $module = null, $options = array())
    {
	$this->_helper->redirector($action, $controller, $module, $options);
    }

    public function request($index = null)
    {
	return (isset($index)) ? $this->getRequest()->getParam($index) : $this->getRequest()->getParams();
    }

}

?>