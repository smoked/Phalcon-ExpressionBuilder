<?php

namespace Phalcon\Component\ExpressionBuilder\Tests;

use Phalcon\Component\ExpressionBuilder\Conditions;
use Phalcon\Component\ExpressionBuilder\Builder;

class ExpressionBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testSetQuote() {
        $expr = new Builder();
        $expr->setQuote('`');

        $this->assertEquals('`', $expr->getQuote());
        $equal = $expr->eq('A', 'B');
        $this->assertEquals('`', $equal->getQuote());
        $equal = $expr->add(Conditions\Equal::init('A', 'B'));
        $this->assertEquals('`', $equal->getQuote());
    }

    public function testExpressionQuoted() {
        $expr  = new Builder();

        $eq   = $expr->add(new Conditions\Equal('A', 'B'));
        $lt   = $expr->add(new Conditions\Less('A', 10));

        $this->assertEquals($expr->getConditions(false), "( {$eq->getConditions(false)} AND {$lt->getConditions(false)} )");
    }

    public function testEscapeString() {
        $expr  = new Builder();

        $expr->setEscapeCallback(function($val) { return $val . 'esc'; });

        $eq   = $expr->add(new Conditions\Equal('A', "'B"));
        $lt   = $expr->add(new Conditions\Less('A', 10));

        $this->assertEquals($expr->getConditions(false), "( {$eq->getConditions(false)} AND {$lt->getConditions(false)} )");
    }

    public function testBuildExpression() {
        $expr = new Builder();

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


        $expr1 = new Builder();

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
        $expr = new Builder();

        $class = 'Phalcon\Component\ExpressionBuilder\Conditions\\'. Builder::$expr_alias[$alias];
        $cond = $expr->$alias("a", $value);
        $this->assertTrue($cond instanceof $class);
    }

    public function getAliases() {
        $data = [];

        foreach(Builder::$expr_alias as $alias => $class) {
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
     * @expectedException \Phalcon\Component\ExpressionBuilder\Exception\ErrorException
     * @expectedExceptionMessage Method "foo" is not exist and not aliases class conditions.
     */
    public function testConditionAliasNotExist() {
        $expr = new Builder();
        $expr->foo(1,2);
    }

    /**
     *
     * @expectedException \Phalcon\Component\ExpressionBuilder\Exception\ErrorException
     * @expectedExceptionMessage Operator must be "AND" or "OR"
     */
    public function testSetWrongOperator() {
        $expr = new Builder('ANDDD');
    }
}