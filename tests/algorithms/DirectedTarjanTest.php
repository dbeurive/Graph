<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\algorithms;

use dbeurive\Graph\DirectedTarjan;
use dbeurive\Graph\lists\DirectedUnweighted;

class DirectedTarjanTest extends \PHPUnit_Framework_TestCase
{
    static private $__outputPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'output';
    static private $__imagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'images';
    static private $__dots = array();

    static public function tearDownAfterClass() {

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedTarjanTest.sh';
        file_put_contents($path, implode(PHP_EOL, self::$__dots));
        chmod($path, 0755);
    }

    /**
     * Compare two sets of strongly connected components.
     * @param array $inComponents1 First set of strongly connected components.
     * @param array $inComponents2 Second set of strongly connected components.
     * @return bool If the sets are identical, then the method returns true.
     *         Otherwise, the method returns false.
     */
    private function __cmpComponents(array $inComponents1, array $inComponents2) {

        if (count($inComponents1) != count($inComponents2)) {
            return false;
        }

        foreach ($inComponents1 as &$_component) {
            sort($_component);
            $_component = md5(serialize($_component));
        }
        foreach ($inComponents2 as &$_component) {
            sort($_component);
            $_component = md5(serialize($_component));
        }
        sort($inComponents1);
        sort($inComponents2);

        for ($i=0; $i<count($inComponents1); $i++) {
            if ($inComponents1[$i] != $inComponents2[$i]) {
                return false;
            }
        }
        return true;
    }

    public function testRun() {

        $tests = array(

            /* 1 */
            array(
                'input' => array(
                    'vertex1' => array('vertex2' => null, 'vertex5' => null),
                    'vertex5' => array('vertex6' => null, 'vertex7' => null),
                    'vertex2' => array('vertex3' => null),
                    'vertex3' => array('vertex4' => null),
                    'vertex4' => array('vertex2' => null)
                ),
                'expected' => array(
                    array('vertex1'),
                    array('vertex2', 'vertex3', 'vertex4'),
                    array('vertex5'),
                    array('vertex6'),
                    array('vertex7'),

                ),
                'cycle' => array(
                    array('vertex2', 'vertex3', 'vertex4')
                )
            ),

            /* 2 */
            array(
                'input' => array(
                    'c0'  => array('node1' => null),
                    'node1'  => array('node4' => null, 'node6' => null, 'node7' => null),
                    'node2'  => array('node4' => null, 'node6' => null, 'node7' => null),
                    'node3'  => array('node4' => null, 'node6' => null, 'node7' => null),
                    'node4'  => array('node2' => null, 'node3' => null),
                    'node5'  => array('node2' => null, 'node3' => null),
                    'node6'  => array('node5' => null, 'node8' => null),
                    'node7'  => array('node5' => null, 'node8' => null),
                    'node8'  => array(),
                    'node9'  => array(),
                    'node10' => array('node10' => null) // This is a self-cycle (aka "loop").
                ),
                'expected' => array(
                    array('c0'),
                    array('node1'),
                    array('node8'),
                    array('node9'),
                    array('node10'),
                    array('node7', 'node3', 'node5', 'node6', 'node2', 'node4')
                ),
                'cycle' => array(
                    array('node7', 'node3', 'node5', 'node6', 'node2', 'node4'),
                    array('node10')
                )
            ),

            /* 3 */
            array(
                'input' => array(
                    'node1'   => array('node2' => null, 'node3' => null, 'g1' => null),
                    'node2'   => array('node4' => null, 'g1' => null),
                    'node3'   => array('node6' => null),
                    'node4'   => array('node5' => null, 'node6' => null),
                    'c100' => array('c200' => null, 'c300' => null, 'g100' => null),
                    'c200' => array('c400' => null, 'g100' => null),
                    'c300' => array('node6' => null),
                    'c400' => array('c500' => null, 'node6' => null),
                    'node5'   => array('g1' => null)
                ),
                'expected' => array(
                    array('node1'),
                    array('node2'),
                    array('node3'),
                    array('node4'),
                    array('node5'),
                    array('node6'),
                    array('g1'),
                    array('c100'),
                    array('c200'),
                    array('c300'),
                    array('c400'),
                    array('c500'),
                    array('g100')
                ),
                'cycle' => array()
            ),

            /* 4 */
            array(
                'input' => array(
                    'node1'   => array('node2' => null, 'node3' => null, 'g1' => null),
                    'node2'   => array('node4' => null, 'g1' => null),
                    'node3'   => array('node6' => null),
                    'node4'   => array('node5' => null, 'node6' => null),
                    'c100' => array('c200' => null, 'c300' => null, 'g100' => null),
                    'c200' => array('c400' => null, 'g100' => null),
                    'c300' => array('node6' => null),
                    'c400' => array('c500' => null, 'node6' => null),
                    'node5'   => array('g1' => null),
                    'c500' => array('c200' => null) // Create a loop
                ),
                'expected' => array(
                    array('node1'),
                    array('node2'),
                    array('node3'),
                    array('node4'),
                    array('node5'),
                    array('node6'),
                    array('g1'),
                    array('c100'),
                    array('c300'),
                    array('g100'),
                    array('c500', 'c400', 'c200')
                ),
                'cycle' => array(
                    array('c500', 'c400', 'c200')
                )
            ),

            /* 5 */
            array(
                'input' => array(
                    'node1'   => array('node2' => null, 'node3' => null, 'g1' => null),
                    'node2'   => array('node4' => null, 'g1' => null),
                    'node3'   => array('node6' => null),
                    'node4'   => array('node5' => null, 'node6' => null),
                    'c100' => array('c200' => null, 'c300' => null, 'g100' => null),
                    'c200' => array('c400' => null, 'g100' => null),
                    'c300' => array('node6' => null),
                    'c400' => array('c500' => null, 'node6' => null),
                    'node5'   => array('g1' => null, 'node1' => null),  // Create a loop
                    'c500' => array('c200' => null) // Create a loop
                ),
                'expected' => array(
                    array('node3'),
                    array('node6'),
                    array('g1'),
                    array('c100'),
                    array('c300'),
                    array('g100'),
                    array('node5', 'node4', 'node2', 'node1'),
                    array('c500', 'c400', 'c200')
                ),
                'cycle' => array(
                    array('node5', 'node4', 'node2', 'node1'),
                    array('c500', 'c400', 'c200')
                )
            ),

            /* 6 */
            array(
                'input' => array(
                    'node1'   => array('node2' => null, 'node3' => null, 'g1' => null),
                    'node2'   => array('node4' => null, 'g1' => null),
                    'node3'   => array('node6' => null),
                    'node4'   => array('node5' => null, 'node6' => null, 'c400' => null), // Add an edge
                    'c100' => array('c200' => null, 'c300' => null, 'g100' => null),
                    'c200' => array('c400' => null, 'g100' => null),
                    'c300' => array('node6' => null),
                    'c400' => array('c500' => null, 'node6' => null),
                    'node5'   => array('g1' => null, 'node1' => null),  // Create a loop
                    'c500' => array('c200' => null) // Create a loop
                ),
                'expected' => array(
                    array('node3'),
                    array('node6'),
                    array('g1'),
                    array('c100'),
                    array('c300'),
                    array('g100'),
                    array('c200', 'c500', 'c400'),
                    array('node5', 'node4', 'node2', 'node1')
                ),
                'cycle' => array(
                    array('c200', 'c500', 'c400'),
                    array('node5', 'node4', 'node2', 'node1')
                )
            ),

            /* 7 */
            array(
                'input' => array(
                    'node1'   => array('node2' => null, 'node3' => null, 'g1' => null),
                    'node2'   => array('node4' => null, 'g1' => null),
                    'node3'   => array('node6' => null),
                    'node4'   => array('node5' => null, 'node6' => null, 'c400' => null),
                    'c100' => array('c200' => null, 'c300' => null, 'g100' => null),
                    'c200' => array('c400' => null, 'g100' => null),
                    'c300' => array('node6' => null),
                    'c400' => array('c500' => null, 'node6' => null, 'node4' => null), // This creates a inner loop between two loops
                    'node5'   => array('g1' => null, 'node1' => null),  // Create a loop
                    'c500' => array('c200' => null) // Create a loop
                ),
                'expected' => array(
                    array('node3'),
                    array('node6'),
                    array('g1'),
                    array('c100'),
                    array('c300'),
                    array('g100'),
                    array('c200', 'c500', 'c400', 'node5', 'node4', 'node2', 'node1')
                ),
                'cycle' => array(
                    array('c200', 'c500', 'c400', 'node5', 'node4', 'node2', 'node1')
                )
            ),

            /* 8 */
            array(
                'input' => array(
                    'vertex1' => array('vertex2' => 1, 'vertex5' => 2),
                    'vertex5' => array('vertex6' => 3, 'vertex7' => 4),
                    'vertex2' => array('vertex3' => 5),
                    'vertex3' => array('vertex4' => 6),
                    'vertex4' => array('vertex2' => 7)
                ),
                'expected' => array(
                    array('vertex1'),
                    array('vertex2', 'vertex3', 'vertex4'),
                    array('vertex5'),
                    array('vertex6'),
                    array('vertex7'),

                ),
                'cycle' => array(
                    array('vertex2', 'vertex3', 'vertex4')
                )
            ),
        );

        /**
         * @var int $_index
         * @var array $_test
         */
        foreach ($tests as $_index => $_test) {
            $input = $_test['input'];
            $graph = new DirectedUnweighted();
            $graph->setSuccessors($input, true);

            // Dump the graphs
            $path = self::$__outputPath . DIRECTORY_SEPARATOR . "DirectedTarjanTest-${_index}-Input.dot";
            $image = self::$__imagePath . DIRECTORY_SEPARATOR . "DirectedTarjanTest-${_index}-Input";
            $dot = $graph->dumpSuccessorsToGraphviz();
            file_put_contents($path, $dot);
            self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

            $algo = new DirectedTarjan($graph);
            $algo->followSuccessors();

            $r = $algo->run();
            $expected = $_test['expected'];
            $this->assertTrue($this->__cmpComponents($expected, $r));

            $r = $algo->getCycles();
            $expected = $_test['cycle'];
            $this->assertTrue($this->__cmpComponents($expected, $r));

            // Dump the graphs
            $path = self::$__outputPath . DIRECTORY_SEPARATOR . "DirectedTarjanTest-${_index}-Output.dot";
            $image = self::$__imagePath . DIRECTORY_SEPARATOR . "DirectedTarjanTest-${_index}-Output";
            $dot = $algo->dumpToGraphviz();
            file_put_contents($path, $dot);
            self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";
        }
    }
}