<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\UndirectedUnweighted;

$csvSuccessorsPath = DIR_INPUTS . 'undirected-unweighted.csv';

$graph = new UndirectedUnweighted();
$graph->loadNeighboursFromCsv($csvSuccessorsPath);

// Optionally, you can "complete" the lists of adjacent vertices.

$graph->completeNeighbours();

var_dump($graph->getNeighbours());