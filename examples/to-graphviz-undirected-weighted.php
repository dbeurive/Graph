<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\UndirectedWeighted;

$dotScripts = array();
$bName      = preg_replace('/\.php$/', '', basename(__FILE__));
$scriptPath = DIR_SCRIPTS . $bName . '.sh';

// Define the graph, as a list of neighbours.

$listOfNeighbours = array(
    'vertex1' => array('vertex2' => 1, 'vertex5' => 2),
    'vertex5' => array('vertex6' => 3, 'vertex7' => 4),
    'vertex2' => array('vertex3' => 5),
    'vertex3' => array('vertex4' => 6),
    'vertex4' => array('vertex2' => 7)
);

// Dump the graph.

$graph = new UndirectedWeighted();
$graph->setNeighbours($listOfNeighbours);
$dotPath = DIR_DOTS . $bName . '.dot';
$txt = $graph->dumpNeighboursToGraphviz();
file_put_contents($dotPath, $txt);

// Generate the command used to produce the image of the graph.

$imagePath = DIR_IMAGES . $bName . '.gif';
$dotScripts[] = dotCommand($dotPath, $imagePath);

// Generate the shell script that will render the graphs.

dumpDotScript($scriptPath, $dotScripts);