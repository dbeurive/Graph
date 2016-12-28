<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\lists;
use dbeurive\Graph\lists\UndirectedWeighted;

class UndirectedWeightedTest extends \PHPUnit_Framework_TestCase
{
    private $__dataPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

    public function testLoad() {
        // ### 1
        // Test the loading with the default configuration.

        $list = new UndirectedWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-1.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($neighbours['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($neighbours['e3'], array('e7' => 6, 'e8' => 7));

        // ### 2
        // Test the loading with a non default field separator.

        $list = new UndirectedWeighted();
        $list->setFieldSeparator(';');
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-2.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($neighbours['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($neighbours['e3'], array('e7' => 6, 'e8' => 7));

        // ### 3
        // Test the default line pre processor.
        // It should:
        //    - Remove any trailing sequence "\r?\n".
        //    - trim any leading and trailing spaces.

        $list = new UndirectedWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-3.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($neighbours['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($neighbours['e3'], array('e7' => 6, 'e8' => 7));

        // ### 4
        // Test the loading with non default configuration:
        //      - Set the field separator to ";" (instead of " ").
        //      - Set a new line pre processor (do not trim spaces).

        $list = new UndirectedWeighted();
        $list->setFieldSeparator(';');
        $list->setLinePreProcessor(function($inLine) { return preg_replace('/\r?\n$/', '', $inLine); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-4.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('  e1', $neighbours);
        $this->assertArrayHasKey('  e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['  e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($neighbours['  e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($neighbours['e3'], array('e7' => 6, 'e8' => 7));

        // ### 5
        // Test the loading with non default configuration:
        //      - Set the weight indicator to '-' (instead of ":").
        //      - Set a non default vertex validator.

        $list = new UndirectedWeighted();
        $list->setWeightIndicator('-');
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^(e|f|g)\d+$/', $inVertexName); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-5.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(4, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('f3', $neighbours);
        $this->assertArrayHasKey('g515466666', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($neighbours['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($neighbours['f3'], array('e7' => 6, 'e8' => 7));
        $this->assertEquals($neighbours['g515466666'], array());

        // ### 6
        // Test the loading with a non default vertex unserializer, and a non default vertex validator.
        // The unserializer will URL decode the vertices' names.

        $list = new UndirectedWeighted();
        $list->setVertexUnserializer(function($v) { return urldecode(utf8_decode($v)); });
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^e\d+( \|)?$/', $inVertexName); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-6.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1 |', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['e1 |'], array('e2 |' => 1, 'e3 |' => 2, 'e4 |' => 3));
        $this->assertEquals($neighbours['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($neighbours['e3'], array('e7' => 6, 'e8' => 7));
    }

    public function testDumpToCsv() {

        $neighbours = array(null);

        // Test the dumping with the default configuration

        $neighbours[] = array(
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

        // Test the dumping with a non default weight indicator and a non default field separator.

        $neighbours[] = array(
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

        // Test the dumping with a non default vertex serializer.

        $neighbours[] = array(
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


        $tmpPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'g.csv';

        $list = new UndirectedWeighted();
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-1.csv';
        $list->setNeighbours($neighbours[1])->dumpNeighboursToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new UndirectedWeighted();
        $list->setWeightIndicator('/');
        $list->setFieldSeparator(';');
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-2.csv';
        $list->setNeighbours($neighbours[2])->dumpNeighboursToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new UndirectedWeighted();
        $list->setVertexSerializer(function ($v) { return urlencode($v); });
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-3.csv';
        $list->setNeighbours($neighbours[3])->dumpNeighboursToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        unlink($tmpPath);
    }

    public function testToGraphViz() {

        $neighbours = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('e5' => 4, 'e6' => 5),
            'e4' => array('e7' => 6, 'e8' => 7),
            'a3' => array('e2' => 8),
            'a1' => array('e4' => 9),
            'a4' => array('e5' => 10),
            'a2' => array('e8' => 11)
        );

        $expected = implode (PHP_EOL, array(
            'graph "MyGraph" {',
            '"e1" -- "e2" [label = "1"];',
            '"e1" -- "e3" [label = "2"];',
            '"e1" -- "e4" [label = "3"];',
            '"e2" -- "e5" [label = "4"];',
            '"e2" -- "e6" [label = "5"];',
            '"e4" -- "e7" [label = "6"];',
            '"e4" -- "e8" [label = "7"];',
            '"a3" -- "e2" [label = "8"];',
            '"a1" -- "e4" [label = "9"];',
            '"a4" -- "e5" [label = "10"];',
            '"a2" -- "e8" [label = "11"];',
            '}'
        ));

        $list = new UndirectedWeighted();
        $dot = $list->setNeighbours($neighbours)->dumpNeighboursToGraphviz();
        $this->assertEquals($expected, $dot);
    }
}