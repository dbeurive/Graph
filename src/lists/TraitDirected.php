<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the required initialisation process, and the required methods for a "directed set of adjacency lists".
 */

namespace dbeurive\Graph\lists;

/**
 * Class TraitDirected
 *
 * This trait implements the required initialisation process, and the required methods for a "directed set of adjacency lists".
 *
 * @package dbeurive\Graph\lists
 */

trait TraitDirected
{
    /** @var array|null Lists of successors. */
    private $__successors = null;
    /** @var array|null Lists of predecessors. */
    private $__predecessors = null;

    /**
     * @see InterfaceLists
     */
    public function initDirection() {
        /** @var $this AbstractLists */
        $this->_setDirected();
    }

    /**
     * @see InterfaceLists
     */
    public function isDirected() {
        return true;
    }

    // --------------------------------------------------

    /**
     * Return the lists of successors.
     * @return array The list of successors.
     */
    public function getSuccessors() {
        return $this->__successors;
    }

    /**
     * Return the lists of predecessors.
     * @return array|null The lists of predecessors.
     */
    public function getPredecessors() {
        return $this->__predecessors;
    }

    /**
     * Load the lists of successors from a CSV file, identified by its path.
     * @param string $inCsvPath Path to the CSV file to load.
     * @param bool $inOptComplete This option specifies whether the method should "complete" the graph or not.
     *        - If the given value is true, then the method will "complete" the graph.
     *        - If the given value is false, then the method will not "complete" the graph.
     * @return $this
     * @note The "completion" is not mandatory, since:
     *       - Some algorithms does not require "complete" graphs to execute.
     *       - The CSV file to load may already represent a "complete" graph.
     *       Completing a graph may be time consuming (depending on the size of the graph).
     */
    public function loadSuccessorsFromCsv($inCsvPath, $inOptComplete=false) {
        $this->__successors = $this->_loadListsFromCsv($inCsvPath);
        if ($inOptComplete) {
            $this->completeSuccessors();
        }
        return $this;
    }

    /**
     * Load the lists of predecessors from a CSV file, identified by its path.
     * @param string $inCsvPath Path to the CSV file to load.
     * @param bool $inOptComplete This option specifies whether the method should "complete" the graph or not.
     *        - If the given value is true, then the method will "complete" the graph.
     *        - If the given value is false, then the method will not "complete" the graph.
     * @return $this
     * @note The "completion" is not mandatory, since:
     *       - Some algorithms does not require "complete" graphs to execute.
     *       - The CSV file to load may already represent a "complete" graph.
     *       Completing a graph may be time consuming (depending on the size of the graph).
     */
    public function loadPredecessorsFromCsv($inCsvPath, $inOptComplete=false) {
        $this->__predecessors = $this->_loadListsFromCsv($inCsvPath);
        if ($inOptComplete) {
            $this->completePredecessors();
        }
        return $this;
    }

    /**
     * Calculate the lists of predecessors from the lists of successors.
     * @param bool $inOptNeedComplete This option specifies whether the method should "complete" the graph or not.
     *        - If the given value is true, then the method will "complete" the graph.
     *        - If the given value is false, then the method will not "complete" the graph.
     * @return $this
     */
    public function calculatePredecessorsFromSuccessors($inOptNeedComplete=true) {
        $this->__predecessors = $this->_reverse($this->__successors, $inOptNeedComplete);
        return $this;
    }

    /**
     * Calculate the lists of successors from the lists of predecessors.
     * @param bool $inOptNeedComplete This option specifies whether the method should "complete" the graph or not.
     *        - If the given value is true, then the method will "complete" the graph.
     *        - If the given value is false, then the method will not "complete" the graph.
     * @return $this
     */
    public function calculateSuccessorsFromPredecessors($inOptNeedComplete=true) {
        $this->__successors = $this->_reverse($this->__predecessors, $inOptNeedComplete);
        return $this;
    }

    /**
     * Dump the lists of successors into a CSV file.
     * @param string $inCsvPath Pat the the CSV file.
     * @return $this
     */
    public function dumpSuccessorsToCsv($inCsvPath) {
        return $this->_dumpListToCsv($inCsvPath, $this->__successors);
    }

    /**
     * Dump the lists of predecessors into a CSV file.
     * @param string $inCsvPath Pat the the CSV file.
     * @return $this
     */
    public function dumpPredecessorsToCsv($inCsvPath) {
        return $this->_dumpListToCsv($inCsvPath, $this->__predecessors);
    }

    /**
     * Return the Graphviz representation of the graph, following the lists of successors.
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
    public function dumpSuccessorsToGraphviz($inOptNodesSpecifications=array(), array $inOptEdgesSpecifications=array(), $inName="MyGraph") {
        return $this->_toGraphviz($this->__successors, true, $inOptNodesSpecifications, $inOptEdgesSpecifications, $inName);
    }

    /**
     * Return the Graphviz representation of the graph, following the lists of predecessors.
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
    public function dumpPredecessorsToGraphviz($inOptNodesSpecifications=array(), array $inOptEdgesSpecifications=array(), $inName="MyGraph") {
        return $this->_toGraphviz($this->__predecessors, true, $inOptNodesSpecifications, $inOptEdgesSpecifications, $inName);
    }

    /**
     * Set the set of successors lists.
     * @param array $inSuccessors The set the successors lists.
     * @param bool $inOptComplete This flag specifies whether the method must "complete" the given set of lists or not.
     * @return $this
     */
    public function setSuccessors(array $inSuccessors, $inOptComplete=false) {
        $this->__successors = $inSuccessors;
        if ($inOptComplete) {
            $this->completeSuccessors();
        }
        return $this;
    }

    /**
     * Set the set of predecessors lists.
     * @param array $inPredecessors The set predecessors lists.*
     * @param bool $inOptComplete This option specifies whether the method should "complete" the graph or not.
     *        - If the given value is true, then the method will "complete" the graph.
     *        - If the given value is false, then the method will not "complete" the graph.
     * @note The "completion" is not mandatory, since:
     *       - Some algorithms does not require "complete" graphs to execute.
     *       - The CSV file to load may already represent a "complete" graph.
     * @return $this
     */
    public function setPredecessors(array $inPredecessors, $inOptComplete=false) {
        $this->__predecessors = $inPredecessors;
        if ($inOptComplete) {
            $this->completePredecessors();
        }
        return $this;
    }

    /**
     * "Complete" the set of successors lists.
     * @return $this
     */
    public function completeSuccessors() {
        $this->_complete($this->__successors);
        return $this;
    }

    /**
     * "Complete" the set of predecessors lists.
     * @return $this
     */
    public function completePredecessors() {
        $this->_complete($this->__predecessors);
        return $this;
    }

    /**
     * Test if both the predecessors and the successors are defined.
     * @return bool If both the predecessors and the successors are defined, then the method returns true.
     *         Otherwise, it returns false.
     */
    public function isFullyDefined() {
        if (is_null($this->__successors) || is_null($this->__predecessors)) {
            return false;
        }
        return true;
    }

    /**
     * Test if the successors are defined.
     * @return bool If the successors are defined, then the method returns true.
     *         Otherwise, it returns false.
     */
    public function isSuccessorsDefined() {
        return ! is_null($this->__successors);
    }

    /**
     * Test if the predecessors are defined.
     * @return bool If the predecessors are defined, then the method returns true.
     *         Otherwise, it returns false.
     */
    public function isPredecessorsDefined() {
        return ! is_null($this->__predecessors);
    }
}