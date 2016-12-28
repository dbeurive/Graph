<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\algorithms;
use dbeurive\Graph\lists\UndirectedUnweighted;
use dbeurive\Graph\UndirectedDepthFirstSearch;

class UndirectedDepthFirstSearchTest extends \PHPUnit_Framework_TestCase
{
    static private $__outputPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'output';
    static private $__imagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'images';
    static private $__dots = array();

    static public function tearDownAfterClass() {

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'UndirectedDepthFirstSearchTest.sh';
        file_put_contents($path, implode(PHP_EOL, self::$__dots));
        chmod($path, 0755);
    }

    public function testRunUnweighted()
    {
        $neighbours = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('e5' => null, 'e6' => null),
            'e4' => array('e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'a4' => array('e5' => null),
            'a2' => array('e8' => null)
        );

        $vertices = array();
        $callback = function ($inVertex) use (&$vertices) {
            $vertices[] = $inVertex;
            return true;
        };

        $lists = new UndirectedUnweighted();
        $lists->setNeighbours($neighbours, true);

        // Dump the graphs
        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'UndirectedDepthFirstSearchTest-Unweighted-neighbours.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'UndirectedDepthFirstSearchTest-Unweighted-neighbours';
        $dot = $lists->dumpNeighboursToGraphviz();
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

        $runner = new UndirectedDepthFirstSearch($lists, $callback);

        // Run through neighbours.
        $vertices = array();
        $runner->run('e1', $callback);
        $expected = array('e1', 'e4', 'a1', 'e8', 'a2', 'e7', 'e3', 'e2', 'a3', 'e6', 'e5', 'a4');
        $this->assertEquals($expected, $vertices);

        $vertices = array();
        $runner->run('e2', $callback);
        $expected = array('e2', 'a3', 'e1', 'e4', 'a1', 'e8', 'a2', 'e7', 'e3', 'e6', 'e5', 'a4');
        $this->assertEquals($expected, $vertices);

        $vertices = array();
        $runner->run('a2', $callback);
        $expected = array('a2', 'e8', 'e4', 'a1', 'e1', 'e3', 'e2', 'a3', 'e6', 'e5', 'a4', 'e7');
        $this->assertEquals($expected, $vertices);
    }

    public function testRunWeighted()
    {
        $neighbours = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('e5' => 5, 'e6' => 6),
            'e4' => array('e7' => 7, 'e8' => 9),
            'a3' => array('e2' => 10),
            'a1' => array('e4' => 11),
            'a4' => array('e5' => 12),
            'a2' => array('e8' => 13)
        );

        $vertices = array();
        $callback = function ($inVertex) use (&$vertices) {
            $vertices[] = $inVertex;
            return true;
        };

        $lists = new UndirectedUnweighted();
        $lists->setNeighbours($neighbours, true);

        // Dump the graphs
        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'UndirectedDepthFirstSearchTest-Weighted-neighbours.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'UndirectedDepthFirstSearchTest-Weighted-neighbours';
        $dot = $lists->dumpNeighboursToGraphviz();
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";

        $runner = new UndirectedDepthFirstSearch($lists, $callback);

        // Run through neighbours.
        $vertices = array();
        $runner->run('e1', $callback);
        $expected = array('e1', 'e4', 'a1', 'e8', 'a2', 'e7', 'e3', 'e2', 'a3', 'e6', 'e5', 'a4');
        $this->assertEquals($expected, $vertices);

        $vertices = array();
        $runner->run('e2', $callback);
        $expected = array('e2', 'a3', 'e1', 'e4', 'a1', 'e8', 'a2', 'e7', 'e3', 'e6', 'e5', 'a4');
        $this->assertEquals($expected, $vertices);

        $vertices = array();
        $runner->run('a2', $callback);
        $expected = array('a2', 'e8', 'e4', 'a1', 'e1', 'e3', 'e2', 'a3', 'e6', 'e5', 'a4', 'e7');
        $this->assertEquals($expected, $vertices);
    }
}