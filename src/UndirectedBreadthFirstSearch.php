<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the "Breadth First Search" algorithm, for undirected graphs.
 */

namespace dbeurive\Graph;
use dbeurive\Graph\lists\UndirectedUnweighted;
use dbeurive\Graph\lists\UndirectedWeighted;

/**
 * Class UndirectedBreadthFirstSearch
 *
 * This class implements the "Breadth First Search" algorithm, for undirected graphs.
 *
 * @package dbeurive\Graph
 */

class UndirectedBreadthFirstSearch extends AbstractBreadthFirstSearch
{
    use TraitUndirectedGraph;

    /**
     * UndirectedBreadthFirstSearch constructor.
     * @param UndirectedUnweighted|UndirectedWeighted $inUndirectedGraph Set of neighbours lists that represents the undirected graph.
     *        Please note that this set of lists must be "complete" (see explanation below).
     *
     *        This set represents an __undirected__ (and by the way, unweighted) graph.
     *        $lists = array(
     *              'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
     *              'e2' => array('a3' => null, 'e1' => null, 'e6' => null, 'e5' => null),
     *              'e3' => array('e1' => null),
     *              'e4' => array('a1' => null, 'e1' => null, 'e7' => null, 'e8' => null),
     *              'a3' => array('e2' => null),
     *              'a1' => array('e4' => null),
     *              'e5' => array('a4' => null, 'e2' => null),
     *              'a4' => array('e5' => null),
     *              'e6' => array('e2' => null),
     *              'e7' => array('e4' => null),
     *              'e8' => array('e4' => null, 'a2' => null),
     *              'a2' => array('e8' => null)
     *        );
     *        You can see that all vertices that appear in the lists have an entry in the array $lists.
     *
     * @see UndirectedWeighted::completeNeighbours()
     * @note This class can be used with weighted or unweighted graphs.
     */
    public function __construct($inUndirectedGraph) {
        // $this->__undirectedGraph is defined within the trait "TraitUndirectedGraph".
        $this->__undirectedGraph = $inUndirectedGraph;
    }
}