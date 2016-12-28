<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * The file implements the Tarjan's strongly connected components algorithm.
 */

namespace dbeurive\Graph;

/**
 * Class AbstractTarjan
 *
 * The class implements the Tarjan's strongly connected components algorithm.
 *
 * @see https://en.wikipedia.org/wiki/Tarjan%27s_strongly_connected_components_algorithm
 *
 * Conditions:
 * - This algorithm works for directed graphs only.
 * - This algorithm works for both directed and undirected graphs.
 *
 * @package dbeurive\Graph
 */

abstract class AbstractTarjan extends AbstractAlgorithm
{
    const KEY_INDEX    = 'index';
    const KEY_LOW_LINK = 'lowLink';
    const KEY_ON_STACK = 'onStack';

    private $__lists = null;
    private $__stack = array();
    private $__index = 0;
    private $__stronglyConnectedComponents = array();
    private $__verticesProperties = array();

    /**
     * Initialise the algorithm.
     */
    private function __init() {
        $this->__lists = $this->_getAdjacentVertices();
        $this->__stack = array();
        $this->__index = 0;
        $this->__stronglyConnectedComponents = array();
        $this->__verticesProperties = array();

        foreach ($this->__lists as $_vertex => $_next) {
            $this->__verticesProperties[$_vertex] = array(
                self::KEY_INDEX    => null,
                self::KEY_LOW_LINK => null,
                self::KEY_ON_STACK => false
            );
        }
    }

    /**
     * Set the vertex's index.
     * @param string $inVertex Name of the vertex.
     * @param int $inValue Value of the index.
     */
    private function __setIndex($inVertex, $inValue) {
        $this->__verticesProperties[$inVertex][self::KEY_INDEX] = $inValue;
    }

    /**
     * Get the vertex's index.
     * @param string $inVertex Name of the vertex.
     * @return int The method returns the index of the vertex.
     */
    private function __getIndex($inVertex) {
        return $this->__verticesProperties[$inVertex][self::KEY_INDEX];
    }

    /**
     * Test whether the vertex has an index or not.
     * @param string $inVertex Name of the vertex.
     * @return bool If the vertex has an index, then the method returns true.
     *         Otherwise, it returns the value false.
     */
    private function __hasIndex($inVertex) {
        return ! is_null($this->__verticesProperties[$inVertex][self::KEY_INDEX]);
    }

    /**
     * Set the value of the low link.
     * @param string $inVertex Name of the vertex.
     * @param int $inValue Value to set.
     */
    private function __setLowLink($inVertex, $inValue) {
        $this->__verticesProperties[$inVertex][self::KEY_LOW_LINK] = $inValue;
    }

    /**
     * Get the value of the low link.
     * @param string $inVertex Name of the vertex.
     * @return int The method returns the value of the low link.
     */
    private function __getLowLink($inVertex) {
        return $this->__verticesProperties[$inVertex][self::KEY_LOW_LINK];
    }

    /**
     * Push a vertex on top od the stack.
     * @param string $inVertex Name of the vertex.
     */
    private function __pushOnStack($inVertex) {
        $this->__stack[] = $inVertex;
        $this->__verticesProperties[$inVertex][self::KEY_ON_STACK] = true;
    }

    /**
     * Test whether a vertex is within the stack or not.
     * @param string $inVertex Name of the vertex.
     * @return bool If the vertex is within the stack, then the method returns true.
     *         Otherwise, it returns the value false.
     */
    private function __isInStack($inVertex) {
        return $this->__verticesProperties[$inVertex][self::KEY_ON_STACK];
    }

    /**
     * Pop a vertex from the stack.
     * @return string|false The method returns the name of the vertex that is on the top of the stack.
     *         If the stack is empty, then the method returns the value false.
     */
    private function __popFromStack() {
        if (0 == count($this->__stack)) {
            return false;
        }
        $v = array_pop($this->__stack);
        $this->__verticesProperties[$v][self::KEY_ON_STACK] = false;
        return $v;
    }

    private function __strongConnect($inVertex) {
        $this->__setIndex($inVertex, $this->__index);
        $this->__setLowLink($inVertex, $this->__index);
        $this->__index += 1;
        $this->__pushOnStack($inVertex);

        foreach ($this->__lists[$inVertex] as $_vertex => $_weight) {
            if (! $this->__hasIndex($_vertex)) {
                $this->__strongConnect($_vertex);
                $this->__setLowLink($inVertex, min($this->__getLowLink($inVertex), $this->__getLowLink($_vertex)));
            } elseif ($this->__isInStack($_vertex)) {
                $this->__setLowLink($inVertex, min($this->__getLowLink($inVertex), $this->__getIndex($_vertex)));
            }
        }

        if ($this->__getLowLink($inVertex) == $this->__getIndex($inVertex)) {
            $component = array();
            do {
                    $w = $this->__popFromStack();
                    $component[] = $w;
            } while ($w != $inVertex);
            $this->__stronglyConnectedComponents[] = $component;
        }

        return $this->__stronglyConnectedComponents;
    }

    /**
     * Extract the cycles from the list of detected strongly connected components.
     * @return array The method returns an array that contains the detected cycles.
     *         Each element of the array is a set of vertices that form cycles.
     */
    private function __keepTrueCycles() {
        $cycles = array();
        /** @var array $_component */
        foreach ($this->__stronglyConnectedComponents as $_component) {
            if (count($_component) > 1) {
                $cycles[] = $_component;
                continue;
            }
            /**
             * @var string $_next
             * @var int|null $_weight
             */
            $c = $_component[0];
            foreach ($this->__lists[$c] as $_next => $_weight) {
                if ($c == $_next) {
                    $cycles[] = array($c);
                    break;
                }
            }
        }
        return $cycles;
    }

    /**
     * Search for strongly connected components or cycles.
     * @param bool $inOptKeepCycles Defines whether the method must keep the cycles or not.
     *        If the value of this parameter is true, then the method returns the cycles.
     *        Otherwise, the method returns the strongly connected components.
     * @return array The method returns the strongly connected components, or the cycles (depending on the value of the optional parameter).
     *         Regardless of the nature of the returned list, the returned value is an array of array.
     *         - If the method is configured to return strongly connected components, then each element of the returned array represents a list of vertices that are strongly connected.
     *         - If the method is configured to return cycles, then each element of the returned array represents a list of vertices that form cycles.
     */
    public function run($inOptKeepCycles=false) {
        $this->__init();
        foreach ($this->__lists as $_vertex => $_next) {
            if (! $this->__hasIndex($_vertex)) {
                $this->__strongConnect($_vertex);
            }
        }

        if ($inOptKeepCycles) {
            return $this->__keepTrueCycles();
        }

        return $this->__stronglyConnectedComponents;
    }

    /**
     * Return the set of strongly connected components or cycles.
     * @return array The method returns the strongly connected components.
     *         Each element of the returned array represents a list of vertices that are strongly connected.
     * @note However, the semantics between these two methods are different.
     *       - The method "run()" always executes the algorithm.
     *       - The method "getStronglyConnectedComponents()" executes the algorithm only if necessary.
     *         That is: if the algorithm has already been executed, then the method returns the last result.
     */
    public function getStronglyConnectedComponents() {
        if (is_null($this->__lists)) {
            $this->run();
        }

        return $this->__stronglyConnectedComponents;
    }

    /**
     * Return the cycles found within the graph.
     * @return array The method returns an array which elements are lists of vertices that form cycles.
     */
    public function getCycles() {
        $this->getStronglyConnectedComponents();
        return $this->__keepTrueCycles();
    }

    /**
     * Return the Graphviz representation of the graph used to run the algorithm.
     * This representations shows the cycles.
     * @param string $inName Name of the graph.
     * @return string The method returns a string that represents the Graphviz representation of the graph used to run the algorithm.
     */
    public function dumpToGraphviz($inName='myGraph') {

        $cycles = $this->getCycles();

        $nodesProperties = array();
        /** @var array $_cycle */
        foreach ($cycles as $_cycle) {
            /** @var string $_vertex */
            foreach ($_cycle as $_vertex) {
                $nodesProperties[$_vertex] = array('style' => 'filled', 'fillcolor' => '#BFAFB2');
            }
        }

        $edgesProperties = array();
        /** @var array $_cycle */
        foreach ($cycles as $_cycle) {
            /** @var string $_vertex */
            foreach ($_cycle as $_vertex) {
                /**
                 * @var string $_nextVertex
                 * @var int|null $_weight
                 */
                foreach ($this->__lists[$_vertex] as $_nextVertex => $_weight) {
                    if (in_array($_nextVertex, $_cycle)) {
                        $from = $_vertex;
                        $to = $_nextVertex;
                        $edgesProperties[$from][$to] = array(
                            'color' => '#FF0000',
                            'penwidth' => '2'
                        );
                    }
                }
            }
        }

        return $this->_dumpToGraphviz($nodesProperties, $edgesProperties, $inName);
    }
}