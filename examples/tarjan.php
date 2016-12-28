<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\DirectedUnweighted;
use dbeurive\Graph\DirectedTarjan;

$dotScripts = array();
$bName      = preg_replace('/\.php$/', '', basename(__FILE__));
$scriptPath = DIR_SCRIPTS . $bName . '.sh';

$successors = array(
    'vertex1' => array('vertex2' => null, 'vertex5' => null),
    'vertex5' => array('vertex6' => null, 'vertex7' => null),
    'vertex2' => array('vertex3' => null),
    'vertex3' => array('vertex4' => null),
    'vertex4' => array('vertex2' => null)
);

$graph = new DirectedUnweighted();
$graph->setSuccessors($successors, true);
$graph->calculatePredecessorsFromSuccessors();

// Generate the graphical representations of the graph.

$dotPath = DIR_DOTS . $bName . '-successors' . '.dot';
$txt = $graph->dumpSuccessorsToGraphviz();
file_put_contents($dotPath, $txt);
$imagePath = DIR_IMAGES . $bName . '-successors.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

$dotPath = DIR_DOTS . $bName . '-predecessors' . '.dot';
$txt = $graph->dumpPredecessorsToGraphviz();
file_put_contents($dotPath, $txt);
$imagePath = DIR_IMAGES . $bName . '-predecessors.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

// Run the algorithm through successors.

$algorithm = new DirectedTarjan($graph);
$algorithm->followSuccessors();
$scc = $algorithm->run();
$cycles = $algorithm->getCycles();

// Print the result.

print "List of strongly connected components:\n\n";

/**
 * @var int $_index
 * @var array $_scc
 */
foreach ($scc as $_index => $_scc) {
    print ' * ' . implode(',', $_scc) . PHP_EOL;
}

print "\n";

print "List of cycles:\n\n";

/**
 * @var int $_index
 * @var array $_scc
 */
foreach ($cycles as $_index => $_cycle) {
    print ' * ' . implode(',', $_cycle) . PHP_EOL;
}

// Print the result as a graph.

$dotPath = DIR_DOTS . $bName . '-successors-output.dot';
$txt = $algorithm->dumpToGraphviz();
file_put_contents($dotPath, $txt);
$imagePath = DIR_IMAGES . $bName . '-successors-output.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

dumpDotScript($scriptPath, $dotScripts);