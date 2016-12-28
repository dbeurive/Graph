<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements code that is common to all algorithms that manipulate undirected graphs.
 */

namespace dbeurive\Graph;
use dbeurive\Graph\lists\UndirectedUnweighted;
use dbeurive\Graph\lists\UndirectedWeighted;

/**
 * Class TraitUndirectedGraph
 *
 * This trait implements code that is common to all algorithms that manipulate undirected graphs.
 *
 * @package dbeurive\Graph
 */

trait TraitUndirectedGraph
{
    /** @var null|UndirectedWeighted|UndirectedUnweighted The graph used to initialise the algorithm. */
    private $__undirectedGraph = null;

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
        return $this->__undirectedGraph->getNeighbours();
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
        return $this->__undirectedGraph->dumpNeighboursToGraphviz(
            $inOptNodesSpecifications,
            $inOptEdgesSpecifications,
            $inOptName
        );
    }
}