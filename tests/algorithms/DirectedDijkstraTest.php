<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\algorithms;
use dbeurive\Graph\DirectedDijkstra;
use dbeurive\Graph\lists\DirectedWeighted;

class DirectedDijkstraTest extends \PHPUnit_Framework_TestCase
{

    static private $__outputPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'output';
    static private $__imagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'images';
    static private $__dots = array();

    static public function tearDownAfterClass() {

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedDijkstraTest.sh';
        file_put_contents($path, implode(PHP_EOL, self::$__dots));
        chmod($path, 0755);
    }

    public function testDirected() {

        $successors = array(
            '1' => array('2' => 7,  '3' => 9, '6' => 14),
            '2' => array('3' => 10, '4' => 15),
            '3' => array('4' => 11, '6' => 2),
            '4' => array('5' => 6),
            '5' => array(),
            '6' => array('5' => 9)
        );

        $graph = new DirectedWeighted();
        $graph->setSuccessors($successors, true);

        // Dump the graphs
        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedDijkstraTest-Input.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'DirectedDijkstraTest-Input';
        $dot = $graph->dumpSuccessorsToGraphviz();
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

        $algorithm = new DirectedDijkstra($graph);
        $algorithm->followSuccessors();
        $distances = $algorithm->run('1');
        $expected = array(
            '1' => array(
                DirectedDijkstra::KEY_DISTANCE => 0,
                DirectedDijkstra::KEY_VERTEX => null
            ),
            '2' => array(
                DirectedDijkstra::KEY_DISTANCE => 7,
                DirectedDijkstra::KEY_VERTEX => 1
            ),
            '3' => array(
                DirectedDijkstra::KEY_DISTANCE => 9,
                DirectedDijkstra::KEY_VERTEX => 1
            ),
            '4' => array(
                DirectedDijkstra::KEY_DISTANCE => 20,
                DirectedDijkstra::KEY_VERTEX => 3,
            ),
            '5' => array(
                DirectedDijkstra::KEY_DISTANCE => 20,
                DirectedDijkstra::KEY_VERTEX => 6
            ),
            '6' => array(
                DirectedDijkstra::KEY_DISTANCE => 11,
                DirectedDijkstra::KEY_VERTEX => 3
            )
        );
        ksort($expected);
        ksort($distances);
        $this->assertEquals($expected, $distances);

        // Dump the graph that shows the result
        $path  = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedDijkstraTest-Result.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'DirectedDijkstraTest-Result';
        $dot = $algorithm->dumpToGraphviz();
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

        /**
         * @var string $_vertex
         * @var array $_data
         */
        $properties = array();
        foreach ($distances as $_vertex => $_data) {
            $label = $_vertex . ' (d:' . $_data[DirectedDijkstra::KEY_DISTANCE] . ' / p:' . $_data[DirectedDijkstra::KEY_VERTEX] . ')';
            $properties[$_vertex] = array('label' => $label);
        }
        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedDijkstraTest-Output.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'DirectedDijkstraTest-Output';
        $dot = $graph->dumpSuccessorsToGraphviz($properties);
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";
    }
}