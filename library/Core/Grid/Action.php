<?php
namespace Core\Grid;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Column
 *
 * @author Administrator
 */
class Action
{

    /**
     * label of column
     * @var string
     */
    protected $label;
    /**
     * the name of the action used on the url
     * @var string
     */
    protected $actionName;
    /**
     * the name of the controller used on the url
     * @var string
     */
    protected $controllerName;
    /**
     * the actions params url by url
     * @var string
     */
    protected $params;
    /**
     * the static url string defined by user
     * @var string
     */
    protected $userDefinedUrl;
    /**
     * sets the condition the to action attend
     * @var string
     */
    protected $condition;
    /**
     * the row index that contain the PK of the record
     * @var mixed
     */
    protected $pkIndex;
    /**
     * the css class use by link of the action
     * @var type 
     */
    protected $cssClass;
    /**
     * html title to the action
     * @var type 
     */
    protected $title;

    /**
     * contructor of the grid
     * @param array $options
     * @return Column 
     */
    public function __construct(array $options = array())
    {
	\Zend\Stdlib\Configurator::configure($this, $options);
	return $this;
    }

    /**
     * Gets de label
     * @return string
     */
    public function getLabel()
    {
	return (string) $this->label;
    }

    /**
     * Sets the label
     * @param string $label
     * @return Column 
     */
    public function setLabel($label)
    {
	$this->label = (string) $label;
	return $this;
    }

    /**
     * sets the user defined url
     * @param string $url
     * @return Action 
     */
    public function setUserDefinedUrl($url)
    {
	$this->userDefinedUrl = (string) $url;
	return $this;
    }

    /**
     * returns the user defined url
     * @return string
     */
    public function getUserDefinedUrl()
    {
	return null;
    }

    /**
     * sets the action name
     * @param string $actionName
     * @return Action 
     */
    public function setActionName($actionName)
    {
	$this->actionName = (string) $actionName;
	return $this;
    }

    /**
     * returns the action name
     * @return string
     */
    public function getActionName()
    {
	return $this->actionName;
    }

    /**
     * sets the controller name
     * @param type $controllerName
     * @return Action 
     */
    public function setControllerName($controllerName)
    {
	$this->controllerName = (string) $controllerName;
	return $this;
    }

    /**
     * returns the controller name
     * @return type 
     */
    public function getControllerName()
    {
	return $this->controllerName;
    }

    /**
     * returns the url paramns
     * @return array
     */
    public function getParams()
    {
	return $this->params;
    }

    /**
     * sets the url params
     * @param type $params
     * @return Action 
     */
    public function setParams(array $params)
    {
	$this->params = $params;
	return $this;
    }

    /**
     * gets the condition thats the action attends
     * @return string
     */
    public function getCondition()
    {
	return $this->condition;
    }

    /**
     * sets the condition to the action attend
     * @param string $condition
     * @return Action 
     */
    public function setCondition($condition)
    {
	$this->condition = $condition;
	return $this;
    }

    /**
     * returns the PK Index
     * @return mixed
     */
    public function getPkIndex()
    {
	return $this->pkIndex;
    }

    /**
     * the PK index of the row
     * @param mixed $pkIndex
     * @return Action 
     */
    public function setPkIndex($pkIndex)
    {
	$this->pkIndex = $pkIndex;
	return $this;
    }

    /**
     * returns the css class of the action
     * @return type 
     */
    public function getCssClass()
    {
	return $this->cssClass;
    }

    /**
     * sets the css class of the action
     * @param string $cssClass 
     */
    public function setCssClass($cssClass)
    {
	$this->cssClass = (string) $cssClass;
    }

    /**
     * returns if the action attends to condition
     * @param array $row
     * @return bool
     */
    public function attendToRowCondition(array $row)
    {
	$condition = $this->getCondition();
	return true;
    }

    /**
     * returns the url string based on a row
     * @param array $row
     * @return string
     */
    public function getUrl(array $row)
    {
	$params = array();

	if (null !== $this->getUserDefinedUrl()) {
	    //returns full defined url
	    $url = $this->getUserDefinedUrl();
	} else {
	    //build a zend framework url
	    if (null !== $this->getActionName())
		$params['action'] = $this->getActionName();
	    if (null !== $this->getControllerName())
		$params['controller'] = $this->getControllerName();
	    if (null !== $this->getPkIndex())
		$params[$this->getPkIndex()] = $row[$this->getPkIndex()];
	    if (null !== $this->getParams())
		$params = array_merge($params, $this->getParams());


	    $helper = new \Zend_View_Helper_Url;
	    $url = $helper->url($params);
	}
	return $url;
    }

    public function getTitle()
    {
	return $this->title;
    }

    public function setTitle($title)
    {
	$this->title = $title;
    }

}
