<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\lists;

class ListsWriterTest extends \PHPUnit_Framework_TestCase
{
    private $__weightedGraphs = array();
    private $__unweightedGraphs = array();

    private $__dataPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

    public function setUp() {

        $this->__weightedGraphs[] = null;   // Tests index begin to 1.
        $this->__unweightedGraphs[] = null; // Tests index begin to 1.

        // ### Weighted 1
        // Test the dumping with the default configuration.

        $this->__unweightedGraphs[] = array(
            'e1' => array(
                'e2' => null,
                'e3' => null,
                'e4' => null
            ),
            'e2' => array(
                'e5' => null,
                'e6' => null
            ),
            'e3' => array(
                'e7' => null
            ),
            'e4' => array()
        );

        // ### Weighted 2
        // Test the dumping with a non default vertex serializer.

        $this->__unweightedGraphs[] = array(
            'e1 ' => array(
                'e2 ' => null,
                'e3 ' => null,
                'e4 ' => null
            ),
            'e2 ' => array(
                'e5 ' => null,
                'e6 ' => null
            ),
            'e3 ' => array(
                'e7 ' => null
            ),
            'e4 ' => array()
        );

        // ### Weigthed 3
        // Test the dumping with a non default vertex

        $this->__unweightedGraphs[] = array(
            'e1' => array(
                'e2' => null,
                'e3' => null,
                'e4' => null
            ),
            'e2' => array(
                'e5' => null,
                'e6' => null
            ),
            'e3' => array(
                'e7' => null
            ),
            'e4' => array()
        );


        // ###  Unweighted 1
        // Test the dumping with the default configuration

        $this->__weightedGraphs[] = array(
            'e1' => array(
                'e2' => 1,
                'e3' => 2,
                'e4' => 3
            ),
            'e2' => array(
                'e5' => 4,
                'e6' => 5
            ),
            'e3' => array(
                'e7' => 6
            ),
            'e4' => array()
        );

        // ###  Unweighted 2
        // Test the dumping with a non default weight indicator and a non default field separator.

        $this->__weightedGraphs[] = array(
            'e1' => array(
                'e2' => 1,
                'e3' => 2,
                'e4' => 3
            ),
            'e2' => array(
                'e5' => 4,
                'e6' => 5
            ),
            'e3' => array(
                'e7' => 6
            ),
            'e4' => array()
        );

        // ###  Unweighted 3
        // Test the dumping with a non default vertex serializer.

        $this->__weightedGraphs[] = array(
            'e1 ' => array(
                'e2 ' => 1,
                'e3 ' => 2,
                'e4 ' => 3
            ),
            'e2 ' => array(
                'e5 ' => 4,
                'e6 ' => 5
            ),
            'e3 ' => array(
                'e7 ' => 6
            ),
            'e4 ' => array()
        );
    }


    public function testDumpListToCsvUnweightedGraph() {

        $tmpPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'g.csv';

        $list = new Lists();
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-unweighted-graph-1.csv';
        $list->dumpListToCsv($tmpPath, $this->__unweightedGraphs[1]);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new Lists();
        $list->setVertexSerializer(function($v) { return urlencode($v); });
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-unweighted-graph-2.csv';
        $list->dumpListToCsv($tmpPath, $this->__unweightedGraphs[2]);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new Lists();
        $list->setFieldSeparator(';');
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-unweighted-graph-3.csv';
        $list->dumpListToCsv($tmpPath, $this->__unweightedGraphs[3]);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        unlink($tmpPath);
    }

    public function testDumpListToCsvWeightedGraph() {

        $tmpPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'g.csv';

        $list = new Lists();
        $list->setWeighted();
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-1.csv';
        $list->dumpListToCsv($tmpPath, $this->__weightedGraphs[1]);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new Lists();
        $list->setWeightIndicator('/');
        $list->setFieldSeparator(';');
        $list->setWeighted();
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-2.csv';
        $list->dumpListToCsv($tmpPath, $this->__weightedGraphs[2]);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new Lists();
        $list->setVertexSerializer(function ($v) { return urlencode($v); });
        $list->setWeighted();
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-3.csv';
        $list->dumpListToCsv($tmpPath, $this->__weightedGraphs[3]);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        unlink($tmpPath);
    }

}