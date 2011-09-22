<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Acl;

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

    /**
     * @var Zend_Acl
     */
    protected $acl;
    protected $conn;

    public function __construct()
    {
	$this->acl = new \Zend_Acl();
	$this->conn = \Zend_Registry::get('doctrine')->getEntityManager()->getConnection();
	$this->initialize();
    }

    protected function initialize()
    {
	# config variable cache
	$frontendOptions = array(
	    'lifetime' => 3600, // cache lifetime of 1 hours
	    'automatic_serialization' => true
	);

	$backendOptions = array(
	    'cache_dir' => APPLICATION_PATH . '/../cache/' // Directory where to put the cache files
	);

	// getting a Zend_Cache_Core object
	$cache = \Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

	// if the cache don't exist
	if (!$cache->load('acl')) {
	    // load information
	    $this->setupRoles();
	    $this->setupResources();
	    $this->setupPrivileges();
	    // caching information
	    $cache->save($this->acl, 'acl');
	} else {
	    $this->acl = $cache->load('acl');
	}

	$this->saveAcl();
    }

    protected function setupRoles()
    {
	$sql = "SELECT PU.NOM_PERFIL_USUARIO 
		FROM PERFIL_USUARIO PU 
		WHERE PU.NOM_PERFIL_USUARIO NOT IN ('ADMIN', 'CONVIDADO')
		ORDER BY 1 ASC";

	$roles = $this->conn->query($sql);

	$this->acl->addRole(new \Zend_Acl_Role('CONVIDADO'));
	$this->acl->addRole(new \Zend_Acl_Role('ADMIN'), 'CONVIDADO');


	while ($row = $roles->fetch()) {
	    $role = $row['NOM_PERFIL_USUARIO'];
	    $this->acl->addRole(new \Zend_Acl_Role($role), 'CONVIDADO');
	}
    }

    protected function setupResources()
    {
	//recurso padrÃ£o de autenticaÃ§Ã£o.
	$this->acl->addResource(new \Zend_Acl_Resource('auth'));
	//recurso padrÃ£o de erros.
	$this->acl->addResource(new \Zend_Acl_Resource('error'));

	$sql = "SELECT R.NOM_RECURSO FROM RECURSO R 
		ORDER BY R.NOM_RECURSO ASC";
	$query = $this->conn->query($sql);

	while ($row = $query->fetch()) {
	    $this->acl->addResource(new \Zend_Acl_Resource($row['NOM_RECURSO']));
	}
    }

    protected function setupPrivileges()
    {
	//acesso total para administrador
	$this->acl->allow('ADMIN');
	//dando acesso somente para acoes iniciais
	$this->acl->allow('CONVIDADO', null, 'index');
	//permitindo acesso para login
	$this->acl->allow('CONVIDADO', 'auth', array('login', 'logout'));
	//permitindo acesso para ver erros
	$this->acl->allow('CONVIDADO', 'error', array(
	    'error',
	    'forbidden',
	    'mudar-deposito-logado',
	    'sem-deposito-logado',
	    'sem-permissao-depositos'
	));

	$sql = "SELECT DISTINCT PU.NOM_PERFIL_USUARIO, R.NOM_RECURSO, A.DSC_ACAO
		FROM PERFIL_USUARIO PU
		INNER JOIN PERFIL_USUARIO_RECURSO_ACAO PURA ON (PU.COD_PERFIL_USUARIO = PURA.COD_PERFIL_USUARIO)
		INNER JOIN RECURSO_ACAO RA ON (PURA.COD_RECURSO_ACAO = RA.COD_RECURSO_ACAO)
		INNER JOIN RECURSO R ON (RA.COD_RECURSO = R.COD_RECURSO)
		INNER JOIN ACAO A ON (RA.COD_ACAO = A.COD_ACAO)
		ORDER BY R.NOM_RECURSO ASC, A.DSC_ACAO";

	// user permissions
	$permissoes = $this->conn->query($sql);

	while ($permissao = $permissoes->fetch()) {
	    $role = $permissao['NOM_PERFIL_USUARIO'];
	    $resource = $permissao['NOM_RECURSO'];
	    $action = $permissao['DSC_ACAO'];
	    $this->acl->allow($role, $resource, array($resource, $action));
	}
    }

    protected function saveAcl()
    {
	$registry = \Zend_Registry::getInstance();
	$registry->set('acl', $this->acl);
    }

}