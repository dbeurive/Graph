<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\Graph;
use dbeurive\Graph\lists\DirectedUnweighted;
use dbeurive\Graph\lists\DirectedWeighted;

/**
 * Class DirectedBreadthFirstSearch
 *
 * This class implements the "Breadth First Search" algorithm, for directed graphs.
 *
 * @package dbeurive\Graph
 */
class DirectedBreadthFirstSearch extends AbstractBreadthFirstSearch
{
    use TraitDirectedGraph;

    /**
     * DirectedBreadthFirstSearch constructor.
     * @param DirectedUnweighted|DirectedWeighted $inDirectedGraph Set of lists that represents a directed graph.
     *        Please note that this set of lists must be "complete" (see explanation below).
     *
     *        This set represents a __directed__ (and by the way, unweighted) graph.
     *        $lists = array(
     *              'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
     *              'e2' => array('e5' => null, 'e6' => null),
     *              'e4' => array('e7' => null, 'e8' => null),
     *              'a3' => array('e2' => null),
     *              'a1' => array('e4' => null),
     *              'a4' => array('e5' => null),
     *              'a2' => array('e8' => null),
     *              'e6' => array(),
     *              'e7' => array(),
     *              'e3' => array(),
     *              'e5' => array(),
     *              'e8' => array()
     *        )
     *        You can see that all vertices that appear in the lists have a key in the array $lists.
     * @see DirectedWeighted::completeSuccessors()
     * @see DirectedWeighted::completePredecessors()
     * @see DirectedUnweighted::completeSuccessors()
     * @see DirectedUnweighted::completePredecessors()
     * @note This class can be used with weighted or unweighted graphs.
     * @throws Exception
     */
    public function __construct($inDirectedGraph) {
        // $this->__directedGraph is defined within the trait "TraitDirectedGraph".
        $this->__directedGraph = $inDirectedGraph;
    }
}