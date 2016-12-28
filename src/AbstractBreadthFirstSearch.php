<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\Graph;

/**
 * Class AbstractBreadthFirstSearch
 *
 * This class implements the "Breadth First Search" algorithm.
 * Please note that this algorithm works for directed and undirected graphs.
 *
 * @package dbeurive\Graph
 */

abstract class AbstractBreadthFirstSearch extends AbstractAlgorithm
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

        $queue = array($inStartVertex);
        $visited = array($inStartVertex => null);
        
        while (count($queue) > 0) {
            $vertex = array_pop($queue);
            if (false === call_user_func($inCallback, $vertex)) {
                break;
            }

            /**
             * @var string $_nextVertex
             * @var int|null $_weight
             */
            foreach ($lists[$vertex] as $_nextVertex => $_weight) {
                if (array_key_exists($_nextVertex, $visited)) {
                    continue;
                }
                array_unshift($queue, $_nextVertex);
                $visited[$_nextVertex] = null;
            }
        }
    }
}