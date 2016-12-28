<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements code that is common to all algorithms that manipulate directed graphs.
 */

namespace dbeurive\Graph;
use dbeurive\Graph\lists\DirectedUnweighted;
use dbeurive\Graph\lists\DirectedWeighted;

/**
 * Trait TraitDirectedGraph
 *
 * This trait implements code that is common to all algorithms that manipulate directed graphs.
 *
 * @package dbeurive\Graph
 */
trait TraitDirectedGraph
{
    /** @var int The direction to follow (successors or predecessors) */
    private $__direction = 0;
    /** @var DirectedUnweighted|DirectedWeighted The graph used to run the algorithm. */
    private $__directedGraph = null;

    // --------------------------------------------------------
    // Methods required by class "AbstractAlgorithm".
    // --------------------------------------------------------

    /**
     * Get the set of lists of "adjacent vertices".
     * - For undirected graphs, "adjacent vertices" are neighbours.
     * - For directed graphs, "adjacent vertices" are successors or predecessors,
     *   depending on the direction followed.
     * @return array The set of lists of "next vertices".
     * @note This method is defined within the abstract class AbstractAlgorithm.
     * @see AbstractAlgorithm
     */
    protected function _getAdjacentVertices() {
        if (0 == $this->__direction) {
            return $this->__directedGraph->getSuccessors();
        }
        return $this->__directedGraph->getPredecessors();
    }

    /**
     * Return a string that represents the Graphviz representation of the graph used to run the algorithm.
     * @param array $inOptNodesSpecifications Nodes' specifications.
     *        Example:
     *        array(
     *           'n1' => array('shape' => 'diamond',
     *                         'style' => 'filled',
     *                         'color' => 'lightgrey',
     *                         'label' => 'the label of the node'),
     *           'n2' => array('shape' => 'diamond',
     *                         'style' => 'filled',
     *                         'color' => 'red')
     *        )
     *        See the documentation for Graphviz.
     * @param array $inOptEdgesSpecifications Edges' specifications.
     *        Example:
     *        array(
     *           'n1' => array( 'n2' => array('color'    => '#9ACEEB',
     *                                        'penwidth' => 3)
     *                   ),
     *           'n2' => array( 'n3' => array('color'    => '#FF0000',
     *                                        'penwidth' => 3),
     *                          'n4' => array('color'    => '#FF0000',
     *                                        'penwidth' => 3)
     *                   )
     *        )
     *        See the documentation for Graphviz.
     * @param string $inOptName Name of the graph.
     *        See the documentation for Graphviz.
     * @return string The method returns the Graphviz representation of the graph.
     * @note This method is defined within the abstract class AbstractAlgorithm.
     * @see AbstractAlgorithm
     */
    protected function _dumpToGraphviz(array $inOptNodesSpecifications=array(), array $inOptEdgesSpecifications=array(), $inOptName='MyGraph') {

        $str = null;
        if (0 == $this->__direction) {
            $str = $this->__directedGraph->dumpSuccessorsToGraphviz(
                $inOptNodesSpecifications,
                $inOptEdgesSpecifications,
                $inOptName
            );
        } else {
            $str = $this->__directedGraph->dumpPredecessorsToGraphviz(
                $inOptNodesSpecifications,
                $inOptEdgesSpecifications,
                $inOptName
            );
        }

        return $str;
    }

    // --------------------------------------------------------
    // Other methods.
    // --------------------------------------------------------

    /**
     * Specify that we want to walk through the graph, following the list of successors.
     */
    public function followSuccessors() {
        $this->__direction = 0;
    }

    /**
     * Specify that we want to walk through the graph, following the list of predecessors.
     */
    public function followPredecessors() {
        $this->__direction = 1;
    }
}