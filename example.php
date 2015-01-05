<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(array(
    'Phalcon' => __DIR__ . '../../Phalcon'
));

$loader->register();

use \Phalcon\Component;

$modelExpression = new Component\ModelExpression();
$modelExpression->category = [1,2];
$modelExpression->uid = 7;

var_dump($modelExpression->build());

$expr = new Component\ExpressionBuilder\Builder();

$expr->eq("A", "eq");
$expr->neq("B", 'neq');
$expr->lt("C", "lt");
$expr->lte("D", 'lte');
$expr->gt("E", 'gt');
$expr->gte("F", 'gte');
$expr->in("G", [1,2,3]);
$expr->like("H", 'like');
$expr->btw("I", [1,2]);

$or = new Component\ExpressionBuilder\Builder('OR');
$or->eq("field2", '2');
$or->eq('field3', '3');

$expr->add($or);

var_dump($expr->build());
//Phalcon\Mvc\Model::find($expr->build());