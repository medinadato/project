<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Core\View\Navigation;

/**
 * Description of Setup
 *
 * @link    www.servfacil.com
 * @since   1.0
 * @version $Revision$
 * @author Desenvolvimento
 */
class Setup
{    
    public function __construct()
    {
	$this->_initialize();
    }

    protected function _initialize()
    {
	$frontendOptions = array(
	    'lifetime' => 3600,
	    'automatic_serialization' => true
	);
	$backendOptions = array('cache_dir' => APPLICATION_PATH . '/../cache/');
	$cache = \Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

	//if (!$cache->load('navConfig')) {
	    $navConfig = new \Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');    
	    //$cache->save($navConfig, 'navConfig');
	/*} else {
	    $navConfig = $cache->load('navConfig');
	}*/

	\Zend_Registry::getInstance()->set('navConfig', $navConfig);
    }

}
