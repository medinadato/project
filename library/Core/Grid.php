<?php
namespace Core;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Datagrid
 *
 * @author Administrator
 */
class Grid
{

    /**
     * html id used by grid
     * @var string
     */
    protected $id;
    /**
     * source of the grid
     * @var Grid\Source\ISource
     */
    protected $source;
    /**
     * if the grid will show headers
     * @var bool
     */
    protected $showHeaders = true;
    /**
     * if the grid will filter results
     * @var bool
     */
    protected $showFilter = true;
    /**
     * if the grid should order results
     * @var boolean 
     */
    protected $hasOrdering = true;
    /**
     * if the grid should export results
     * @var boolean 
     */
    protected $showExport = false;
    /**
     * if the grid should paginate results
     * @var boolean 
     */
    protected $hasPager = true;
    /**
     * if the grid should use mass actions results
     * @var boolean 
     */
    protected $hasMassActions = true;
    /**
     * path for templates
     * @var boolean 
     */
    protected $templatePath = 'templates/';
    /**
     * template used by grid
     * @var boolean 
     */
    protected $template = 'grid';
    /**
     * columns used by grid
     * @var array
     */
    protected $columns = array();
    /**
     * actions used by grid
     * @var array
     */
    protected $actions = array();
    /**
     * if the grid was builded
     * @var bool
     */
    private $builded = false;
    /**
     * pager used by grid
     * @var Core\Grid\Pager; 
     */
    protected $pager;
    /**
     * The user request
     * @var Zend_Controller_Request_Abstract
     */
    protected $request;
    /**
     * number of the actual page
     * @var int
     */
    protected $page;
    /**
     * order config
     * @var array
     */
    protected $order;

    /**
     * Constructor of the class
     * @param Core\Grid\Source\ISource $source
     * @param array $options 
     */
    public function __construct(\Core\Grid\Source\ISource $source, array $options = array())
    {
	$this->setSource($source);

	if (isset($options))
	    $this->setOptions($options);
    }

    /**
     * Set grid state from options array
     *
     * @param  array $options
     * @return Grid
     */
    public function setOptions(array $options)
    {
	foreach ($options as $key => $value) {
	    $method = 'set' . ucfirst($key);
	    //forbiden options
	    if (in_array($method, array()))
		if (!is_object($value))
		    continue;

	    if (method_exists($this, $method))
	    // Setter exists; use it
		$this->$method($value);
	    else
		throw new Grid\Exception("Unknown option {$method}");
	}
	return $this;
    }

    /**
     * Sets the source of the grid
     * @param \Core\Grid\Source\ISource $source
     * @return Grid 
     */
    public function setSource(\Core\Grid\Source\ISource $source)
    {
	$this->source = $source;
	return $this;
    }

    /**
     * gets source
     * @return Grid\Source\ISource
     */
    public function getSource()
    {
	return $this->source;
    }
    
    /**
     * Returns HTML id of the grid
     * @return string
     */
    public function getId()
    {
	return $this->id;
    }
    
    /**
     * Sets HTML id of the grid
     * @param string $id
     * @return Grid 
     */
    public function setId($id)
    {
	$this->id = $id;
	return $this;
    }

    /**
     * Add a new column
     *
     * $column may be either an array column options, or an object of type
     * Grid\Column. 
     *
     * @param  array|Grid\Column $column
     * @throws Core\Grid\Exception on invalid element
     * @return Grid
     */
    public function addColumn($column)
    {
	if (is_array($column)) {
	    $options = $column;

	    if (null === $options['index'])
		throw new Grid\Exception('Columns specified by array must have an accompanying index');

	    $this->columns[] = $this->createColumn($options);
	} elseif ($column instanceof Grid\Column) {

	    if (null === $column->getIndex())
		throw new Grid\Exception('Columns must have an accompanying index');

	    $this->columns[$column->getIndex()] = $element;
	} else {
	    throw new Grid\Exception('Column must be specified by array options or Core\Grid\Column instance');
	}

	return $this;
    }

    /**
     * Create a column
     *
     * Acts as a factory for creating column. Columns created with this
     * method will not be attached to the grid, but will contain column
     * settings as specified in the grid object (including plugin render
     * ordering, filter, etc.).
     *
     * @param  string $type
     * @param  string $name
     * @param  array $options
     * @return Grid\Column
     */
    public function createColumn(array $options)
    {
	$column = new Grid\Column($options);
	return $column;
    }

    /**
     * Returns the columns of the grid
     * @return array
     */
    public function getColumns()
    {
	return $this->columns;
    }

    /**
     * Adds an user action to the grid
     * @return Grid 
     */
    public function addAction($action)
    {
	if (is_array($action))
	    $action = new \Core\Grid\Action($action);
	elseif ($action instanceof \Core\Grid\Action)
	    $action = $action;
	else
	    throw new \Exception('Invalid action param');

	$this->actions[] = $action;
	return $this;
    }

    /**
     * returns actions
     * @return array
     */
    public function getActions()
    {
	return $this->actions;
    }

    /**
     * returns only actions that attends to self condition
     * @param array $row
     * @return array
     */
    public function getActionsByRow(array $row)
    {
	$tmpActions = array();
	$actions = $this->getActions();
	foreach ($actions as $action)
	    if ($action->attendToRowCondition($row))
		$tmpActions[] = $action;
	return $tmpActions;
    }

    /**
     * sets the template to be used
     * @param string $template template name
     * @return Grid 
     */
    public function setTemplate($template)
    {
	$this->template = (string) $template;
	return $this;
    }

    /**
     * Returns if the grid uses filter
     * @return bool
     */
    public function getShowFilter()
    {
	return $this->showFilter;
    }

    /**
     * just an alias for $this->getHasFilter()
     * @return bool
     */
    public function showFilter()
    {
	return $this->getShowFilter();
    }

    /**
     * Sets if the grid uses filter
     * @param bool $filtered 
     * @return Grid
     */
    public function setShowFilter($filtered)
    {
	$this->showFilter = (bool) $filtered;
	return $this;
    }

    /**
     * return the order config
     * @return array
     */
    public function getOrder()
    {
	return $this->order;
    }

    /**
     * Returns if the grid can export results
     * @return bool
     */
    public function getShowExport()
    {
	return $this->showExport;
    }

    /**
     * just an alias for $this->getHasExport()
     * @return bool
     */
    public function showExport()
    {
	return $this->getShowExport();
    }

    /**
     * Sets if the can exports results
     * @param bool $ordered 
     * @return Grid
     */
    public function setShowExport($canExport)
    {
	$this->hasExport = (bool) $canExport;
	return $this;
    }

    /**
     * returns the pager used by grid
     * @return Core\Grid\Pager; 
     */
    public function getPager()
    {
	return $this->pager;
    }

    /**
     * sets the pager
     * @param \Core\Grid\Pager $pager
     * @return Grid 
     */
    public function setPager(\Core\Grid\Pager $pager)
    {
	$this->pager = $pager;
	return $this;
    }

    /**
     * Returns if the grid paginate results
     * @return bool
     */
    public function getShowPager()
    {
	return $this->hasPager;
    }

    /**
     * just an alias for $this->getHasPager()
     * @return bool
     */
    public function showPager()
    {
	return $this->getShowPager();
    }

    /**
     * Returns the actual page
     * @return int
     */
    public function getPage()
    {
	return $this->page;
    }

    /**
     * Sets the actual page
     * @return int
     */
    public function setPage($page)
    {
	$this->page = (int) $page;
	return $this;
    }

    /**
     * Sets if the grid paginate results
     * @param bool $paginated 
     * @return Grid
     */
    public function setHasPager($paginated)
    {
	$this->hasPager = (bool) $paginated;
	return $this;
    }

    /**
     * returns if the grid use mass actions
     * @return bool 
     */
    public function getHasMassActions()
    {
	return $this->hasMassActions;
    }

    /**
     * just an alias for $this->getHasMassActions()
     * @return bool
     */
    public function hasMassActions()
    {
	return $this->getHasMassActions();
    }

    /**
     * sets if the grid uses mass actions
     * @param type $useMassActions 
     * @return Grid
     */
    public function setHasMassActions($useMassActions)
    {
	$this->hasMassActions = (bool) $useMassActions;
	return $this;
    }

    /**
     * get the template path
     * @return string
     */
    public function getTemplatePath()
    {
	return $this->templatePath;
    }

    /**
     * sets the template path
     * @param string $templatePath 
     * @return Grid
     */
    public function setTemplatePath($templatePath)
    {
	$this->templatePath = (string) $templatePath;
	return $this;
    }

    /**
     * sets if the grid shows headers
     * @param bool $value
     * @return Grid 
     */
    public function setShowHeaders($value)
    {
	$this->showHeaders = (bool) $value;
	return $this;
    }

    /**
     * returns if the grid shows headers
     * @return bool
     */
    public function getShowHeaders()
    {
	return $this->showHeaders;
    }

    /**
     * just an alias for Grid::getShowHeaders()
     * @return bool 
     */
    public function showHeaders()
    {
	return $this->getShowHeaders();
    }

    /**
     * Returns the HTML output 
     * @param string $name name of the template
     * @return string
     */
    public function render($name = NULL)
    {
	if ($this->builded === false)
	    $this->build();

	$view = new \Zend_View;
	$view->setBasePath(APPLICATION_PATH . '/' . $this->getTemplatePath());
	$view->setScriptPath(APPLICATION_PATH . '/' . $this->getTemplatePath());
	$view->grid = $this;
	return $view->render('grid.phtml');
    }

    /**
     * The user request
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
	return $this->request;
    }

    /**
     * Sets the request object
     * @param \Zend_Controller_Request_Abstract $request
     * @return Grid 
     */
    public function setRequest(\Zend_Controller_Request_Abstract $request)
    {
	$this->request = $request;
	return $this;
    }

    /**
     * sets number of results of the grid
     * @param type $total
     * @return Grid 
     */
    public function setNumResults($total)
    {
	$this->numResults = (int) $total;
	return $this;
    }

    /**
     * return the number of results of the grid
     * @return int 
     */
    public function getNumResults()
    {
	return $this->numResults;
    }

    /**
     * Build user defined filters
     *
     * @return Grid
     */
    protected function buildDefaultFiltersValues()
    {
	if ($this->_paramsInSession === true) {
	    if ($this->getParam('noFilters')) {
		$this->_sessionParams->filters = null;
	    }
	}

	if ((is_array($this->_defaultFilters) || $this->_paramsInSession === true)
		&& !$this->hasFilters()
		&& !$this->getParam('noFilters')
	) {

	    foreach ($this->_data['fields'] as $key => $value) {
		if (!$this->_displayField($key)) {
		    continue;
		}

		if ($this->_paramsInSession === true) {
		    if (isset($this->_sessionParams->filters[$key])) {
			if (is_array($this->_sessionParams->filters[$key])) {
			    foreach ($this->_sessionParams->filters[$key] as $skey => $svalue) {
				if (!isset($this->_ctrlParams[$key . $this->getGridId() . '[' . $skey . ']'])) {
				    $this->_ctrlParams[$key . $this->getGridId() . '[' . $skey . ']'] = $svalue;
				}
			    }
			} else {
			    if (!isset($this->_ctrlParams[$key . $this->getGridId()])) {
				$this->_ctrlParams[$key . $this->getGridId()] = $this->_sessionParams->filters[$key];
			    }
			}
			continue;
		    }
		}

		if (is_array($this->_defaultFilters) && array_key_exists($key, $this->_defaultFilters)) {
		    $this->_ctrlParams[$key] = $this->_defaultFilters[$key];
		}
	    }
	}

	return $this;
    }

    /**
     * Process the user request setting the grid defaults
     * @return Grid 
     */
    private function processRequest()
    {
	if (null === $this->getRequest())
	//get defult request
	    $request = \Zend_Controller_Front::getInstance()->getRequest();
	else
	//get user defined request
	    $request = $this->getRequest();

	$this->setRequest($request);

	return $this;
    }

    /**
     * count records on source - this aux the pager
     * @return Grid 
     */
    private function countRecords()
    {
	$total = $this->getSource()->count();
	$this->setNumResults($total);
	$this->counted = true;
	return $this;
    }

    /**
     * Process the pager 
     * @return Grid 
     */
    private function processPager()
    {
	if (!$this->counted)
	    throw new Exception('You need do count the records before build the pager. Use Grid::countRecords()');

	// if page don't exists
	if (null == $this->getPage()) {
	    //gets the page by request
	    $page = $this->getRequest()->getParam('page');
	    $this->setPage($page);
	}

	if (null === $this->getPager()) {
	    //get default pager
	    $pager = new \Core\Grid\Pager($this->getNumResults(), $this->getPage());
	    $this->setPager($pager);
	} else {
	    //get user defined pager
	    $pager = $this->getPager();
	}

	$this->getSource()
		->setLimit($pager->getMaxPerPage())
		->setOffset($pager->getOffset());

	return $this;
    }

    /**
     * Process the order
     * @return Grid 
     */
    private function processOrder()
    {
	/* if (null === $this->getOrder())
	  //get default order
	  $order = new \Core\Grid\Order();
	  else
	  //get user defined order
	  $order = $this->getOrder();

	  $this->getSource()->setOrder($order); */
	return $this;
    }

    /**
     * Process the filter
     * @return Grid 
     */
    private function processColumnFilters()
    {
	/* $columns = $this->getColumns();
	  $this->getSource()->setFilter($filter); */
	return $this;
    }

    private function processSource()
    {
	$this->result = $this->getSource()->execute();
	$this->sourceProcessed = true;
    }

    public function getResult()
    {
	if (!$this->sourceProcessed)
	    throw new \Exception('You must build the grid before get the result. Use Grid::build()');

	return $this->result;
    }

    /**
     * Deploys
     *
     * @return Grid
     */
    final public function build()
    {
	if ($this->getSource() === null)
	    throw new \Exception('Please specify your source');

	$this->processRequest() //getting request object
		->countRecords()  //counting the record from source
		->processPager()  //
		->processOrder() //
		->processColumnFilters()
		->processSource();

	/* if (count($this->getSource()->getSelectOrder()) == 1 && !$this->getParam('order')) {
	  $norder = $this->getSource()->getSelectOrder();

	  if (!$norder instanceof Zend_Db_Expr) {
	  $this->setParam('order' . $this->getGridId(), $norder[0] . '_' . strtoupper($norder[1]));
	  }
	  }

	  $this->buildDefaultFiltersValues();

	  // Validate table fields, make sure they exist...
	  $this->_validateFields($this->_data['fields']);

	  // Filters. Not required that every field as filter.
	  $this->_filters = $this->_validateFilters($this->_filters);

	  $this->_buildFiltersValues();

	  if ($this->_isDetail == false) {
	  $this->_buildQueryOrderAndLimit();
	  }

	  if ($this->getParam('noOrder') == 1) {
	  $this->getSource()->resetOrder();
	  } */

	if (count($this->getColumns()) == 0)
	    throw new \Exception('No columns to show');

	return $this;
    }

    /**
     * String representation of grid
     *
     * Proxies to {@link render()}.
     *
     * @return string
     */
    public function __toString()
    {
	try {
	    return $this->render();
	} catch (\Exception $e) {
	    trigger_error($e->getMessage());
	    return '';
	}
    }

}