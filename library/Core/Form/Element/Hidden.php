<?php
class Core_Form_Element_Hidden extends \Core\Form\Element\Xhtml
{
    /**
     * Use formHidden view helper by default
     * @var string
     */
    public $helper = 'formHidden';

    public function init()
	{
		$this->setDecorators(array('ViewHelper'));
	}
}