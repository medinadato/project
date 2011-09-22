<?php
namespace Core\Grid\Source;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ASource
 *
 * @author Administrador
 */
abstract class ASource
{

    /**
     * grid used by source
     * @var Grid
     */
    protected $grid = null;
    /**
     * pager used by source
     * @var type 
     */
    protected $pager = null;
    /**
     * order config of the source
     * @var array
     */
    protected $order = array();

    /**
     * Sets the order config
     * @param array $order
     * @return ASource 
     */
    public function setOrder(array $order)
    {
	$this->order = $order;
	return $this;
    }

    /**
     * sets the limit
     * @param type $limit
     * @return ASource 
     */
    public function setLimit($limit)
    {
	$this->limit = (int) $limit;
	return $this;
    }

    /**
     * sets the offset
     * @param type $offset
     * @return ASource 
     */
    public function setOffset($offset)
    {
	$this->offset = (int) $offset;
	return $this;
    }

}

?>
