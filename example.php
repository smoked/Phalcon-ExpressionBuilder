<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(array(
    'Phalcon' => __DIR__ . '../../Phalcon'
));

$loader->register();

$modelExpression = new \Phalcon\Component\ModelExpression();
$modelExpression->category = [1,2,3];
$modelExpression->uid = 7;
echo "<pre>";
var_export($modelExpression->build());

$expr = new Phalcon\ExpressionBuilder\ExpressionBuilder();

$expr->eq("A", "eq");
$expr->neq("B", 'neq');
$expr->lt("C", "lt");
$expr->lte("D", 'lte');
$expr->gt("E", 'gt');
$expr->gte("F", 'gte');
$expr->in("G", [1,2,3]);
$expr->like("H", 'like');
$expr->btw("I", [1,2]);

$or = new \Phalcon\ExpressionBuilder\ExpressionBuilder('OR');
$or->eq("field2", '2');
$or->eq('field3', '3');

$expr->add($or);

var_export($expr->build());
//Phalcon\Mvc\Model::find($expr->build());