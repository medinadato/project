<?php
namespace Core\Grid\Column\Render;
use Core\Grid\Column\Render;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Text
 *
 * @author Administrator
 */
class Float extends Render\ARender implements Render\IRender
{
    /**
     *
     * @return string
     */
    public function render()
    {
	$row = $this->getRow();
	$index = $this->getColumn()->getIndex();
	return number_format($row[$index], 2);
    }

}

?>
