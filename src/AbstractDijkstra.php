<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the Dijkstra's algorithm.
 * This algorithm solves the "Single-Source Shortest Path Problem".
 */

namespace dbeurive\Graph;

/**
 * Class AbstractDijkstra
 *
 * This class implements the Dijkstra's algorithm.
 * This algorithm solves the "Single-Source Shortest Path Problem".
 *
 * Conditions:
 * - This algorithm works with both directed and undirected graphs
 * - All edges must have nonnegative weights
 * - The graph must be connected
 *
 * @see http://math.mit.edu/~rothvoss/18.304.3PM/Presentations/1-Melissa.pdf
 * @see http://www.ifp.illinois.edu/~angelia/ge330fall09_dijkstra_l18.pdf
 *
 * @package dbeurive\Graph
 */
abstract class AbstractDijkstra extends AbstractAlgorithm
{
    const DIJSTRA_INFINITE = -1;
    const KEY_VERTEX = 'vertex';
    const KEY_DISTANCE = 'distance';
    const KEY_INDEX = 'index';

    /**
     * @var null|array This property contains data about each vertex within the graph, relatively to the source vertex.
     *      The structure of this associative array is:
     *      array(<vertex> => <data>,
     *            <vertex> => <data>,
     *            ...
     *      )
     *      There is one entry <vertex> per vertex within the graph.
     *
     *      <data> is an associative array with the following keys:
     *      - AbstractDijkstra::KEY_DISTANCE: minimal distance between the "source vertex" and <vertex>.
     *      - AbstractDijkstra::KEY_VERTEX: name of the precedent vertex, along the shortest path from the "source vertex" to <vertex>.
     */
    private $__topologicalData = null;
    /** @var null|string The source vertex. */
    private $__sourceVertex = null;

    /**
     * Extract the minimal distances between each vertex of the graph and the "source vertex" from the topological data.
     * @param array $inTopologicalData The tological data.
     * @return array The method returns an associative array.
     * @see $__topologicalData for details about the structure of the parameter $inData.
     */
    private function __getDistancesFromTopologicalData(array $inTopologicalData) {
        /**
         * @var string $_vertex
         * @var array $_data
         */
        $result = array();
        foreach ($inTopologicalData as $_vertex => $_data) {
            $result[$_vertex] = $_data[self::KEY_DISTANCE];
        }
        return $result;
    }

    /**
     * Compare two distances (between two vertices).
     * @param int $inDistance1 First distance to compare.
     * @param int $inDistance2 Second distance to compare.
     * @return int If $inDistance1 > $inDistance2, then the metod returns the value +1.
     *             If $inDistance1 < $inDistance2, then the metod returns the value -1.
     *             If $inDistance1 = $inDistance2, then the metod returns the value 0.
     */
    private function __dijkstraCmp($inDistance1, $inDistance2) {
        if ((self::DIJSTRA_INFINITE == $inDistance1) && (self::DIJSTRA_INFINITE == $inDistance2)) {
            return 0;
        }

        if (self::DIJSTRA_INFINITE == $inDistance1) {
            return +1;
        }

        if (self::DIJSTRA_INFINITE == $inDistance2) {
            return -1;
        }

        if ($inDistance1 == $inDistance2) {
            return 0;
        }

        return $inDistance1 > $inDistance2 ? +1 : -1;
    }

    /**
     * Select the closest vertex from the source vertex, among the vertices that have not been visited yet.
     * Please note that the first time that this method is executed: the selected vertex will be the source vertex.
     * @param array $inQueue List of vertices that have not been visited yet.
     *        Please note that the first time that this method is executed, this list is empty.
     * @param array $inDistances Distances between the source vertex and the visited vertices.
     * @return array The method returns an array that contains the following keys:
     *         - Dijkstra::KEY_VERTEX The name of the closest - selected - (from the source vertex) unvisited vertex.
     *         - Dijkstra::KEY_DISTANCE The distance between the selected vertex and the source vertex.
     *         - Dijkstra::KEY_INDEX The index, within the list of unvisited vertices (that is: the queue), of the selected vertex.
     * @see DirectedDijkstra::KEY_VERTEX
     * @see DirectedDijkstra::KEY_DISTANCE
     * @see DirectedDijkstra::KEY_INDEX
     */
    private function __dijkstraSelectVertexWithinMinDistanceFromCurrentVertex(array $inQueue, array $inDistances) {

        // The first time that this method is executed:
        //   - $inVisited is empty.
        //   - $queue contains all vertices.
        //   - All vertices' distances within $inDistances are infinite, except for the source vertex (which distance is 0).
        //   => The selected vertex will be the source vertex (since it's distance is 0).
        //
        // The second time that this method is executed:
        //   - $inVisited contains one element: the source vertex.
        //   - $queue contains all vertices, except the source vertex.
        //   - All vertices' distances within $inDistances are infinite, except for:
        //     - the source vertex.
        //     - all neighbours of the source vertex.
        //   => The selected vertex will be the neighbour of the source vertex with the smallest distance.

        $vertex      = null;
        $index       = null;
        $minDistance = $inDistances[$inQueue[0]];
        for ($i=0; $i<count($inQueue); $i++) {
            $_vertex = $inQueue[$i];
            $_distance = $inDistances[$_vertex];
            $cmp = $this->__dijkstraCmp($_distance, $minDistance);
            if ((-1 == $cmp) || (0 == $cmp)) {
                $minDistance = $_distance;
                $vertex = $_vertex;
                $index = $i;
            }
        }

        return array(
            self::KEY_VERTEX   => $vertex,
            self::KEY_DISTANCE => $minDistance,
            self::KEY_INDEX    => $index
        );
    }

    /**
     * Start the algorithm from a given "source vertex".
     * This algorithm will find the shortest paths from the given "source vertex" to other vertices
     * in the given graph (one-to-all shortest path problem).
     * @param string $inSourceVertex The name of the source vertex.
     * @return array The method returns an associative array which keys are vertices' names and values are topological data.
     *         For example, let $d be the returned associative array.
     *         And let "V1" be the "source array".
     *         $d = array(
     *              'v1' => array(
     *                  AbstractDijkstra::KEY_DISTANCE => 0,
     *                  AbstractDijkstra::KEY_VERTEX => null
     *              ),
     *              'v2' => array(
     *                  AbstractDijkstra::KEY_DISTANCE => 10,
     *                  AbstractDijkstra::KEY_VERTEX => 'V1'
     *              ),
     *              'v3' => array(
     *                  AbstractDijkstra::KEY_DISTANCE => 15,
     *                  AbstractDijkstra::KEY_VERTEX => 'V2'
     *              )
     *         );
     *         Then:
     *         - The shortest distance between V1 and the source vertex (itself) is 0.
     *         - The shortest distance between V2 and the source vertex (V1) is 10.
     *         - The shortest distance between V3 and the source vertex (V1) is 15.
     *         And, along the shortest path between V1 (the source vertex) and each vertex:
     *         - The precedent vertex between the source vertex (V1) and V1 is not defined.
     *         - The precedent vertex between the source vertex (V1) and V2 is V1.
     *         - The precedent vertex between the source vertex (V1) and V3 is V2.
     *
     * @throws Exception The given "source vertex" must be part of the graph used to initialise the algorithm.
     * @see $__topologicalData for details about the structure of the returned array.
     */
    public function run($inSourceVertex) {

        $lists = $this->_getAdjacentVertices();
        if (! array_key_exists($inSourceVertex, $lists)) {
            throw new Exception("Unexpected vertex <$inSourceVertex>.");
        }

        // Initialisation:
        // - The queue ($queue) initially contains all vertices.
        // - Set distances between the source vertex to all vertices ($distances), except the source vertex, to infinite.
        // - Distance between the source vertex and itself is zero.
        $topologicalData = array(); // self::KEY_DISTANCE => Distances between the source vertex and the visited vertices.
                                    // self::KEY_VERTEX => Name of the precedent vertex, along the shortest path from the "source vertex" to the current vertex.
        $queue = array();           // List of vertices that have been visited.

        foreach ($lists as $_vertex => $_successorsVertices) {
            $topologicalData[$_vertex] = array(
                // Distances between the source vertex and $_vertex.
                self::KEY_DISTANCE => self::DIJSTRA_INFINITE,
                // Name of the precedent vertex, along the shortest path from the "source vertex" to $_vertex.
                self::KEY_VERTEX => null
            );
            $queue[] = $_vertex;
        }
        $topologicalData[$inSourceVertex] = array(
            self::KEY_DISTANCE => 0,
            self::KEY_VERTEX => null
        );

        // While the queue is not empty.
        while (count($queue) > 0) {
            // Select the element of the queue with the minimal distance from the "source vertex".
            // Let $u be this element.
            // Remove $u from the list of vertices to process.
            $res = $this->__dijkstraSelectVertexWithinMinDistanceFromCurrentVertex($queue, $this->__getDistancesFromTopologicalData($topologicalData));
            $u = $res[self::KEY_VERTEX];   // Newly discovered "closest vertex" to the "source vertex" (at this point).
            $d = $res[self::KEY_DISTANCE]; // Distance between the "source vertex" and the newly discovered "closest vertex".
            $i = $res[self::KEY_INDEX];    // Index of the new discovered "closest vertex" within the queue.
            array_splice($queue, $i, 1);   // Remove $u (that has been selected) from the queue.

            // If necessary, update the minimal distance between the successors of $u and the "source vertex".
            // For all successors of $u.
            foreach ($lists[$u] as $_vertex => $_weight) {
                $distanceFromSourceVertexToSuccessor = $d + $_weight;
                // Please keep in mind that all distances between vertices and the source vertex are initialised to "infinite".
                // If new shortest path found.
                if (+1 == $this->__dijkstraCmp($topologicalData[$_vertex][self::KEY_DISTANCE], $distanceFromSourceVertexToSuccessor)) {
                    // Set new value of shortest path.
                    $topologicalData[$_vertex] = array(
                        self::KEY_DISTANCE => $distanceFromSourceVertexToSuccessor,
                        self::KEY_VERTEX => $u
                    );
                }
            }
        }

        $this->__sourceVertex = $inSourceVertex;
        $this->__topologicalData = $topologicalData;
        return $topologicalData;
    }

    /**
     * This method returns the shortest distances between the "source vertex" and all the other vertices within the graph used to run the algorithm.
     * @return array The method returns an associative array with the following structure:
     *         array(
     *              <vertex> => <shortest distance>,
     *              <vertex> => <shortest distance>,
     *              ...
     *         )
     *         Keys: all vertices within the graph.
     *         Values: for each vertex <vertex>, the value represents the shortest distance between <vertex> and the source vertex.
     */
    public function getShortestDistances() {
        return $this->__getDistancesFromTopologicalData($this->__topologicalData);
    }

    /**
     * This methods returns the shortest paths between the "source vertex" and all the other vertices within the graph used to run the algorithm.
     * @return array The method returns an associative array with the following structure:
     *         array(
     *              <vertex> => array(<milestone> => <shortest distance>, <milestone> => <shortest distance>...),
     *              <vertex> => array(<milestone> => <shortest distance>, <milestone> => <shortest distance>...),
     *         )
     *         Keys: all vertices within the graph.
     *         Values: for each vertex <vertex>, the value represents the shortest path between <vertex> and the source vertex.
     *                 - <milestone> is the name of a vertex.
     *                 - <shortest distance> is the shortest distance between <milestone> and the source vertex.
     */
    public function getShortestPaths() {
        $paths = array();

        foreach (array_keys($this->__topologicalData) as $_vertex) {
            $milestones = array($_vertex => $this->__topologicalData[$_vertex][self::KEY_DISTANCE]);
            $precedentVertex = $this->__topologicalData[$_vertex][self::KEY_VERTEX];
            while(! is_null($precedentVertex)) {
                $milestones[$precedentVertex] = $this->__topologicalData[$precedentVertex][self::KEY_DISTANCE];
                $precedentVertex = $this->__topologicalData[$precedentVertex][self::KEY_VERTEX];
            }

            $paths[$_vertex] = $milestones;
        }

        return $paths;
    }

    /**
     * Return the Graphviz representation of the graph used to run the algorithm.
     * This representations shows the shortest distances and the shortest paths.
     * @param string $inName Name of the graph.
     * @return string The method returns a string that represents the Graphviz representation of the graph used to run the algorithm.
     */
    public function dumpToGraphviz($inName='myGraph') {

        /**
         * @var string $_vertex
         * @var array $_data
         */
        $nodesProperties = array();
        foreach ($this->__topologicalData as $_vertex => $_data) {
            $label = $_vertex . ' (d:' . $_data[DirectedDijkstra::KEY_DISTANCE]. ')';
            $nodesProperties[$_vertex] = array('label' => $label);
        }

        $edgesProperties = array();
        $paths = $this->getShortestPaths();
        /**
         * @var string $_from
         * @var array $_milestones
         */
        foreach ($paths as $_from => $_milestones) {
            $vertices = array_keys($_milestones);
            for($i=0; $i<=count($vertices)-2; $i++) {
                $__from = $vertices[$i];
                $__to = $vertices[$i+1];
                $edgesProperties[$__to][$__from] = array(
                    'color' => '#FF0000',
                    'penwidth' => '2'
                );
            }
        }

        return $this->_dumpToGraphviz($nodesProperties, $edgesProperties, $inName);
    }
}