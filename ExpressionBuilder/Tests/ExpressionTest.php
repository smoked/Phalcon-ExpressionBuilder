<?php


namespace Phalcon\ExpressionBuilder\Tests;


use Phalcon\ExpressionBuilder\Conditions;
use Phalcon\ExpressionBuilder\Expression;
use Phalcon\ExpressionBuilder\ExpressionBuilder;

class ExpressionTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @expectedException \Phalcon\ExpressionBuilder\Exception\ErrorException
     * @expectedExceptionMessage Cannot instantiate abstract class Phalcon\ExpressionBuilder\Expression
     */
    public function testAbstractInit() {
        Expression::init("a", "b");
    }


    public function testSetOperatorSpace() {
        $expr = new ExpressionBuilder();

        $expr->setOperator('AND');
        $this->assertEquals($expr->getOperator() , ' AND ');
        $this->assertEquals($expr->getOperator(true), 'AND');
    }

    public function testSettersAndGetters() {
        $cond = new Conditions\Equal('A', 'B');

        $this->assertEquals($cond->getField(), 'A');
        $this->assertEquals($cond->getValue(), 'B');

        $cond->setField("C");
        $cond->setValue("A");

        $this->assertEquals($cond->getField(), 'C');
        $this->assertEquals($cond->getValue(), 'A');
    }

    public function testCreateUniqueBindParam() {
        $range = range(1,20000);
        $expr  = new Conditions\Contains('A', $range);
        $build = $expr->build();
        $this->assertEquals(count($range), count($build['bind']));
    }

    public function testCreateBindParam() {
        $condition = new Conditions\Equal('A', 'B');
        $this->assertEquals($condition->createBindParam('B'), 'f' . hash('crc32','B'));
    }

    public function testGenerateBindParam() {
        $condition = new Conditions\Equal('A', 'B');
        $build     = $condition->build();

        $this->assertEquals($build['bind'][$condition->createBindParam('B')], 'B');
    }
}