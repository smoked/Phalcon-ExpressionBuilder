<?php

$loader = new Phalcon\Loader();

$loader->registerNamespaces(array(
    'Phalcon' => __DIR__ . '/Phalcon',
));

use Phalcon\Component\ExpressionBuilder as E;
use Phalcon\Component\ExpressionBuilder\Conditions as C;

$loader->register();

$modelExpression = new Phalcon\Component\ModelExpression();
$modelExpression->category = [1,2,3];
$modelExpression->uid = 7;
$modelExpression->p = 100;
$pEqual = $modelExpression->add(C\Equal::init('p', 200));
echo "<pre>";
var_export($modelExpression->build());
/*
array (
  'conditions' => '( category IN (:f6c300461:,:fb516476c:,:f020b8668:) AND uid = :fde7d827b: AND p = :f4db61c8b: AND p = :fc4437589: )',
  'bind' =>
  array (
    'fc4437589' => 200,
    'f4db61c8b' => 100,
    'fde7d827b' => 7,
    'f6c300461' => 1,
    'fb516476c' => 2,
    'f020b8668' => 3,
  ),
)
//Usage
AnyModelPhalcon::find($modelExpression->build());
*/
$modelExpression->remove($pEqual);
unset($modelExpression->p);
var_export($modelExpression->build());
/*
array (
  'conditions' => '( category IN (:f6c300461:,:fb516476c:,:f020b8668:) AND uid = :fde7d827b: )',
  'bind' =>
  array (
    'fde7d827b' => 7,
    'f6c300461' => 1,
    'fb516476c' => 2,
    'f020b8668' => 3,
  ),
)
*/

$expr = new E\Builder();

$expr->add(C\Contains::init('fieldin', [1,2]));
$expr->eq("A", "eq"); // Alias $expr->add(C\Equal::init("A", "eq"));
$expr->neq("B", 'neq');
$expr->lt("C", "lt");
$expr->lte("D", 'lte');
$expr->gt("E", 'gt');
$expr->gte("F", 'gte');
$expr->in("G", [1,2,3]);
$expr->like("H", 'like');
$expr->btw("I", [1,2]);

$orExpr = new E\Builder('OR');
$orExpr->eq("field2", '2');
$orExpr->eq('field3', '3');

$expr->add($orExpr);

var_export($expr->build());
/*
array (
  'conditions' => '( fieldin IN (:f6c300461:,:fb516476c:) AND A = :fcd21e5ed: AND B != :fba061e1d: AND C < :f28c871a2: AND D <= :fd7a8f283: AND E > :fa9d4d25a: AND F >= :f66395e8f: AND G IN (:f6c300461:,:fb516476c:,:f020b8668:) AND H LIKE :fa7b21be6: AND I BETWEEN :f6c300461: AND :fb516476c: AND ( field2 = :fb516476c: OR field3 = :f020b8668: ) )',
  'bind' =>
  array (
    'f020b8668' => 3,
    'fb516476c' => 2,
    'f6c300461' => 1,
    'fa7b21be6' => 'like',
    'f66395e8f' => 'gte',
    'fa9d4d25a' => 'gt',
    'fd7a8f283' => 'lte',
    'f28c871a2' => 'lt',
    'fba061e1d' => 'neq',
    'fcd21e5ed' => 'eq',
  ),
)
*/