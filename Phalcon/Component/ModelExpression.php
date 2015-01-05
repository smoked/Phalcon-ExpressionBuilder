<?php

namespace Phalcon\Component;

use Phalcon\Mvc\Model as PhalconModel;

/**
 * Example usage class
 *
 * Class ModelExpression
 * @package Phalcon\Component
 */
class ModelExpression extends ExpressionBuilder\Builder
{
    protected $_fields;
    protected $_values;
    protected $_model = null;

    public function __construct($operator = 'AND', $model = null) {
        parent::__construct($operator);
        if($model !== null) {
            $this->setModel($model);
        }
    }

    public function setModel(PhalconModel $model) {
        $this->_model = $model;
        return $this;
    }

    public function getModel() {
        return $this->_model;
    }

    public function find() {
        if($this->getModel() === null) return;
        return $this->_model->find($this->build());
    }

    public function findFirst() {
        if($this->getModel() === null) return;
        return $this->_model->findFirst($this->build());
    }


    public function assign($array) {
        foreach($array as $name => $value) {
            $this->__set($name, $value);
        }
        return $this;
    }

    public function validate($name) {
        if($name === null) return;
        if($this->getModel() !== null && $this->getDI()->has('modelsMetadata') ) {
            $attributes = $this->getDI()->get('modelsMetadata')->getAttributes($this->getModel());

            if(!array_search($name, $attributes)) {
                throw new \InvalidArgumentException("Parameter {$name} must be in model {$this->getModel()->getSource()}");
            }
        }
    }

    public function add(ExpressionBuilder\Expression $exp) {
        $this->validate($exp->getField());
        return parent::add($exp);
    }

    public function getField($name) {
        return $this->_fields[$name];
    }

    public function __call($name, $arguments) {
        $this->_fields[$arguments[0]] = parent::__call($name, $arguments);
        $this->_values[$arguments[0]] = $arguments[1];
        return $this;
    }

    public function __get($name) {
        return $this->_values[$name];
    }

    public function __set($name, $value) {
        $this->validate($name);

        if(isset($this->_fields[$name])) {
            $this->remove($this->_fields[$name]);
        }

        if(is_array($value)) {
            if(count($value) > 1) {
                $this->_fields[$name] = $this->add(new ExpressionBuilder\Conditions\Contains($name, $value));
            } elseif(!empty($value)) {
                $this->_fields[$name] = $this->add(new ExpressionBuilder\Conditions\Equal($name, $value[0]));
            }
        } else {
            $this->_fields[$name] = $this->add(new ExpressionBuilder\Conditions\Equal($name, $value));
        }

        $this->_values[$name] = $value;
    }

    public function __unset($name) {
        $this->remove($this->_fields[$name]);
    }
}

?>