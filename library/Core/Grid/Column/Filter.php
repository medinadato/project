<?php
namespace Core\Grid\Column;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Filter
 *
 * @author Administrador
 */
class Filter
{
    
    protected $type = null;

    /**
     * Constructor of the class
     * @param array $options 
     */
    public function __construct(array $options = array())
    {
	return $this;
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
    
   protected function getTypeFromInput(Core_Form_Element $input)
   {
       
   }
   
   public function getInput()
   {
       return new \Zend_Form_Element_Text(array('name' => 'sdfsdfs'));
   }

}

?>
