<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\lists;
use dbeurive\Graph\lists\UndirectedUnweighted;

class UndirectedUnweightedTest extends \PHPUnit_Framework_TestCase
{
    private $__dataPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

    public function testLoad() {
        // ### 1
        // Test the loading with the default configuration.

        $list = new UndirectedUnweighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-1.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($neighbours['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($neighbours['e3'], array('e7' => null, 'e8' => null));

        // ### 2
        // Test the loading with a non default field separator.

        $list = new UndirectedUnweighted();
        $list->setFieldSeparator(';');
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-2.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($neighbours['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($neighbours['e3'], array('e7' => null, 'e8' => null));

        // ### 3
        // Test the default line pre processor.
        // It should:
        //    - Remove any trailing sequence "\r?\n".
        //    - trim any leading and trailing spaces.

        $list = new UndirectedUnweighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-3.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($neighbours['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($neighbours['e3'], array('e7' => null, 'e8' => null));

        // ### 4
        // Test the loading with non default configuration:
        //      - Set the field separator to ";" (instead of " ").
        //      - Set a new line pre processor (do not trim spaces).

        $list = new UndirectedUnweighted();
        $list->setFieldSeparator(';');
        $list->setLinePreProcessor(function($inLine) { return preg_replace('/\r?\n$/', '', $inLine); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-4.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey(' e3', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($neighbours['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($neighbours[' e3'], array('e7' => null, 'e8' => null));

        // ### 5
        // Test loading with a non default vertex validator.

        $list = new UndirectedUnweighted();
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^(e|f|g)\d+$/', $inVertexName); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-5.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(4, array_keys($neighbours));
        $this->assertArrayHasKey('e1', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('f3', $neighbours);
        $this->assertEquals($neighbours['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($neighbours['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($neighbours['f3'], array('e7' => null, 'e8' => null));
        $this->assertEquals($neighbours['g515466666'], array());

        // ### 6
        // Test the loading with a non default vertex unserializer, and a non default vertex validator.
        // The unserializer will URL decode the vertices' names.

        $list = new UndirectedUnweighted();
        $list->setVertexUnserializer(function($v) { return urldecode(utf8_decode($v)); });
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^e\d+( \|)?$/', $inVertexName); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-6.csv';
        $neighbours = $list->loadNeighboursFromCsv($csvPath)->getNeighbours();
        $this->assertCount(3, array_keys($neighbours));
        $this->assertArrayHasKey('e1 |', $neighbours);
        $this->assertArrayHasKey('e2', $neighbours);
        $this->assertArrayHasKey('e3', $neighbours);
        $this->assertEquals($neighbours['e1 |'], array('e2 |' => null, 'e3 |' => null, 'e4 |' => null));
        $this->assertEquals($neighbours['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($neighbours['e3'], array('e7' => null, 'e8' => null));
    }

    public function testDumpToCsv() {

        $neighbours = array(null); // Tests begins to 1

        // Test the dumping with the default configuration.

        $neighbours[] = array(
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

        // Test the dumping with a non default vertex serializer.

        $neighbours[] = array(
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

        // Test the dumping with a non default vertex

        $neighbours[] = array(
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

        $tmpPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'g.csv';

        $list = new undirectedUnweighted();
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-unweighted-graph-1.csv';
        $list->setNeighbours($neighbours[1])->dumpNeighboursToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new undirectedUnweighted();
        $list->setVertexSerializer(function($v) { return urlencode($v); });
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-unweighted-graph-2.csv';
        $list->setNeighbours($neighbours[2])->dumpNeighboursToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new undirectedUnweighted();
        $list->setFieldSeparator(';');
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-unweighted-graph-3.csv';
        $list->setNeighbours($neighbours[3])->dumpNeighboursToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        unlink($tmpPath);
    }

    public function testToGraphViz() {

        $neighbours = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('e5' => null, 'e6' => null),
            'e4' => array('e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'a4' => array('e5' => null),
            'a2' => array('e8' => null)
        );

        $expected = implode (PHP_EOL, array(
            'graph "MyGraph" {',
            '"e1" -- "e2";',
            '"e1" -- "e3";',
            '"e1" -- "e4";',
            '"e2" -- "e5";',
            '"e2" -- "e6";',
            '"e4" -- "e7";',
            '"e4" -- "e8";',
            '"a3" -- "e2";',
            '"a1" -- "e4";',
            '"a4" -- "e5";',
            '"a2" -- "e8";',
            '}'
        ));

        $list = new UndirectedUnweighted();
        $dot = $list->setNeighbours($neighbours)->dumpNeighboursToGraphviz();
        $this->assertEquals($expected, $dot);
    }
}