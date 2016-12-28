<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file defines the common interface for all classes that implement algorithms.
 *
 * Please note that this interface is implemented within traits:
 * - TraitDirectedGraph
 * - TraitUndirectedGraph
 *
 * @see TraitDirectedGraph
 * @see TraitUndirectedGraph
 */

namespace dbeurive\Graph;

/**
 * Class AbstractAlgorithm
 *
 * This method defines the common interface for all classes that implement algorithms.
 *
 * Please note that this interface is implemented within traits:
 * - TraitDirectedGraph
 * - TraitUndirectedGraph
 *
 * @package dbeurive\Graph
 *
 * @see TraitDirectedGraph
 * @see TraitUndirectedGraph
 */
abstract class AbstractAlgorithm
{
    /**
     * Get the set of lists of "adjacent vertices".
     * - For undirected graphs, "adjacent vertices" are neighbours.
     * - For directed graphs, "adjacent vertices" are successors or predecessors,
     *   depending on the direction followed.
     * @return array The set of lists of "next vertices".
     * @note This method is implemented within:
     *       * TraitDirectedGraph
     *       * TraitUndirectedGraph
     * @see TraitDirectedGraph
     * @see TraitUndirectedGraph
     */
    abstract protected function _getAdjacentVertices();

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
     * @note This method is implemented within:
     *       * TraitDirectedGraph
     *       * TraitUndirectedGraph
     * @see TraitDirectedGraph
     * @see TraitUndirectedGraph
     */
    abstract protected function _dumpToGraphviz(array $inOptNodesSpecifications=array(), array $inOptEdgesSpecifications=array(), $inOptName='MyGraph');
}