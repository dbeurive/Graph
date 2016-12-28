<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\UndirectedWeighted;
use dbeurive\Graph\UndirectedDijkstra;

$dotScripts = array();
$bName      = preg_replace('/\.php$/', '', basename(__FILE__));
$scriptPath = DIR_SCRIPTS . $bName . '.sh';

$neighbours = array(
    '1' => array('2' => 7,  '3' => 9, '6' => 14),
    '2' => array('3' => 10, '4' => 15),
    '3' => array('4' => 11, '6' => 2),
    '4' => array('5' => 6),
    '5' => array(),
    '6' => array('5' => 9)
);

$graph = new UndirectedWeighted();
$graph->setNeighbours($neighbours, true);

// Generate the graphical representations of the graph.

$dotPath = DIR_DOTS . $bName . '-neighbours.dot';
$txt = $graph->dumpNeighboursToGraphviz();
file_put_contents($dotPath, $txt);
$imagePath = DIR_IMAGES . $bName . '-neighbours.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

// Run the algorithm through successors.

$algorithm = new UndirectedDijkstra($graph);
$distances = $algorithm->run('1');

// Print the result.

/**
 * @var string $_vertex
 * @var array $_data
 */
foreach ($distances as $_vertex => $_data) {
    print "--- Vertex $_vertex ---\n\n";
    print "    The shortest distance between 1 and $_vertex is: " . $_data[UndirectedDijkstra::KEY_DISTANCE] . "\n";
    $previous = $_data[UndirectedDijkstra::KEY_VERTEX];
    if (! is_null($previous)) {
        print "    The previous vertex along the shortest path between 1 and $_vertex is: $previous\n";
    }
    print "\n";
}

// Print the result as a graph.

$dotPath = DIR_DOTS . $bName . '-neighbours-output.dot';
$txt = $algorithm->dumpToGraphviz();
file_put_contents($dotPath, $txt);
$imagePath = DIR_IMAGES . $bName . '-neighbours-output.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

dumpDotScript($scriptPath, $dotScripts);