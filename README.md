# Phalcon ExpressionBuilder

###Installation
```sh
git clone https://github.com/smoked/Phalcon-ExpressionBuilder.git
```

```php
$loader = new Phalcon\Loader();

$loader->registerNamespaces(array(
    /* other namespaces */
    'Phalcon' => __DIR__ . '/Phalcon' // path to component
));
```

###Required
PHP >= 5.3

### Conditions class

Between:
```php
Between::init('field', [1,2]); # field BETWEEN 1 AND 2
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->btw('field', [1,2]);
// Or
$ExpressionBuilder->add(Between::init('field', [1,2]));
```
Contains:
```php
Contains::init('field', [1,2]); # field IN (1,2)
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->in('field', [1,2]);
// Or
$ExpressionBuilder->add(Contains::init('field', [1,2]));
```

Equal:
```php
Equal::init('field', 1); # field = 1
Equal::init('field', null); # field IS NULL
Equal::init('field'); # field IS NULL
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->eq('field', 1);
// Or
$ExpressionBuilder->add(Equal::init('field', 1));
```

NotEqual:
```php
NotEqual::init('field', 1); # field != 1
NotEqual::init('field', null); # field IS NOT NULL
NotEqual::init('field'); # field IS NOT NULL
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->neq('field', 1);
// Or
$ExpressionBuilder->add(NotEqual::init('field', 1));
```

Less:
```php
Less::init('field', 1); // field < 1
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->lt('field', 1);
// Or
$ExpressionBuilder->add(Less::init('field', 1))
```

LessOrEqual:
```php
LessOrEqual::init('field', 1); // field <= 1
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->lte('field', 1);
// Or
$ExpressionBuilder->add(LessOrEqual::init('field', 1));
```

Greater:
```php
Greater::init('field', 1); // field > 1
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->gt('field', 1);
// Or
$ExpressionBuilder->add(Greater::init('field', 1));
```

GreaterOrEqual:
```php
GreaterOrEqual::init('field', 1); // field >= 1
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->gte('field', 1);
// Or
$ExpressionBuilder->add(GreaterOrEqual::init('field', 1));
```

Like:
```php
Like::init('field', 1); // field LIKE 1
// Usage from Phalcon\Component\ExpressionBuilder\Builder
// Alias
$ExpressionBuilder->like('field', 1);
// Or
$ExpressionBuilder->add(Like::init('field', 1));
```

###Examples

Implement a where **IN** statement in a model
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

$IN = Contains::init('names', ["Alex", "Piter", "Mc'laren"]);
echo $IN->getConditions(false) . PHP_EOL;
// names IN ('Alex','Piter','Mac'laren')
$IN->setEscapeCallback(function($val) { return addslashes($val); });
echo $IN->getConditions(false) . PHP_EOL;
// names IN ('Alex','Piter','Mac\'laren')
$IN->setQuote('`');
echo $IN->getConditions(false) . PHP_EOL;
// names IN (`Alex`,`Piter`,`Mc\'laren`)
```

Expression building:

```php
<?php
$loader = new Phalcon\Loader();

$loader->registerNamespaces(array(
    'Phalcon' => __DIR__ . '/Phalcon', // path to component
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
```
