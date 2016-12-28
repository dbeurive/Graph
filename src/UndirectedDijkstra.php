<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the Dijkstra's algorithm for undirected graphs.
 */

namespace dbeurive\Graph;
use dbeurive\Graph\lists\UndirectedWeighted;

/**
 * Class UndirectedDijkstra
 *
 * This class implements the Dijkstra's algorithm for directed graphs.
 *
 * @see AbstractDijkstra
 * @package dbeurive\Graph
 */

class UndirectedDijkstra extends AbstractDijkstra
{
    use TraitUndirectedGraph;

    /**
     * UndirectedDijkstra constructor.
     * @param UndirectedWeighted $inUndirectedWeightedGraph Graph used to initialise the algorithm.
     */
    public function __construct($inUndirectedWeightedGraph) {
        $this->__undirectedGraph = $inUndirectedWeightedGraph;
    }
}