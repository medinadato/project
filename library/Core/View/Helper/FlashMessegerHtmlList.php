<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @link    www.servfacil.com
 * @since   1.0
 * @version $Revision$
 * @author	Desenvolvimento
 */
class Core_View_Helper_FlashMessegerHtmlList extends \Zend_View_Helper_FormElement
{

    /**
     * Generates a 'List' element.
     *
     * @param array   $items   Array with the elements of the list
     * @param boolean $ordered Specifies ordered/unordered list; default unordered
     * @param array   $attribs Attributes for the ol/ul tag.
     * @return string The list XHTML.
     */
    public function flashMessegerHtmlList(array $items, $ordered = false, $attribs = false, $escape = true)
    {
        if (!is_array($items)) {
            $e = new \Zend_View_Exception('First param must be an array');
            $e->setView($this->view);
            throw $e;
        }

        $list = '';
		
        if ($attribs) {
            $attribs = $this->_htmlAttribs($attribs);
        } else {
            $attribs = '';
        }

        foreach ($items as $item) {
            if (!is_array($item)) {
                if ($escape) {
                    $item = $this->view->escape($item);
                }
                $list .= '<li ' . $attribs . '>' . $item . '<a href="#" class="fmBtnClose">X</a></li>' . self::EOL;
            } else {
                if (6 < strlen($list)) {
                    $list = substr($list, 0, strlen($list) - 6)
                     . $this->htmlList($item, $ordered, $attribs, $escape) . '</li>' . self::EOL;
                } else {
                    $list .= '<li ' . $attribs . '>' . $this->htmlList($item, $ordered, $attribs, $escape) . '<a href="#" class="fmBtnClose">X</a></li>' . self::EOL;
                }
            }
        }

        $tag = 'ul';
        if ($ordered) {
            $tag = 'ol';
        }

        return '<' . $tag . ' class="flashMessenger">' . self::EOL . $list . '</' . $tag . '>' . self::EOL;
    }
}
