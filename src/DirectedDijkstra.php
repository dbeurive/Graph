<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the Dijkstra's algorithm for directed graphs.
 */

namespace dbeurive\Graph;
use dbeurive\Graph\lists\DirectedWeighted;

/**
 * Class DirectedDijkstra
 *
 * This class implements the Dijkstra's algorithm for directed graphs.
 *
 * @see AbstractDijkstra
 * @package dbeurive\Graph
 */

class DirectedDijkstra extends AbstractDijkstra
{
    use TraitDirectedGraph;

    /**
     * DirectedDijkstra constructor.
     * @param DirectedWeighted $inDirectedWeightedGraph Graph used to initialise the algorithm.
     */
    public function __construct($inDirectedWeightedGraph) {
        // $this->__directedGraph is defined within the trait "TraitDirectedGraph".
        $this->__directedGraph = $inDirectedWeightedGraph;
    }
}