<?php
/**
 * LICENSE
 *
 * This source file is subject to the new BSD license
 * It is  available through the world-wide-web at this URL:
 * http://www.petala-azul.com/bsd.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to geral@petala-azul.com so we can send you a copy immediately.
 *
 * @package   Bvb_Grid
 * @author    Bento Vilas Boas <geral@petala-azul.com>
 * @copyright 2010 ZFDatagrid
 * @license   http://www.petala-azul.com/bsd.txt   New BSD License
 * @version   $Id$
 * @link      http://zfdatagrid.com
 */

namespace Core\Grid\Source;
use Core\Grid\Source;

class ArraySource extends Source\ASource implements Source\ISource
{

    protected $eventDispatcher;

    protected $fields;
    protected $rawResult;
    protected $offset = 0;
    protected $start;
    protected $numResults = 0;
    protected $sourceName;
    protected $cache;
    protected $primaryKey = null;

    public function __construct(array $array)
    {
        $this->rawResult = $array;
        $this->sourceName = 'array';
    }
    
    public function count()
    {
	$numResults = count($this->rawResult);
	$this->numResults = $numResults;
	return $numResults;
    }

    public function quoteValue($value)
    {
        return $value;
    }

    public function resetOrder()
    {
        return true;
    }

    public function resetLimit()
    {
        return true;
    }

    /**
     * Apply the search to a give field when the adaptar is an array
     */
    protected function applySearchType($final, $filtro, $op)
    {
        switch ($op) {
            case 'equal':
            case '=':
                if ($filtro == $final)
                    return true;
                break;
            case 'REGEXP':
                if (preg_match($filtro, $final))
                    return true;
                break;
            case 'rlike':
                if (substr($final, 0, strlen($filtro)) == $filtro)
                    return true;
                break;
            case 'llike':
                if (substr($final, - strlen($filtro)) == $filtro)
                    return true;
                break;
            case '>=':
                if ($final >= $filtro)
                    return true;
                break;
            case '>':
                if ($final > $filtro)
                    return true;
                break;
            case '<>':
            case '!=':
                if ($final != $filtro)
                    return true;
                break;
            case '<=':
                if ($final <= $filtro)
                    return true;
                break;
            case '<':
                if ($final < $filtro)
                    return true;
                break;
            default:
                $enc = stripos((string) $final, $filtro);
                if ($enc !== false) {
                    return true;
                }
                break;
        }

        return false;
    }

    /**
     * @see library/Bvb/Grid/Source/Bvb_Grid_Source_SourceInterface::getSelectOrder()
     */
    public function getSelectOrder()
    {
        return array();
    }

    public function getDescribeTable()
    {
        return false;
    }

    public function getFilterValuesBasedOnFieldDefinition($field)
    {
        return 'text';
    }

    public function getNumResults()
    {
        return $this->numResults;
    }

    public function execute()
    {
        return array_slice($this->rawResult, $this->offset, 25);
    }

    public function getMainTable()
    {
        return true;
    }

    public function getTableList()
    {
        return array();
    }

    public function buildQueryOrder($field, $order, $reset = false)
    {
        if (strtolower($order) == 'desc') {
            $sort = SORT_DESC;
        } else {
            $sort = SORT_ASC;
        }

        // Obtain a list of columns
        foreach ($this->rawResult as $key => $row) {
            $result[$key] = $row[$field];
        }

        array_multisort($result, $sort, $this->rawResult);

        unset($result);
    }

    public function getSqlExp(array $value, $where = array())
    {
        if (is_array($value['functions'])) {
            $i = 0;
            foreach ($value['functions'] as $final) {
                if ($i == 0) {
                    $valor = $this->applySqlExpToArray($value['value'], $final, null, $where);
                } else {
                    $valor = $this->applySqlExpToArray($value['value'], $final, $valor, $where);
                }

                $i++;
            }
        } else {
            $valor = $this->applySqlExpToArray($valor['value'], $value['functions'], null, $where);
        }

        return $valor;
    }

    /**
     * Applies the SQL EXP options to an array
     * @param $field
     * @param $operation
     * @param $option
     */
    protected function applySqlExpToArray($field, $operation, $value = null, $where = array())
    {
        $field = trim($field);
        $array = array();

        if (null === $value) {
            foreach ($this->rawResult as $value) {
                if ((count($where) > 0 && $value[key($where)] == reset($where)) || count($where) == 0)
                    $array[] = $value[$field];
            }
        } else {
            $array = array($value);
        }

        $operation = trim(strtolower($operation));

        switch ($operation) {
            case 'product':
                return array_product($array);
                break;
            case 'sum':
                return array_sum($array);
                break;
            case 'count':
                return count($array);
                break;
            case 'min':
                sort($array);
                return array_shift($array);
                break;
            case 'max':
                sort($array);
                return array_pop($array);
                break;
            case 'avg':
                return round(array_sum($array) / count($array));
                break;
            default:
                throw new Bvb_Grid_Exception('Operation not found');
                break;
        }
    }

    public function getDistinctValuesForFilters($field, $fieldValue, $order = 'name ASC')
    {
        $filter = array();
        foreach ($this->rawResult as $value) {
            $filter[$value[$field]] = $value[$fieldValue];
        }

        return array_unique($filter);
    }

    public function getFieldType($field)
    {

    }

    public function getSelectObject()
    {

    }

    public function addFullTextSearch($filter, $field)
    {

    }

    public function getRecord($table, array $condition)
    {

    }

    public function insert($table, array $post)
    {

    }

    public function update($table, array $post, array $condition)
    {

    }

    public function delete($table, array $condition)
    {

    }

    public function setCache($cache)
    {
        if (!is_array($cache)) {
            $cache = array('enable' => 0);
        }

        $this->cache = $cache;
    }

    public function buildForm($inputsType = array())
    {
        $form = array();

        foreach ($this->fields as $elements) {
            if ($elements == '_zfgId')
                continue;

            $label = ucwords(str_replace('_', ' ', $elements));
            $form['elements'][$elements] = array('text', array('size' => 10, 'label' => $label));
        }
        
        $event = new Bvb_Grid_Event('crud.elements_build', $this, array('elments'=>&$form));
        $this->eventDispatcher->emit($event);

        return $form;
    }

    public function getIdentifierColumns($table)
    {
        if (in_array('_zfgId', $this->fields)) {
            return array('_zfgId');
        }

        if (is_array($this->primaryKey)) {
            return $this->primaryKey;
        }
        return false;
    }

    public function getMassActionsIds ($table, $fields, $separator = '-')
    {
        if (!$pk = $this->getIdentifierColumns()) {
            throw new Bvb_Grid_Exception('No primary key found');
        }
    }

    public function setPrimaryKey(array $pk)
    {
        $this->primaryKey = $pk;
        return $this;
    }

    public function getValuesForFiltersFromTable($table, $field, $fieldValue, $order = 'name ASC')
    {

        throw new Exception('Not possible');
    }

    /**
     * Defines total records
     *
     * @param int $total Total records found
     */
    public function setNumResults($total)
    {
        $this->numResults = (int) $total;
    }

}