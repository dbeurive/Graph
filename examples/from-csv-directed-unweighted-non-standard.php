<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

use dbeurive\Graph\lists\DirectedUnweighted;

$csvSuccessorsPath = DIR_INPUTS . 'directed-unweighted-successors-non-standard.csv';
$csvPredecessorsPath = DIR_INPUTS . 'directed-unweighted-predecessors-non-standard.csv';

// -------------------------------------------------------------------------
// Load using the list of successors.
// - Set the field separator to ";".
// - Set a vertex unserializer.
// - Set a line preprocessor.
// -------------------------------------------------------------------------

$graph = new DirectedUnweighted();
$graph->setFieldSeparator(';');
$graph->setVertexUnserializer(function($inVertex) { return strtoupper($inVertex); });
$graph->setLinePreProcessor(function($inLine) { return trim($inLine); });

$graph->loadSuccessorsFromCsv($csvSuccessorsPath);

// Optionally, you can "complete" the graph.
$graph->completeSuccessors();

// Optionally, you can calculate the lists of predecessors from the lists of successors.
$graph->calculatePredecessorsFromSuccessors();

var_dump($graph->getSuccessors(), $graph->getPredecessors());

// -------------------------------------------------------------------------
// Load using the list of predecessors.
// - Set the field separator to ";".
// - Set a vertex unserializer.
// - Set a line preprocessor.
// -------------------------------------------------------------------------

$graph = new DirectedUnweighted();
$graph->setFieldSeparator(';');
$graph->setVertexUnserializer(function($inVertex) { return strtoupper($inVertex); });
$graph->setLinePreProcessor(function($inLine) { return trim($inLine); });

$graph->loadPredecessorsFromCsv($csvPredecessorsPath);

// Optionally, you can "complete" the graph.
$graph->completePredecessors();

// Optionally, you can calculate the lists of successors from the lists of predecessors.
$graph->calculateSuccessorsFromPredecessors();

var_dump($graph->getSuccessors(), $graph->getPredecessors());

// -------------------------------------------------------------------------
// Load using the lists of successors and the lists of predecessors.
// - Set the field separator to ";".
// - Set a vertex unserializer.
// - Set a line preprocessor.
// -------------------------------------------------------------------------

$graph = new DirectedUnweighted();
$graph->setFieldSeparator(';');
$graph->setVertexUnserializer(function($inVertex) { return strtoupper($inVertex); });
$graph->setLinePreProcessor(function($inLine) { return trim($inLine); });

$graph->loadSuccessorsFromCsv($csvSuccessorsPath, true);
$graph->loadPredecessorsFromCsv($csvPredecessorsPath, true);

var_dump($graph->getSuccessors(), $graph->getPredecessors());