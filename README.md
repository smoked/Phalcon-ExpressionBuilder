# Phalcon ExpressionBuilder

###Instalation
```sh
git clone https://github.com/smoked/Phalcon-ExpressionBuilder.git
```
###Required
PHP >= 5.3

###Examples

Implement a where in statement in a model
```php
<?php
$loader = new \Phalcon\Loader();

$loader->registerNamespaces(array(
    'Phalcon' => __DIR__ . '../../Phalcon' // path to component
));

$loader->register();

use \Phalcon\Component\ExpressionBuilder\Conditions\Contains;

//ModelName = Something model class in Phalcon
$result = ModelName::find(Contains::e('id', [1,3,5]));
/*
Equivalent
ModelName::find([
    'conditions' => 'id IN (:f6c300461:,:fb516476c:,:f020b8668:)',
    'bind' => [
        'fde7d827b' => 1,
        'f6c300461' => 3,
        'fb516476c' => 5,
    ]
]);
*/
```

Building expression

```php
<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(array(
    'Phalcon' => __DIR__ . '../../Phalcon'
));

$loader->register();

$modelExpression = new \Phalcon\Component\ModelExpression();
$modelExpression->category = [1,2,3];
$modelExpression->uid = 7;

var_dump($modelExpression->build());
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
Phalcon\Mvc\Model::find($expr->build())
*/

$expr = new Phalcon\ExpressionBuilder\Builder();

$expr->eq("A", "eq");
$expr->neq("B", 'neq');
$expr->lt("C", "lt");
$expr->lte("D", 'lte');
$expr->gt("E", 'gt');
$expr->gte("F", 'gte');
$expr->in("G", [1,2,3]);
$expr->like("H", 'like');
$expr->btw("I", [1,2]);

$orExpr = new \Phalcon\ExpressionBuilder\ExpressionBuilder('OR');
$orExpr->eq("field2", '2');
$orExpr->eq('field3', '3');

$expr->add($orExpr);

var_dump($expr->build());
//Phalcon\Mvc\Model::find($expr->build());
```