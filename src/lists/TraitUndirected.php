<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the required initialisation process, and the required methods for an "undirected set of adjacency lists".
 */

namespace dbeurive\Graph\lists;

/**
 * Class TraitUndirected
 *
 * This trait implements the required initialisation process, and the required methods for an "undirected set of adjacency lists".
 *
 * @package dbeurive\Graph\lists
 */

trait TraitUndirected
{
    /** @var array|null The list of neighbours. */
    private $__neighbours = null;

    /**
     * @see InterfaceLists
     */
    public function initDirection() {
        /** @var $this AbstractLists */
        $this->_setUndirected();
    }

    /**
     * @see InterfaceLists
     */
    public function isDirected() {
        return false;
    }

    // --------------------------------------------------

    /**
     * Get the lists of neighbours.
     * @return array|null The list of neighbours.
     */
    public function getNeighbours() {
        return $this->__neighbours;
    }

    /**
     * Load the lists of neighbours for a CSV file, identified by its given path.
     * @param string $inCsvPath Path to the CSV file.
     * @param bool $inOptComplete This option specifies whether the method should "complete" the graph or not.
     *        - If the given value is true, then the method will "complete" the graph.
     *        - If the given value is false, then the method will not "complete" the graph.
     * @return $this
     * @note The "completion" is not mandatory, since:
     *       - Some algorithms does not require "complete" graphs to execute.
     *       - The CSV file to load may already represent a "complete" graph.
     *       Completing a graph may be time consuming (depending on the size of the graph).
     */

    public function loadNeighboursFromCsv($inCsvPath, $inOptComplete=false) {
        $this->__neighbours = $this->_loadListsFromCsv($inCsvPath);
        if ($inOptComplete) {
            $this->_complete($this->__neighbours);
        }
        return $this;
    }

    /**
     * Dump the list of neighbours into a CSV file, identified by its given path.
     * @param string $inCsvPath Path to the CSV file.
     * @return $this
     */
    public function dumpNeighboursToCsv($inCsvPath) {
        return $this->_dumpListToCsv($inCsvPath, $this->__neighbours);
    }

    /**
     * Return the Graphviz representation of the graph.
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
     *                                        'penwidth' => 3)
     *                   )
     *        )
     *        See the documentation for Graphviz.
     * @param string $inName Name of the graph.
     * @return string The method returns the Graphviz representation of the graph.
     */
    public function dumpNeighboursToGraphviz($inOptNodesSpecifications=array(), array $inOptEdgesSpecifications=array(), $inName="MyGraph") {
        return $this->_toGraphviz($this->__neighbours, false, $inOptNodesSpecifications, $inOptEdgesSpecifications, $inName);
    }

    /**
     * Set the lists of neighbours.
     * @param array $inNeighbours The lists of neighbours.
     * @param bool $inOptComplete This option specifies whether the method should "complete" the graph or not.
     *        - If the given value is true, then the method will "complete" the graph.
     *        - If the given value is false, then the method will not "complete" the graph.
     * @note The "completion" is not mandatory, since:
     *       - Some algorithms does not require "complete" graphs to execute.
     *       - The CSV file to load may already represent a "complete" graph.
     *       Completing a graph may be time consuming (depending on the size of the graph).
     * @return $this
     */
    public function setNeighbours(array $inNeighbours, $inOptComplete=false) {
        $this->__neighbours = $inNeighbours;
        if ($inOptComplete) {
            $this->completeNeighbours();
        }

        return $this;
    }

    /**
     * "Complete" the list of neighbours.
     * @return $this
     */
    public function completeNeighbours() {
        $this->_complete($this->__neighbours);
        return $this;
    }
}