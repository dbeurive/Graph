<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the edge bookkeeper.
 * This class is used when we need to generate the Graphviz representation of an undirected graph.
 */

namespace dbeurive\Graph\lists;

/**
 * Class EdgeBookkeeper
 *
 * This class implements the edge bookkeeper.
 * This class is used when we need to generate the Graphviz representation of an undirected graph.
 *
 * Example of undirected graph:
 *    array(
 *            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
 *            'e2' => array('e1' => null, 'e5' => null),
 *            'e3' => array('e1' => null),
 *            'e4' => array('e1' => null),
 *            'e5' => array('e2' => null)
 *         )
 * As you can see, edges appear twice.
 *
 * For example: 'e1' => 'e2' and 'e2' = 'e1'.
 *              Since the graph is undirected, <'e1' => 'e2'> and <'e2' = 'e1'> represent the same edge.
 *
 * Although all edges within an undirected graph appear twice, they must be rendered only once.
 *
 * @package dbeurive\Graph\lists
 */

class EdgeBookkeeper
{
    /** @var array|null  */
    private $__lists = null;
    private $__bookkeeper = array();
    private $__specifications = array();

    public function __construct(array $inLists, array $inSpecifications=null)
    {
        $this->__lists = $inLists;

        if (! is_null($inSpecifications)) {

            /**
             * @var string $_vertex
             * @var array $_nextVertices
             */
            foreach ($inSpecifications as $_vertex => $_nextVertices) {
                /**
                 * @var string $__vertex
                 * @var array $_properties
                 */
                foreach ($_nextVertices as $__vertex => $_properties) {

                    if (! array_key_exists($_vertex, $this->__specifications)) {
                        $this->__specifications[$_vertex] = array();
                    }
                    if (! array_key_exists($__vertex, $this->__specifications[$_vertex])) {
                        $this->__specifications[$_vertex][$__vertex] = $_properties;
                    }

                    if (! array_key_exists($__vertex, $this->__specifications)) {
                        $this->__specifications[$__vertex] = array();
                    }
                    if (! array_key_exists($__vertex, $this->__specifications[$_vertex])) {
                        $this->__specifications[$__vertex][$_vertex] = $_properties;
                    } else {
                        $this->__specifications[$__vertex][$_vertex] = $this->__specifications[$_vertex][$__vertex];
                    }
                }
            }
        }
    }

    public function declareEdge($inFrom, $inTo) {

        if (array_key_exists($inFrom, $this->__lists)
            &&
            array_key_exists($inTo, $this->__lists)) {

            if (array_key_exists($inFrom, $this->__lists[$inTo])
                &&
                array_key_exists($inTo, $this->__lists[$inFrom])) {
                $w1 = $this->__lists[$inTo][$inFrom];
                $w2 = $this->__lists[$inFrom][$inTo];
                if ($w1 === $w2) {
                    $this->__bookkeeper[$inFrom][$inTo] = null;
                    $this->__bookkeeper[$inTo][$inFrom] = null;
                    return;
                }
            }
        }

        if (! array_key_exists($inFrom, $this->__bookkeeper)) {
            $this->__bookkeeper[$inFrom] = array();
        }

        $this->__bookkeeper[$inFrom][$inTo] = null;
    }

    public function isDeclared($inFrom, $inTo) {
        if (array_key_exists($inFrom, $this->__bookkeeper)) {
            if (array_key_exists($inTo, $this->__bookkeeper[$inFrom])) {
                return true;
            }
        }
        return false;
    }

    public function getSpecification($inFrom, $inTo) {

        if (array_key_exists($inFrom, $this->__specifications)) {
            if (array_key_exists($inTo, $this->__specifications[$inFrom])) {
                return $this->__specifications[$inFrom][$inTo];
            }
        }

        return false;
    }
}