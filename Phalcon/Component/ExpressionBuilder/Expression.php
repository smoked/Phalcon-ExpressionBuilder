<?php

namespace Phalcon\Component\ExpressionBuilder;

use Phalcon\Component\ExpressionBuilder\Exception\ErrorException;
use Phalcon\Mvc\User\Component;

/**
 * Expression class is responsible to dynamically create SQL query parts to Phalcon\Mvc\Model.
 *
 * Class Expression
 * @package Phalcon\ExpressionBuilder
 */
abstract class Expression extends Component
{
    /**
     * Operator expression
     *
     * @string string
     */
    protected $_operator;

    /**
     * Field expression
     *
     * @string
     */
    protected $_field;

    /**
     * Value expression
     *
     * @mixed
     */
    protected $_value;

    /**
     * Quoted values
     *
     * @var
     */
    protected $_quote = "'";

    /**
     * Escape string callback function
     *
     * @var
     */
    protected $_escapeCallback;

    /**
     * Initializes a new Expression.
     *
     * @param $field        Field expression
     * @param null $value   Value expression
     */
    public function __construct($field, $value = null) {
        $this->setField($field);
        $this->setValue($value);
    }

    /**
     * Static initializes a new Expression
     *
     * @param $field    Filed expression
     * @param $value    Value expression
     * @return mixed    Expression
     * @throws ErrorException
     */
    public static function init($field, $value) {
        $class_instance = get_called_class();
        if($class_instance === __CLASS__) {
            throw new ErrorException('Cannot instantiate abstract class Expression');
        }

        return new $class_instance($field, $value);
    }

    /**
     * Return quote
     *
     * @return mixed
     */
    public function getQuote() {
        return $this->_quote;
    }

    /**
     * @param mixed $quote
     */
    public function setQuote($quote) {
        $this->_quote = $quote;
    }

    /**
     * Check the value on the quotes
     *
     * @param $value
     * @return bool
     */
    public function isNotQuoted($value) {
        return is_int($value) || is_float($value);
    }

    /**
     * Returns quotes for a specific value
     *
     * @return mixed
     */
    public function getQuoteValue($value = null) {
        if($value === null) {
            $value = $this->getValue();
        }
        return $this->isNotQuoted($value) ? '' : $this->getQuote();
    }

    /**
     * Exec escape callback
     *
     * @return mixed
     */
    public function escapeCallback($value) {
        $callback = $this->getEscapeCallback();
        return $callback instanceof \Closure ? $callback($value) : $value;
    }

    /**
     * Return escapeCallback
     *
     * @param mixed $escapeCallback
     */
    public function getEscapeCallback() {
        return $this->_escapeCallback;
    }

    /**
     * Set escape callback
     *
     * @example
     * self::setEscapeCallback( function ($val) { return addslashes($val); } );
     * self::setEscapeCallback( function ($val) { return pg_escape_string($val); } );
     * etc...
     *
     * @param mixed $escapeCallback
     */
    public function setEscapeCallback(\Closure $escapeCallback) {
        $this->_escapeCallback = $escapeCallback;
    }

    /**
     * Escape string value
     * Default none escape string
     *
     * @param $value
     * @return mixed
     */
    public function escape($value) {
        if($this->_escapeCallback === null) {
            return $value;
        }

        return $this->escapeCallback($value);
    }

    /**
     * Return field expression
     *
     * @return string
     */
    public function getField() {
        return $this->_field;
    }

    /**
     * Set field expression
     *
     * @param mixed $field
     * @return $this
     */
    public function setField($field) {
        $this->_field = $field;
        return $this;
    }

    /**
     * Return value expression
     *
     * @return mixed
     */
    public function getValue() {
        return $this->_value;
    }

    /**
     * Set value expression
     *
     * @param mixed $value
     * @return $this
     */
    public function setValue($value) {
        $this->_value = $value;
        return $this;
    }

    /**
     * Create new expression
     *
     * @param Expression $exp
     * @throws Exception
     */
    public function add(Expression $exp) {
        throw new Exception( get_class($this) .' is not builder expression');
    }

    /**
     * Return operator expression
     *
     * @param bool $trim    Remove spaces or not
     * @return string
     */
    public function getOperator($trim = false) {
        return $trim ? trim( $this->_operator ) : $this->_operator;
    }

    /**
     * Set operator expression
     *
     * @param string $operator
     * @return $this
     */
    public function setOperator($operator) {
        $this->_operator = " " . $operator . " ";
        return $this;
    }

    /**
     * Create bind value name parameter to Phalcon\Mvc\Model
     * @see Phalcon\Mvc\Model::find()
     *
     * @param $value        Value expression
     * @return null|string
     */
    public function createBindParam($value) {
        if($value === null) {
            return null;
        }

        return "f" . hash('crc32', $value);
    }

    /**
     * Return bind parameters to Phalcon\Mvc\Model
     * @see Phalcon\Mvc\Model::find()
     *
     * @return array|string|void
     */
    public function getBindParam() {
        if($this->getValue() === null) return;
        $obj = $this;

        return is_array($this->getValue()) ?
            array_map(function($a) use ($obj) {
                return ":" . $obj->createBindParam($a) . ":";
            }, $this->getValue()) :
            ":" . $this->createBindParam($this->getValue()) . ":";
    }

    /**
     * Return bind params in conditions expression
     *
     * @return array|string|void
     */
    public function getBindParamCondition() {
        return $this->getBindParam();
    }

    /**
     * Return params in conditions expression
     *
     * @return array|string|void
     */
    public function getParamCondition() {
        return $this->getParam();
    }

    /**
     * Return param
     *
     * @return array|string|void
     */
    public function getParam() {
        if($this->getValue() === null) return;
        $obj = $this;

        return is_array($this->getValue()) ?
            array_map(function($a) use ($obj) {
                return $obj->getQuoteValue($a) . $obj->escape( $a ) . $obj->getQuoteValue($a);
            }, $this->getValue())
            : $obj->getQuoteValue() . $obj->escape( $this->getValue() ) . $obj->getQuoteValue();
    }


    /**
     * Return conditions to expression
     *
     * @param boolean $binded
     * @return string
     */
    public function getConditions($binded = true) {
        return $this->getField() . $this->getOperator() . ( $binded ? $this->getBindParamCondition() : $this->getParamCondition() );
    }

    /**
     * Return bind parameters to expression
     *
     * @return array
     */
    public function getBind() {
        $bind = [];
        if(is_array($this->getValue())) {
            foreach($this->getValue() as $val) {
                $bind[$this->createBindParam($val)] = $val;
            }
        } else {
            $bind[$this->createBindParam($this->getValue())] = $this->getValue();
        }

        return array_filter($bind,function ($a) { return $a !== null ? true: false;});
    }

    /**
     * Build query parameters to Phalcon\Mvc\Model
     *
     * @return array
     */
    public function build() {
        return [
            'conditions' => $this->getConditions(),
            'bind'       => $this->getBind()
        ];
    }

    /**
     * Remove expression
     *
     * @param Expression $name
     */
    public function __unset($name) {
        $this->remove($name);
    }

    /**
     * Remove expression
     *
     * @param Expression $obj
     */
    public function remove($obj) {}
}