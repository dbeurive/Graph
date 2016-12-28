<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\lists;

use dbeurive\Graph\lists\DirectedWeighted;

class DirectedWeightedTest extends \PHPUnit_Framework_TestCase
{
    private $__dataPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

    public function testLoad() {
        // ### 1
        // Test the loading with the default configuration.

        $list = new DirectedWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-1.csv';
        $list->loadSuccessorsFromCsv($csvPath);
        $successors = $list->getSuccessors();
        $this->assertCount(3, array_keys($successors));
        $this->assertArrayHasKey('e1', $successors);
        $this->assertArrayHasKey('e2', $successors);
        $this->assertArrayHasKey('e3', $successors);
        $this->assertEquals($successors['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($successors['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($successors['e3'], array('e7' => 6, 'e8' => 7));

        // ### 2
        // Test the loading with a non default field separator.

        $list = new DirectedWeighted();
        $list->setFieldSeparator(';');
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-2.csv';
        $list->loadSuccessorsFromCsv($csvPath);
        $successors = $list->getSuccessors();
        $this->assertCount(3, array_keys($successors));
        $this->assertArrayHasKey('e1', $successors);
        $this->assertArrayHasKey('e2', $successors);
        $this->assertArrayHasKey('e3', $successors);
        $this->assertEquals($successors['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($successors['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($successors['e3'], array('e7' => 6, 'e8' => 7));

        // ### 3
        // Test the default line pre processor.
        // It should:
        //    - Remove any trailing sequence "\r?\n".
        //    - trim any leading and trailing spaces.

        $list = new DirectedWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-3.csv';
        $list->loadSuccessorsFromCsv($csvPath);
        $successors = $list->getSuccessors();
        $this->assertCount(3, array_keys($successors));
        $this->assertArrayHasKey('e1', $successors);
        $this->assertArrayHasKey('e2', $successors);
        $this->assertArrayHasKey('e3', $successors);
        $this->assertEquals($successors['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($successors['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($successors['e3'], array('e7' => 6, 'e8' => 7));

        // ### 4
        // Test the loading with non default configuration:
        //      - Set the field separator to ";" (instead of " ").
        //      - Set a new line pre processor (do not trim spaces).

        $list = new DirectedWeighted();
        $list->setFieldSeparator(';');
        $list->setLinePreProcessor(function($inLine) { return preg_replace('/\r?\n$/', '', $inLine); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-4.csv';
        $list->loadSuccessorsFromCsv($csvPath);
        $successors = $list->getSuccessors();
        $this->assertCount(3, array_keys($successors));
        $this->assertArrayHasKey('  e1', $successors);
        $this->assertArrayHasKey('  e2', $successors);
        $this->assertArrayHasKey('e3', $successors);
        $this->assertEquals($successors['  e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($successors['  e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($successors['e3'], array('e7' => 6, 'e8' => 7));

        // ### 5
        // Test the loading with non default configuration:
        //      - Set the weight indicator to '-' (instead of ":").
        //      - Set a non default vertex validator.

        $list = new DirectedWeighted();
        $list->setWeightIndicator('-');
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^(e|f|g)\d+$/', $inVertexName); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-5.csv';
        $list->loadSuccessorsFromCsv($csvPath);
        $successors = $list->getSuccessors();
        $this->assertCount(4, array_keys($successors));
        $this->assertArrayHasKey('e1', $successors);
        $this->assertArrayHasKey('e2', $successors);
        $this->assertArrayHasKey('f3', $successors);
        $this->assertArrayHasKey('g515466666', $successors);
        $this->assertEquals($successors['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($successors['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($successors['f3'], array('e7' => 6, 'e8' => 7));
        $this->assertEquals($successors['g515466666'], array());

        // ### 6
        // Test the loading with a non default vertex unserializer, and a non default vertex validator.
        // The unserializer will URL decode the vertices' names.

        $list = new DirectedWeighted();
        $list->setVertexUnserializer(function($v) { return urldecode(utf8_decode($v)); });
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^e\d+( \|)?$/', $inVertexName); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-6.csv';
        $list->loadSuccessorsFromCsv($csvPath);
        $successors = $list->getSuccessors();
        $this->assertCount(3, array_keys($successors));
        $this->assertArrayHasKey('e1 |', $successors);
        $this->assertArrayHasKey('e2', $successors);
        $this->assertArrayHasKey('e3', $successors);
        $this->assertEquals($successors['e1 |'], array('e2 |' => 1, 'e3 |' => 2, 'e4 |' => 3));
        $this->assertEquals($successors['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($successors['e3'], array('e7' => 6, 'e8' => 7));
    }

    public function testCalculatePredecessorsFromSuccessors() {

        $successors = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('e5' => 4, 'e6' => 5),
            'e4' => array('e7' => 6, 'e8' => 7),
            'a3' => array('e2' => 8),
            'a1' => array('e4' => 9),
            'a4' => array('e5' => 10),
            'a2' => array('e8' => 11)
        );

        $expectedPredecessors = array(
            'e5' => array('a4' => 10, 'e2' => 4),
            'e6' => array('e2' => 5),
            'e7' => array('e4' => 6),
            'e8' => array('e4' => 7, 'a2' => 11),
            'e2' => array('a3' => 8, 'e1' => 1),
            'e3' => array('e1' => 2),
            'e4' => array('e1' => 3, 'a1' => 9),
            'a4' => array(),
            'a3' => array(),
            'e1' => array(),
            'a1' => array(),
            'a2' => array()
        );

        $list = new DirectedWeighted();
        $predecessors = $list->setSuccessors($successors)->calculatePredecessorsFromSuccessors()->getPredecessors();
        $this->assertCount(count($expectedPredecessors), $predecessors);

        /**
         * @var string $_key
         * @var array $_value
         */
        foreach ($expectedPredecessors as $_key => $_value) {
            $this->assertArrayHasKey($_key, $predecessors);
            $this->assertCount(count($_value), $predecessors[$_key]);
            /**
             * @var string $__key
             * @var null $_null
             */
            foreach ($_value as $__key => $_null) {
                $this->assertArrayHasKey($__key, $predecessors[$_key]);
            }
        }
    }

    public function testCalculateSuccessorsFromPredecessors() {
        $predecessors = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('e5' => null, 'e6' => null),
            'e4' => array('e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'a4' => array('e5' => null),
            'a2' => array('e8' => null)
        );

        $expectedSuccessors = array(
            'e5' => array('a4' => null, 'e2' => null),
            'e6' => array('e2' => null),
            'e7' => array('e4' => null),
            'e8' => array('e4' => null, 'a2' => null),
            'e2' => array('a3' => null, 'e1' => null),
            'e3' => array('e1' => null),
            'e4' => array('e1' => null, 'a1' => null),
            'a4' => array(),
            'a3' => array(),
            'e1' => array(),
            'a1' => array(),
            'a2' => array()
        );

        $list = new DirectedWeighted();
        $successors = $list->setPredecessors($predecessors)->calculateSuccessorsFromPredecessors()->getSuccessors();
        $this->assertCount(count($expectedSuccessors), $successors);

        /**
         * @var string $_key
         * @var array $_value
         */
        foreach ($expectedSuccessors as $_key => $_value) {
            $this->assertArrayHasKey($_key, $successors);
            $this->assertCount(count($_value), $successors[$_key]);
            /**
             * @var string $__key
             * @var null $_null
             */
            foreach ($_value as $__key => $_null) {
                $this->assertArrayHasKey($__key, $successors[$_key]);
            }
        }
    }

    public function testDumpToCsv() {

        $successors = array(null);

        // Test the dumping with the default configuration

        $successors[] = array(
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

        $successors[] = array(
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

        $successors[] = array(
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

        $list = new DirectedWeighted();
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-1.csv';
        $list->setSuccessors($successors[1])->dumpSuccessorsToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new DirectedWeighted();
        $list->setWeightIndicator('/');
        $list->setFieldSeparator(';');
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-2.csv';
        $list->setSuccessors($successors[2])->dumpSuccessorsToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        $list = new DirectedWeighted();
        $list->setVertexSerializer(function ($v) { return urlencode($v); });
        $expectedCsvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'w-weighted-graph-3.csv';
        $list->setSuccessors($successors[3])->dumpSuccessorsToCsv($tmpPath);
        $this->assertFileEquals($expectedCsvPath, $tmpPath);

        unlink($tmpPath);
    }

    public function testToGraphViz() {

        $successors = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('e5' => 4, 'e6' => 5),
            'e4' => array('e7' => 6, 'e8' => 7),
            'a3' => array('e2' => 8),
            'a1' => array('e4' => 9),
            'a4' => array('e5' => 10),
            'a2' => array('e8' => 11)
        );

        $expected = implode (PHP_EOL, array(
            'digraph "MyGraph" {',
            '"e1" -> "e2" [label = "1"];',
            '"e1" -> "e3" [label = "2"];',
            '"e1" -> "e4" [label = "3"];',
            '"e2" -> "e5" [label = "4"];',
            '"e2" -> "e6" [label = "5"];',
            '"e4" -> "e7" [label = "6"];',
            '"e4" -> "e8" [label = "7"];',
            '"a3" -> "e2" [label = "8"];',
            '"a1" -> "e4" [label = "9"];',
            '"a4" -> "e5" [label = "10"];',
            '"a2" -> "e8" [label = "11"];',
            '}'
        ));

        $list = new DirectedWeighted();
        $dot = $list->setSuccessors($successors)->dumpSuccessorsToGraphviz();
        $this->assertEquals($expected, $dot);
    }
}