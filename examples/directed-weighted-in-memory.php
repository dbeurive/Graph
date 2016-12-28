<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\DirectedWeighted;

$dotScripts = array();
$bName      = preg_replace('/\.php$/', '', basename(__FILE__));
$scriptPath = DIR_SCRIPTS . $bName . '.sh';

// Define the graph, as a list of successors.

$listOfSuccessors = array(
    'vertex1' => array('vertex2' => 1, 'vertex5' => 2),
    'vertex5' => array('vertex6' => 3, 'vertex7' => 4),
    'vertex2' => array('vertex3' => 5),
    'vertex3' => array('vertex4' => 6),
    'vertex4' => array('vertex2' => 7)
);

$graph = new DirectedWeighted();

// Dump the graph, following the successors.
// $graphVizRepresentation is the GraphViz representation of the graph.
// See http://graphviz.org/

$graph->setSuccessors($listOfSuccessors);
$graphVizRepresentation = $graph->dumpSuccessorsToGraphviz();

// Generate the command used to produce the image of the graph.

$dotPath   = DIR_DOTS   . $bName . '-successors' . '.dot';
$imagePath = DIR_IMAGES . $bName . '-successors' . '.gif';
file_put_contents($dotPath, $graphVizRepresentation);
$dotScripts[] = dotCommand($dotPath, $imagePath);

// Dump the CSV representation of the graph.

$csvPath = DIR_CSVS . $bName . '-successors' . '.csv';
$graph->dumpSuccessorsToCsv($csvPath);

// Dump the graph, following the predecessors.

$graph->calculatePredecessorsFromSuccessors();
$graphVizRepresentation = $graph->dumpPredecessorsToGraphviz();

// Generate the command used to produce the image of the graph.

$dotPath   = DIR_DOTS   . $bName . '-predecessors' . '.dot';
$imagePath = DIR_IMAGES . $bName . '-predecessors' . '.gif';
file_put_contents($dotPath, $graphVizRepresentation);
$dotScripts[] = dotCommand($dotPath, $imagePath);

// Dump the CSV representation of the graph.

$csvPath = DIR_CSVS . $bName . '-predecessors' . '.csv';
$graph->dumpPredecessorsToCsv($csvPath);

// Generate the shell script that will render the graphs.

dumpDotScript($scriptPath, $dotScripts);


