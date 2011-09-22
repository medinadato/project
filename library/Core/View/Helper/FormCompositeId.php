<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormText.php 23775 2011-03-01 17:25:24Z ralph $
 */
/**
 * Abstract class for extension
 */

/**
 * Helper to generate a "text" element
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Core_View_Helper_FormCompositeId extends Zend_View_Helper_FormElement
{

    /**
     * Generates a 'text' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formCompositeId($name, $value = null, $attribs = null)
    {
	$xhtmlExtra = '';
	
	$helper = new \Zend_View_Helper_FormText;
	$helper->setView($this->view);
	
	if (strpos($value, '.')) {
	    $valores = explode('.', $value); 
	    $attribs['style'] = 'width: 20px';
	    $xhtmlExtra = '<span id="id-extra">&nbsp;'
			  . $helper->formText('identificacao[idFilho]', $valores[1], $attribs)
			  . '</span>';
	    
	    $attribs['readonly'] = 'readonly';
	    $value = $valores[0] . '.';
	}

	$xhtml = $helper->formText($name, $value, $attribs);
	
	return $xhtml . $xhtmlExtra;
    }

}