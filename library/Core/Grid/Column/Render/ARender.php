<?php
namespace Core\Grid\Column\Render;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ARender
 *
 * @author Administrator
 */
abstract class ARender implements IRender
{

    /**
     * row used by render
     * @var array
     */
    protected $row = array();
    /**
     * Column that uses the render
     * @var Core\Grid\Column
     */
    protected $column = null;
    /**
     * render options
     */
    protected $options;

    /**
     * constructor may set options
     * @param array $options 
     */
    public function __construct(array $options = array())
    {
	if (isset($options))
	    $this->options = $options;
    }

    /**
     * sets a row to be parsed by render
     * @param array $row 
     * @return ARender 
     */
    public function setRow(array $row)
    {
	$this->row = $row;
	return $this;
    }

    /**
     * returns the row
     * @return array
     */
    public function getRow()
    {
	return $this->row;
    }

    /**
     * returns column that uses the render
     */
    public function getColumn()
    {
	return $this->column;
    }

    /**
     * sets column that uses the render
     * @param Core\Grid\Column $column
     * @return ARender 
     */
    public function setColumn(\Core\Grid\Column $column)
    {
	$this->column = $column;
	return $this;
    }

}

?>
