<?php

namespace Phalcon\Component\ExpressionBuilder;

use Phalcon\Component\ExpressionBuilder\Exception\ErrorException;

/**
 * Builder class is responsible to dynamically create SQL query parts from Phalcon\Mvc\Model.
 *
 * Class Builder
 * @package Phalcon\ExpressionBuilder
 */
class Builder extends Expression {

    /**
     * Aliases to expression factory
     *
     * @var array
     */
    public static $expr_alias = [
        'eq'   => 'Equal',
        'neq'  => 'NotEqual',
        'lt'   => 'Less',
        'lte'  => 'LessOrEqual',
        'gt'   => 'Greater',
        'gte'  => 'GreaterOrEqual',
        'in'   => 'Contains',
        'like' => 'Like',
        'btw'  => 'Between',
    ];

    /**
     * Groups expressions
     *
     * @var array
     */
    protected $_expressions = [];

    /**
     * Conditions to build expressions
     *
     * @var array
     */
    protected $_conditions = [];

    /**
     * Bind parameters to build expressions
     *
     * @var array
     */
    protected $_bind = [];

    /**
     * Initializes a new Builder.
     *
     * @param string $operator Operator Builder
     */
    public function __construct($operator = 'AND') {
        $this->setOperator($operator);
    }

    public function setOperator($operator) {
        $operator = trim($operator);
        if($operator != 'AND' && $operator != 'OR') {
            throw new ErrorException('Operator must be "AND" or "OR"');
        }
        return parent::setOperator($operator);
    }

    /**
     * Create expression to Builder
     *
     * @param Expression $expr
     * @return Expression
     */
    public function add(Expression $expr) {
        $expr->setQuote($this->getQuote());
        if($this->getEscapeCallback() !== null) {
            $expr->setEscapeCallback($this->getEscapeCallback());
        }
        array_push($this->_expressions, $expr);
        return $expr;
    }

    /**
     * @see parent::getConditions();
     *
     * @return string
     */
    public function getConditions($binded = true) {
        $this->_conditions = array();
        foreach($this->_expressions as $expr) {
            $this->_conditions[] = $expr->getConditions($binded);
        }
        return "( " . implode($this->getOperator(), $this->_conditions) . " )";
    }

    /**
     * @see parent::getBind();
     *
     * @return string
     */
    public function getBind() {
        $this->_bind = array();
        foreach($this->_expressions as $expr) {
            $bind = $expr->getBind();
            $this->_bind = array_merge($bind, $this->_bind);
        }
        return $this->_bind;
    }

    /**
     * @see parent::remove();
     *
     * @param Expression $name
     */
    public function remove($name) {
        $searchIndex = array_search($name, $this->_expressions);
        if($searchIndex !== false) {
            unset($this->_expressions[$searchIndex]);

        }
    }

    /**
     * Magic aliases to expression factory
     * @see self::$expr_alias
     *
     * @param $method
     * @param $arguments
     * @return Expression
     * @throws ErrorException
     */
    public function __call($method, $arguments) {
        $method = strtolower($method);
        $condition = __NAMESPACE__ . '\Conditions\\' . ucfirst($method);
        if(isset(self::$expr_alias[$method])) {
            $condition = __NAMESPACE__ . '\Conditions\\' . self::$expr_alias[$method];
        }

        if(class_exists($condition) && count($arguments) == 2) {
            return $this->add(new $condition($arguments[0], $arguments[1]));
        } else {
            throw new ErrorException('Method "'.$method.'" is not exist and not aliases class conditions.');
        }
    }
}