<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\Graph;

/**
 * Class AbstractDepthFirstSearch
 *
 * This class implements the "Depth First Search" algorithm.
 *
 * @package dbeurive\Graph
 */
abstract class AbstractDepthFirstSearch extends AbstractAlgorithm
{
    /**
     * Start the traversal.
     * @param string $inStartVertex Name of the vertex.
     * @param array|callable $inCallback Callback function executed for each visited vertex.
     *        The signature of this function must be: bool function(string $inVertexName)
     *        - $inVertexName: Name of the visited vertex.
     *        - If the function returns the value true, then the algorithm continues.
     *          Otherwise, the algorithm stops.
     * @throws Exception
     */
    public function run($inStartVertex, $inCallback) {

        $lists = $this->_getAdjacentVertices();
        if (! array_key_exists($inStartVertex, $lists)) {
            throw new Exception("Unexpected vertex <$inStartVertex>.");
        }

        $stack = array();
        $discovered = array();
        $stack[] = $inStartVertex;
        while (count($stack) > 0) {
            $vertex = array_pop($stack);
            if (! array_key_exists($vertex, $discovered)) {
                $discovered[$vertex] = true;
                if (false === call_user_func($inCallback, $vertex)) {
                    break;
                }

                /**
                 * @var string $_nextVertex
                 * @var null|int $_weight
                 */
                foreach ($lists[$vertex] as $_nextVertex => $_weight) {
                    $stack[] = $_nextVertex;
                }
            }
        }
    }
}