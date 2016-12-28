<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).


/**
 * This class implements the graph representation known as a "set of adjacency lists".
 * - For undirected graphs, adjacent vertices are neighbours.
 * - For directed graphs, adjacent vertices are successors or predecessors.
 */

namespace dbeurive\Graph\lists;
use dbeurive\Graph\Exception;

/**
 * Class AbstractLists
 *
 * This class implements the graph representation known as a "set of adjacency lists".
 * - For undirected graphs, adjacent vertices are neighbours.
 * - For directed graphs, adjacent vertices are successors or predecessors.
 *
 * Using the PHP formalism, a list is represented by an associative array.
 * - Array keys are vertices' names.
 * - Array values are the list of adjacent vertices.
 *
 * Example for a directed and unweighted graph:
 *
 *      array(
 *          'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
 *          'e2' => array('e5' => null, 'e6' => null),
 *          'e3' => array('e7' => null),
 *          'e4' => array()
 *      )
 *
 *      - The successors of the vertex "e1" are the vertices "e2", "e3", and "e4".
 *      - The successors of the vertex "e2" are the vertices "e5", "e6".
 *      - ...
 *
 *      And, by the way:
 *
 *      - The vertex "e1" has no predecessor.
 *      - The predecessor of the vertex "e2" is the vertex "e1".
 *      - The predecessor of the vertex "e3" is the vertex "e1".
 *      - ...
 *
 * Example for an undirected and unweighted graph:
 *
 *      array(
 *          'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
 *          'e2' => array('a3' => null, 'e1' => null, 'e6' => null, 'e5' => null),
 *          'e3' => array('e1' => null),
 *          'e4' => array('a1' => null, 'e1' => null, 'e7' => null, 'e8' => null),
 *          'a3' => array('e2' => null),
 *          'a1' => array('e4' => null),
 *          'e5' => array('a4' => null, 'e2' => null),
 *          'a4' => array('e5' => null),
 *          'e6' => array('e2' => null),
 *          'e7' => array('e4' => null),
 *          'e8' => array('e4' => null, 'a2' => null),
 *          'a2' => array('e8' => null)
 *      )
 *
 *      - The neighbours of the vertex "e1" are the vertices "e2", "e3", and "e4".
 *      - The neighbours of the vertex "e2" are the vertices "a3", "e1", "e6" and "e5".
 *      - ...
 *
 * Example for a directed and weighted graph:
 *
 *      array(
 *          'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
 *          'e2' => array('e5' => 4, 'e6' => 5),
 *          'e3' => array('e7' => 1),
 *          'e4' => array()
 *      )
 *
 *      - The edge from the vertex "e1" to the vertex "e2" weights 1 unit.
 *      - The edge from the vertex "e1" to the vertex "e3" weights 2 units.
 *      - ...
 *
 * Example for an undirected and weighted graph:
 *
 *      array(
 *          'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
 *          'e2' => array('a3' => 4, 'e1' => 1, 'e6' => 6, 'e5' => 7),
 *          'e3' => array('e1' => 2),
 *          'e4' => array('a1' => 8, 'e1' => 3, 'e7' => 10, 'e8' => 11),
 *          'a3' => array('e2' => 4),
 *          'a1' => array('e4' => 8),
 *          'e5' => array('a4' => 12, 'e2' => 7),
 *          'a4' => array('e5' => 12),
 *          'e6' => array('e2' => 6),
 *          'e7' => array('e4' => 10),
 *          'e8' => array('e4' => 11, 'a2' => 15),
 *          'a2' => array('e8' => 15)
 *      )
 *
 *      - The edge between the vertex "e1" and the vertex "e2" weights 1 unit.
 *      - The edge between the vertex "e1" and the vertex "e3" weights 2 units.
 *      - ...
 *
 * Please note that:
 *
 * A set of adjacency lists is said "complete" when the list of keys of the associative array that represents the graph contains all the names of the vertices that make the graph.
 *
 * For example:
 *
 * The following set of adjacency lists is not complete ("e5" is not part of the array's keys, for example):
 *
 *      array(
 *          'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
 *          'e2' => array('e5' => null, 'e6' => null),
 *          'e4' => array('e7' => null, 'e8' => null),
 *          'a3' => array('e2' => null),
 *          'a1' => array('e4' => null),
 *          'a4' => array('e5' => null),
 *          'a2' => array('e8' => null)
 *      )
 *
 * However, the following set of adjacency lists is complete:
 *
 *      array(
 *          'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
 *          'e2' => array('e5' => null, 'e6' => null),
 *          'e4' => array('e7' => null, 'e8' => null),
 *          'a3' => array('e2' => null),
 *          'a1' => array('e4' => null),
 *          'a4' => array('e5' => null),
 *          'a2' => array('e8' => null),
 *          'e6' => array(),
 *          'e7' => array(),
 *          'e3' => array(),
 *          'e5' => array(),
 *          'e8' => array()
 *      )
 *
 * @note This class does not define any abstract methods.
 *       However, it is declared "abstract" because it is not supposed to be instantiated.
 *
 * @package dbeurive\Graph
 */
abstract class AbstractLists
{
    const RWC_DEFAULT_FIELD_SEPARATOR = ' ';
    const RWC_DEFAULT_WEIGHT_SEPARATOR = ':';

    /** @var string */
    private $__fieldSeparator = self::RWC_DEFAULT_FIELD_SEPARATOR;
    /** @var string */
    private $__weightIndicator = self::RWC_DEFAULT_WEIGHT_SEPARATOR;
    /** @var bool This flag specifies whether the graph is weighted or not. */
    private $__weighted = false;
    /** @var bool This flag specifies whether the graph is directed or not. */
    private $__directed = true;
    /** @var array|callable */
    private $__vertexUnserializer = null;
    /** @var array|callable */
    private $__vertexSerializer = null;
    /** @var array|callable */
    private $__vertexValidator = null;
    /** @var array|callable */
    private $__linePreprocessor = null;

    /**
     * NeighborsLists constructor.
     */
    public function __construct() {
        $this->__vertexUnserializer = $this->_defaultVertexUnserializer();
        $this->__vertexSerializer = $this->_defaultVertexSerializer();
        $this->__vertexValidator = $this->_defaultVertexValidator();
        $this->__linePreprocessor = $this->_defaultLinePreProcessor();
    }

    /**
     * The method configures the processor so that it expects to load a weighted graph.
     * By default, graphs are expected to be unweighted.
     * @return $this
     */
    protected function _setWeighted() {
        $this->__weighted = true;
        return $this;
    }

    /**
     * The method configures the processor so that it expects to load a unweighted graph.
     * By default, graphs are expected to be unweighted.
     * @return $this
     */
    protected function _setUnweighted() {
        $this->__weighted = false;
        return $this;
    }

    /**
     * The method configures the processor so that it expects to load a directed graph.
     * By default, graphs are expected to be directed.
     * @return $this
     */
    protected function _setDirected() {
        $this->__directed = true;
        return $this;
    }

    /**
     * The method configures the processor so that it expects to load an undirected graph.
     * By default, graphs are expected to be directed.
     * @return $this
     */
    protected function _setUndirected() {
        $this->__directed = false;
        return $this;
    }

    /**
     * Set the fields separator.
     * @param string $inFieldSeparator The fields separator. Default is " ".
     * @return $this
     * @see AbstractLists::RWC_DEFAULT_FIELD_SEPARATOR
     */
    public function setFieldSeparator($inFieldSeparator) {
        $this->__fieldSeparator = $inFieldSeparator;
        return $this;
    }

    /**
     * For weighted graphs, set the string used to prefix the weight of an edge.
     * Please see the documentation for the method loadListsFromCsv() for details.
     * @param string $inWeightIndicator The string used to prefix the weight of an edge. Default is: ":".
     * @return $this
     * @see AbstractLists::RWC_DEFAULT_WEIGHT_SEPARATOR
     */
    protected function _setWeightIndicator($inWeightIndicator) {
        $this->__weightIndicator = $inWeightIndicator;
        $this->_setWeighted();
        return $this;
    }

    /**
     * This method returns the default line preprocessor.
     * @return callable The default line preprocessor.
     * @note The line preprocessor is used when a CSV file is loaded.
     */
    protected function _defaultLinePreProcessor() {
        return function($inLine) {
            return preg_replace('/\r?\n$/', '', trim($inLine));
        };
    }

    /**
     * Return the default vertex serializer.
     * @return callable
     * @note The vertex serializer is used when a CVS file is written.
     */
    protected function _defaultVertexSerializer() {
        return function ($inVertex) {
            return $inVertex;
        };
    }

    /**
     * Return the default vertex unserializer.
     * @return callable
     * @note The vertex unserializer is used when a CSV file is loaded.
     */
    protected function _defaultVertexUnserializer() {
        return function ($inSerializedVertex) {
            return $inSerializedVertex;
        };
    }

    /**
     * Return the default vertex validator.
     * @return callable
     * @note The vertex validator is used when a CSV file is loaded.
     *       It checks that the vertex's name is valid.
     */
    protected function _defaultVertexValidator() {
        return function ($inVertex) {
            return true;
        };
    }

    // --------------------------------------------------------------------------
    // Lists reader
    // --------------------------------------------------------------------------

    /**
     * Set the line pre-processor.
     * The line pre-processor is a function that is applied to each line extracted from the file, before any further treatment.
     * @param array|callable $inLinePreprocessor The line pre-processor.
     *        The signature ot this function must be:
     *        string function(string $inLine)
     *        The default pre-processing is:
     *        * Remove leading and trailing spaces.
     *        * Remove the "end of line" sequence "\r?\n".
     * @return $this
     * @see AbstractLists::_defaultLinePreProcessor()
     */
    public function setLinePreProcessor($inLinePreprocessor) {
        $this->__linePreprocessor = $inLinePreprocessor;
        return $this;
    }

    /**
     * Set the function used to unserialize the name of a vertex.
     * @param array|callable $inUnserializer The function used to unserialize the name of a vertex.
     *        The signature of this function must be:
     *        string function(string $serializedVertex)
     * @return $this
     * @see AbstractLists::_defaultVertexUnserializer()
     */
    public function setVertexUnserializer($inUnserializer) {
        $this->__vertexUnserializer = $inUnserializer;
        return $this;
    }

    /**
     * Set the function used to validate the name of a vertex.
     * @param array|callable $inValidator The validator function.
     *        The signature of his function must be:
     *        bool function(string $inVertexName)
     *        * If the name of the vertex is valid, then the function must return the value true.
     *        * Otherwise, the function must return the value false.
     *        The default validator always returns the value true.
     * @return $this
     * @see AbstractLists::_defaultVertexValidator()
     */
    public function setVertexValidator($inValidator) {
        $this->__vertexValidator = $inValidator;
        return $this;
    }

    /**
     * Load a given CSV file that represents a (weighted ou unweighted) graph defined by a set of lists of adjacent vertices.
     * Each line of the CSV file contains one or more fields.
     *   * The first field represents the current vertex.
     *   * The following fields represent the edges between the current vertex and its adjacent vertices (if any).
     *
     * For example, let's assume that the field separator is the space and that we have the following line:
     *   e1 e2 e3 e4
     * This means that: the adjacent vertices of the vertex "e1" are the vertices "e2", "e3" and "e4".
     *
     * Or, let's assume the field separator is the space and that we have the following line:
     *   e1 e2:1 e3:10 e4:50
     * Means that: This means that: the adjacent vertices of the vertex "e1" are the vertices "e2", "e3" and "e4".
     * And: the edge between "e1" and "e2" weights 1.
     *      the edge between "e1" and "e3" weights 10.
     *      the edge between "e1" and "e4" weights 50.
     *
     * @param string $inPath Path to the CSV file to load.
     * @return array The method returns an array that represents the graph's definition loaded from the given CSV file.
     *         Example 1:
     *              If the given CSV file contains the following lines:
     *                  e1 e2:1 e3:2 e7:3
     *                  e2 e5:4
     *              Then, the returned array is:
     *              array(
     *                  'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
     *                  'e2' => array('e5' => 4)
     *              )
     *         Example 2:
     *              If the given CSV file contains the following lines:
     *                  e1 e2 e3 e7
     *                  e2 e5
     *              Then, the returned array is:
     *              array(
     *                  'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
     *                  'e2' => array('e5' => null)
     *              )
     * @throws Exception
     * @note This method is applicable to weighted and unweighted graphs.
     * @note This method is applicable to directed and undirected graphs.
     */
    protected function _loadListsFromCsv($inPath) {

        if (false === $input = @fopen($inPath, 'r')) {
            $error = error_get_last();
            throw new Exception("Can not open input CSV file <$inPath>: " . $error['message']);
        }

        $list = array();

        while ((false !== $line = fgets($input))) {
            if ($this->__isBlankLine($line)) {
                continue;
            }

            $line = call_user_func($this->__linePreprocessor, $line);
            $fields = explode($this->__fieldSeparator, $line);
            $leftVertex = call_user_func($this->__vertexUnserializer, array_shift($fields));

            if (array_key_exists($leftVertex, $list)) {
                throw new Exception("Duplicate entry found for vertex \"$leftVertex\".");
            }

            if (false === call_user_func($this->__vertexValidator, $leftVertex)) {
                throw new Exception("Invalid vertex detected ($leftVertex).");
            }
            $list[$leftVertex] = $this->__processListOfEdges($fields);
        }

        if (false === fclose($input)) {
            $error = error_get_last();
            throw new Exception("Can not close input CSV file <$inPath>: " . $error['message']);
        }

        return $list;
    }

    /**
     * Test whether a line is blank or not.
     * @param string $inLine Line to test.
     * @return bool If the line is blank, then the method returns the value true.
     *         Otherwise, the method returns the value false.
     */
    private function __isBlankLine($inLine) {
        if (preg_match('/^\s*#/', $inLine) === 1) {
            return true;
        }

        if (preg_match('/^\s*$/', $inLine) === 1) {
            return true;
        }
        return false;
    }

    /**
     * Process a list of edges.
     * @param array $inEdgesList List of edges.
     * @return array The method returns an array that represents the list of edges.
     */
    private function __processListOfEdges($inEdgesList) {
        if ($this->__weighted) {
            return $this->__processListOfWeightedEdges($inEdgesList);
        } else {
            return $this->__processListOfUnweightedEdges($inEdgesList);
        }
    }

    /**
     * Process a list of unweigthed edges.
     * @param array $inFields List of unweighted edges.
     * @return array The method returns an array that represents the list of unweighted edges.
     * @throws Exception
     */
    private function __processListOfUnweightedEdges(array $inFields) {
        $result = array();
        foreach ($inFields as $_field) {
            $vertex = call_user_func($this->__vertexUnserializer, $_field);
            if (false === call_user_func($this->__vertexValidator, $vertex)) {
                throw new Exception("Invalid vertex detected ($vertex).");
            }
            $result[$vertex] = null;
        }
        return $result;
    }

    /**
     * Process a list of weigthed edges.
     * @param array $inFields List of weighted edges.
     * @return array The method returns an array that represents the list of weighted edges.
     * @throws Exception
     */
    private function __processListOfWeightedEdges(array $inFields) {
        $result = array();
        foreach ($inFields as $_field) {
            $tokens = explode($this->__weightIndicator, $_field);
            if (2 != count($tokens)) {
                throw new Exception("Invalid field detected ($_field): the weight is missing.");
            }
            $vertex = call_user_func($this->__vertexUnserializer, $tokens[0]);
            $weight = $tokens[1];
            if (false === call_user_func($this->__vertexValidator, $vertex)) {
                throw new Exception("Invalid vertex detected ($vertex).");
            }
            if (! is_numeric($weight)) {
                throw new Exception("Invalid weight detected ($weight).");
            }
            $result[$vertex] = $weight;
        }
        return $result;
    }

    // --------------------------------------------------------------------------
    // Lists writer
    // --------------------------------------------------------------------------

    /**
     * Set the function used to serialize the name of a vertex.
     * @param array|callable $inSerializer The function used to serialize the vertex's name.
     *        The signature of the function must be:
     *        string function(string $vertex)
     *        The default serializer is:
     *        function($vertex) { return $vertex; }
     * @return $this
     * @see AbstractLists::_defaultVertexValidator()
     */
    public function setVertexSerializer($inSerializer) {
        $this->__vertexSerializer = $inSerializer;
        return $this;
    }

    /**
     * This method dumps a given graph, represented as a set of adjacency lists, in a given file.
     * @param string $inPath Path the the file.
     * @param array $inLists Lists of adjacent vertices.
     * @return $this
     * @throws Exception
     */
    protected function _dumpListToCsv($inPath, array $inLists) {

        if (false === $output = @fopen($inPath, 'w')) {
            $error = error_get_last();
            throw new Exception("Can not open output CSV file <$inPath>: " . $error['message']);
        }

        foreach ($inLists as $_leftVertex => $_neighbours) {

            $line = array(call_user_func($this->__vertexSerializer, $_leftVertex));

            foreach ( $_neighbours as $_vertex => $_weight) {
                if ($this->__weighted && is_null($_weight)) {
                    throw new Exception("Invalid weighted graph. I found an edged without weight ($_leftVertex -> $_vertex).");
                }

                $weight = is_null($_weight) ? '' : $this->__weightIndicator . $_weight;
                $line[] = call_user_func($this->__vertexSerializer, $_vertex) . $weight;
            }

            if (false === fwrite($output, implode($this->__fieldSeparator, $line) . PHP_EOL)) {
                $error = error_get_last();
                throw new Exception("Can not write data into output CSV file <$output>: " . $error['message']);
            }
        }

        if (false === fclose($output)) {
            $error = error_get_last();
            throw new Exception("Can not close output CSV file <$output>: " . $error['message']);
        }

        return $this;
    }

    // --------------------------------------------------------------------------
    // Lists converter
    // --------------------------------------------------------------------------

    /**
     * Complete a given set of adjacency lists.
     *
     * Example for a directed graph: let's assume that the given set of lists is:
     *         array(
     *            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
     *            'e2' => array('e5' => null)
     *         )
     *         Then, the completed set of lists is:
     *         array(
     *            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
     *            'e2' => array('e5' => null),
     *            'e3' => array(),
     *            'e4' => array(),
     *            'e5' => array()
     *         )
     * Example for an undirected graph
     *         array(
     *            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
     *            'e2' => array('e5' => null)
     *         )
     *         Then, the completed set of lists is:
     *         array(
     *            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
     *            'e2' => array('e1' => null, 'e5' => null),
     *            'e3' => array('e1' => null),
     *            'e4' => array('e1' => null),
     *            'e5' => array('e2' => null)
     *         )
     * @param array $inLists Set of lists to complete.
     * @return $this;
     * @note The method modifies the array given as input (that is: $inLists).
     * @note This methods is applicable to weighted and unweighted graphs.
     * @note This methods is applicable to directed and undirected graphs.
     */
    protected function _complete(array &$inLists) {
        /**
         * @var string $_vertex
         * @var array $_neighbours
         */
        foreach ($inLists as $_vertex => $_neighbours) {
            /**
             * @var string $__vertex
             * @var null $__weight
             */
            foreach ($_neighbours as $__vertex => $__weight) {
                if (! array_key_exists($__vertex, $inLists)) {
                    if ($this->__directed) {
                        $inLists[$__vertex] = array();
                    } else {
                        $inLists[$__vertex] = array($_vertex => $__weight);
                    }
                } else {
                    if (! $this->__directed) {
                        $inLists[$__vertex][$_vertex] = $__weight;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Reverse a given set of lists of adjacent vertices (that represents a directed graph).
     * Please note that the term "reverse" means that:
     * If the given set of lists represents a set of successors, then the method returns a set of predecessors.
     * If the given set of lists represents a set of predecessors, then the method returns a set of successors.
     * Example for a directed graph: let's assume that the given set of lists is:
     *         array(
     *             'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
     *             'e2' => array('e5' => null),
     *             'e3' => array('e1'),
     *             'e4' => array(),
     *             'e5' => array(),
     *             'e6' => array()
     *         )
     *         Then, the returned array will be:
     *         array (
     *             'e1' => array('e3' => null),
     *             'e2' => array('e1' => null),
     *             'e3' => array('e1' => null),
     *             'e4' => array('e1' => null),
     *             'e5' => array('e2' => null),
     *             'e6' => array()
     *         )
     *
     * @param array $inLists The set of lists to reverse.
     * @param bool $inOptNeedComplete This flag indicates whether the given set of lists should be completed or not (default is NO).
     * @return array The method returns the reversed set of lists.
     * @note This methods is applicable to weighted and unweighted graphs.
     * @note Reversion does not make sense for undirected graphs.
     */
    protected function _reverse(array &$inLists, $inOptNeedComplete=false) {
        if ($inOptNeedComplete) {
            self::_complete($inLists);
        }
        $result = array();
        /**
         * @var string $_vertex
         * @var array $_neighbours
         */
        foreach ($inLists as $_vertex => $_neighbours) {
            if (! array_key_exists($_vertex, $result)) {
                $result[$_vertex] = array();
            }
            /**
             * @var string $__vertex
             * @var null|int $__weight
             */
            foreach ($_neighbours as $__vertex => $__weight) {
                if (! array_key_exists($__vertex, $result)) {
                    $result[$__vertex] = array();
                }
                $result[$__vertex][$_vertex] = $__weight;
            }
        }
        return $result;
    }

    /**
     * Return the Graphviz representation of the graph.
     * @param array $inLists The set of adjacency lists that define the graph.
     * @param bool $inOptDirected This flag indicates whether the graph is directed or not.
     *        * The value true indicates that the graph is directed.
     *        * The value false indicates that the graph is not directed.
     *        By default, graphs are considered to be directed.
     * @param array $inNodesSpecifications Nodes' specifications.
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
     * @param string $inOptName Name of the graph.
     *        See the documentation for Graphviz.
     * @return string The method returns the Graphviz representation of the graph.
     * @see http://graphviz.org/
     * @note This methods is applicable to weighted and unweighted graphs.
     * @note This methods is applicable to directed and undirected graphs.
     */
    protected function _toGraphviz(array &$inLists, $inOptDirected=true, array $inNodesSpecifications=array(), array $inOptEdgesSpecifications=array(), $inOptName='MyGraph') {

        $type = $inOptDirected ? 'digraph' : 'graph';

        $result = array("${type} \"${inOptName}\" {");

        /**
         * @var string $_node
         * @var array $_specifications
         */
        foreach ($inNodesSpecifications as $_node => $_specifications) {
            $n = array("\"${_node}\" [");
            $spec = array();
            /**
             * @var string $__property
             * @var string $__value
             */
            foreach ($_specifications as $__property => $__value) {
                $spec[] = $__property . ' = "' . $__value . '"';
            }
            $n[] = implode(', ', $spec) . '];';
            $result[] = implode(' ', $n);
        }

        $g = array();
        if ($inOptDirected) {
            $g = $this->__directedToGraphviz($inLists, $inOptEdgesSpecifications);
        } else {
            $g = $this->__undirectedToGraphviz($inLists, $inOptEdgesSpecifications);
        }

        $result = array_merge($result, $g);

        $result[] = '}';
        return implode(PHP_EOL, $result);
    }

    /**
     * Returns the Graphviz representation for a given set of adjacency lists that represent a directed graph.
     * @param array $inLists The set of lists of successors (or predecessors) that define the directed graph.
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
     * @return array The method returns the Graphviz representation.
     */
    private function __directedToGraphviz(array &$inLists, array $inOptEdgesSpecifications=array()) {
        $result = array();

        /**
         * @var string $_vertex
         * @var array $_neighbours
         */
        foreach ($inLists as $_vertex => $_neighbours) {
            /**
             * @var string $__vertex
             * @var int $__weight
             */
            foreach ($_neighbours as $__vertex => $__weight) {


                $properties = array();
                if (! is_null($__weight)) {
                    $properties[] = "label = \"${__weight}\"";
                }
                if (array_key_exists($_vertex, $inOptEdgesSpecifications)) {
                    if (array_key_exists($__vertex, $inOptEdgesSpecifications[$_vertex])) {
                        foreach ($inOptEdgesSpecifications[$_vertex][$__vertex] as $_property => $_value) {
                            $properties[] = "$_property = \"$_value\"";
                        }
                    }
                }

                $properties = count($properties) > 0 ? ' [' . implode(', ', $properties) . ']' : '';

                $result[] = "\"${_vertex}\" -> \"${__vertex}\"${properties};";
            }
        }
        return $result;
    }

    /**
     * Returns the "bare" Graphviz representation for a given set of adjacency lists that represent an undirected graph.
     * @param array $inLists The set of lists of neighbours that define the undirected graph.
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
     * @return array The method returns the "bare" Graphviz representation.
     */
    private function __undirectedToGraphviz(array &$inLists, array $inOptEdgesSpecifications=array()) {
        $result = array();
        $edgeBookkeeper = new EdgeBookkeeper($inLists, $inOptEdgesSpecifications);

        // var_dump($inOptEdgesSpecifications);

        /**
         * @var string $_vertex
         * @var array $_neighbours
         */
        foreach ($inLists as $_vertex => $_neighbours) {

            /**
             * @var string $__neighbourVertex
             * @var null|int $__weight
             */
            foreach ($_neighbours as $__neighbourVertex => $__weight) {
                // Dumping "$_vertex -- $__neighbourVertex".
                // Do not dump the same edge twice.

                if ($edgeBookkeeper->isDeclared($_vertex, $__neighbourVertex)) {
                    continue;
                }
                $edgeBookkeeper->declareEdge($_vertex, $__neighbourVertex);

                $properties = array();
                if (! is_null($__weight)) {
                    $properties[] = "label = \"${__weight}\"";
                }

                $specifications = $edgeBookkeeper->getSpecification($_vertex, $__neighbourVertex);
                if (false !== $specifications) {
                    foreach ($specifications as $_property => $_value) {
                        $properties[] = "$_property=\"$_value\"";
                    }
                }


//                if (array_key_exists($_vertex, $inOptEdgesSpecifications)) {
//                    if (array_key_exists($__neighbourVertex, $inOptEdgesSpecifications[$_vertex])) {
//                        foreach ($inOptEdgesSpecifications[$_vertex][$__neighbourVertex] as $_property => $_value) {
//                            $properties[] = "$_property=\"$_value\"";
//                        }
//                    }
//                }

                $properties = count($properties) > 0 ? ' [' . implode(',', $properties) . ']' : '';
                $result[] = "\"${_vertex}\" -- \"${__neighbourVertex}\"${properties};";
            }
        }

        // var_dump($done);
        return $result;
    }

}