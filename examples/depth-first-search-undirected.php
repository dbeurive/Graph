<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\UndirectedUnweighted;
use dbeurive\Graph\UndirectedDepthFirstSearch;

$dotScripts = array();
$bName      = preg_replace('/\.php$/', '', basename(__FILE__));
$scriptPath = DIR_SCRIPTS . $bName . '.sh';

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
$callback = function($inVertex) use(&$vertices) {
    $vertices[] = $inVertex;
    return true; // Continue the traversal.
};

$graph = new UndirectedUnweighted();
$graph->setNeighbours($neighbours, true);

// Generate the graphical representations of the graph.

$dotPath = DIR_DOTS . $bName . '-neighbours.dot';
$txt = $graph->dumpNeighboursToGraphviz();
file_put_contents($dotPath, $txt);
$imagePath = DIR_IMAGES . $bName . '-neighbours.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

dumpDotScript($scriptPath, $dotScripts);

// Run the algorithm

$algorithm = new UndirectedDepthFirstSearch($graph, $callback);

// Run through successors.
$vertices = array();
$algorithm->run('e1', $callback);

print implode(',', $vertices) . PHP_EOL;