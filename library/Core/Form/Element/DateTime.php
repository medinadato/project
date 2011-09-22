<?php
class Core_Form_Element_DateTime extends \Core\Form\Element\Xhtml
{
    public $helper = "dateTime";
    protected $_date = null;
    protected $_time = null;
    
    public function __construct($spec, $options = null)
    {
        $this->addValidator('Date', false, array('format' => 'DD/MM/YYYY h:i'));
        parent::__construct($spec, $options);
    }

    public function setDate($date)
    {
        $this->_date = $date;
        return $this;
    }

    public function setTime($time)
    {
        $this->_time = $time;
        return $this;
    }

    public function setValue($value)
    {
        if (is_array($value)
                &&(isset($value['date']))
                &&(isset($value['time']))
                )
        {
            $this->setDate($value['date'])
                 ->setTime($value['time']);
        }
    }

    public function getValue()
    {
        if (! $this->_date || ! $this->_time)
                return false;
        return $this->_date .' '. $this->_time;
    }
}

