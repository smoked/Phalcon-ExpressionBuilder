<?php

namespace Phalcon\ExpressionBuilder\Tests;

use Phalcon\ExpressionBuilder\Conditions;
use Phalcon\ExpressionBuilder\ExpressionBuilder;

class ExpressionBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildExpression() {
        $expr = new ExpressionBuilder();

        $eq   = $expr->add(new Conditions\Equal('A', 'B'));
        $lt   = $expr->add(new Conditions\Less('A', 10));

        $this->assertEquals(count($expr->getBind()), 2);
        $this->assertEquals($expr->getConditions(), "( {$eq->getConditions()} AND {$lt->getConditions()} )");

        $expr->setOperator('OR');

        $this->assertEquals($expr->getConditions(), "( {$eq->getConditions()} OR {$lt->getConditions()} )");

        $gte   = $expr->add(new Conditions\GreaterOrEqual('A', 10));

        $this->assertEquals(count($expr->getBind()), 2);

        $this->assertEquals($expr->getConditions(), "( {$eq->getConditions()} OR {$lt->getConditions()} OR {$gte->getConditions()} )");

        $expr->remove($eq);

        $this->assertEquals(count($expr->getBind()), 1);
        $this->assertEquals($expr->getConditions(), "( {$lt->getConditions()} OR {$gte->getConditions()} )");

        $in = $expr->add( Conditions\Contains::init('A', [1,2,3]) );
        $this->assertEquals(count($expr->getBind()), 4);


        $expr1 = new ExpressionBuilder();

        $eqb = $expr1->eq("B", 'eq');
        $ltb = $expr1->lt("B", 'lt');

        $expr->add($expr1);

        $this->assertEquals($expr->getConditions(), "( {$lt->getConditions()} OR {$gte->getConditions()} OR {$in->getConditions()} OR {$expr1->getConditions()} )");
        $this->assertEquals(count($expr->getBind()), 6);

        $expr1->remove($eqb);

        $this->assertEquals($expr->getConditions(), "( {$lt->getConditions()} OR {$gte->getConditions()} OR {$in->getConditions()} OR {$expr1->getConditions()} )");
        $this->assertEquals(count($expr->getBind()), 5);
    }

    /**
     *
     * @dataProvider getAliases
     */
    public function testConditionAlias($alias, $value) {
        $expr = new ExpressionBuilder();

        $class = 'Phalcon\ExpressionBuilder\Conditions\\'. ExpressionBuilder::$expr_alias[$alias];
        $cond = $expr->$alias("a", $value);
        $this->assertTrue($cond instanceof $class);
    }

    public function getAliases() {
        $data = [];

        foreach(ExpressionBuilder::$expr_alias as $alias => $class) {
            switch($alias) {
                case 'contains':
                case 'btw':
                    $value = [1,2];
                    break;
                default:
                    $value = 1;
                    break;
            }
            $data[] = [$alias, $value];
        }

        return $data;
    }


    /**
     *
     * @expectedException \Phalcon\ExpressionBuilder\Exception\ErrorException
     * @expectedExceptionMessage Method "foo" is not exist and not aliases class conditions.
     */
    public function testConditionAliasNotExist() {
        $expr = new ExpressionBuilder();
        $expr->foo(1,2);
    }

    /**
     *
     * @expectedException \Phalcon\ExpressionBuilder\Exception\ErrorException
     * @expectedExceptionMessage Operator must be "AND" or "OR"
     */
    public function testSetWrongOperator() {
        $expr = new ExpressionBuilder('ANDDD');
    }
}