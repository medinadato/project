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
class Link extends Render\ARender implements Render\IRender
{

    /**
     *
     * @return string
     */
    public function render()
    {
	extract($this->options);
	$row = $this->getRow();
	$html = '<a href="' . $row[$href] . '">' . $row[$label] . '</a>';
	return $html;
    }

}

?>
