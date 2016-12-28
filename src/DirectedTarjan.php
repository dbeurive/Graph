<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\Graph;
use dbeurive\Graph\lists\DirectedUnweighted;
use dbeurive\Graph\lists\DirectedWeighted;

class DirectedTarjan extends AbstractTarjan
{
    use TraitDirectedGraph;

    /**
     * DirectedTarjan constructor.
     * @param DirectedWeighted|DirectedUnweighted $inDirectedGraph Graph used to initialise the algorithm.
     */
    public function __construct($inDirectedGraph) {
        // $this->__directedGraph is defined within the trait "TraitDirectedGraph".
        $this->__directedGraph = $inDirectedGraph;
    }
}