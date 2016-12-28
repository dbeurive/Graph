<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\UndirectedUnweighted;

$bName = preg_replace('/\.php$/', '', basename(__FILE__));

// Define the graph, as a list of successors.

$listOfNeighbours = array(
    'vertex1' => array('vertex2' => null, 'vertex5' => null),
    'vertex5' => array('vertex6' => null, 'vertex7' => null),
    'vertex2' => array('vertex3' => null),
    'vertex3' => array('vertex4' => null),
    'vertex4' => array('vertex2' => null)
);

$graph = new UndirectedUnweighted();
$graph->setNeighbours($listOfNeighbours);
$csvPath = DIR_CSVS . $bName . '.csv';
$graph->dumpNeighboursToCsv($csvPath);

