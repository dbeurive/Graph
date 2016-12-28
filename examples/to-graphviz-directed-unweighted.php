<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\DirectedUnweighted;

$dotScripts = array();
$bName      = preg_replace('/\.php$/', '', basename(__FILE__));
$scriptPath = DIR_SCRIPTS . $bName . '.sh';

// Define the graph, as a list of successors.

$listOfSuccessors = array(
    'vertex1' => array('vertex2' => null, 'vertex5' => null),
    'vertex5' => array('vertex6' => null, 'vertex7' => null),
    'vertex2' => array('vertex3' => null),
    'vertex3' => array('vertex4' => null),
    'vertex4' => array('vertex2' => null)
);

// Dump the graph following the successors.

$graph = new DirectedUnweighted();
$graph->setSuccessors($listOfSuccessors);
$dotPath = DIR_DOTS . $bName . '-successors' . '.dot';
$txt = $graph->dumpSuccessorsToGraphviz();
file_put_contents($dotPath, $txt);

// Generate the command used to produce the image of the graph.

$imagePath = DIR_IMAGES . $bName . '-successors' . '.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

// Dump the graph following the predecessors.

$graph->calculatePredecessorsFromSuccessors();
$dotPath = DIR_DOTS . $bName . '-predecessors' . '.dot';
$txt = $graph->dumpPredecessorsToGraphviz();
file_put_contents($dotPath, $txt);

// Generate the command used to produce the image of the graph.

$imagePath = DIR_IMAGES . $bName . '-predecessors' . '.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

// Generate the shell script that will render the graphs.

dumpDotScript($scriptPath, $dotScripts);
