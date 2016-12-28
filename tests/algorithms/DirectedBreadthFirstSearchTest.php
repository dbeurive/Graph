<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\algorithms;
use dbeurive\Graph\lists\DirectedUnweighted;
use dbeurive\Graph\DirectedBreadthFirstSearch;
use dbeurive\Graph\lists\DirectedWeighted;

class DirectedBreadthFirstSearchTest extends \PHPUnit_Framework_TestCase
{
    static private $__outputPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'output';
    static private $__imagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'images';
    static private $__dots = array();

    static public function tearDownAfterClass() {
        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest.sh';
        file_put_contents($path, implode(PHP_EOL, self::$__dots));
        chmod($path, 0755);
    }

    public function testRunUnweighted() {

        $successors = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('e5' => null, 'e6' => null),
            'e4' => array('e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'a4' => array('e5' => null),
            'a2' => array('e8' => null)
        );

        $vertices = array();
        $callback = function($inVertex) use(&$vertices) {
            $vertices[] = $inVertex;
            return true;
        };

        $lists = new DirectedUnweighted();
        $lists->setSuccessors($successors, true);
        $lists->calculatePredecessorsFromSuccessors();

        // Dump the graphs
        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest-Unweighted-successors.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest-Unweighted-successors';
        $dot = $lists->dumpSuccessorsToGraphviz();
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest-Unweighted-predecessors.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest-Unweighted-predecessors';
        $dot = $lists->dumpPredecessorsToGraphviz();
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

        $runner = new DirectedBreadthFirstSearch($lists, $callback);

        // Run through successors.
        $runner->followSuccessors();

        $vertices = array();
        $runner->run('e1', $callback);
        $expected = array('e1', 'e2', 'e3', 'e4', 'e5', 'e6', 'e7', 'e8');
        $this->assertEquals($expected, $vertices);
    }

    public function testRunWeighted() {

        $successors = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('e5' => 4, 'e6' => 5),
            'e4' => array('e7' => 6, 'e8' => 6),
            'a3' => array('e2' => 8),
            'a1' => array('e4' => 9),
            'a4' => array('e5' => 10),
            'a2' => array('e8' => 11)
        );

        $vertices = array();
        $callback = function($inVertex) use(&$vertices) {
            $vertices[] = $inVertex;
            return true;
        };

        $lists = new DirectedWeighted();
        $lists->setSuccessors($successors, true);
        $lists->calculatePredecessorsFromSuccessors();

        // Dump the graphs
        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest-Weighted-successors.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest-Weighted-successors';
        $dot = $lists->dumpSuccessorsToGraphviz();
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest-Weighted-predecessors.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'DirectedBreadthFirstSearchTest-Weighted-predecessors';
        $dot = $lists->dumpPredecessorsToGraphviz();
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

        $runner = new DirectedBreadthFirstSearch($lists, $callback);

        // Run through successors.
        $runner->followSuccessors();

        $vertices = array();
        $runner->run('e1', $callback);
        $expected = array('e1', 'e2', 'e3', 'e4', 'e5', 'e6', 'e7', 'e8');
        $this->assertEquals($expected, $vertices);
    }

}