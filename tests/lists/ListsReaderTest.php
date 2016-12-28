<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\lists;

class ListsReaderTest extends \PHPUnit_Framework_TestCase
{
    private $__dataPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

    public function testLoadListsFromCsvUnweightedGraph() {

        // ### 1
        // Test the loading with the default configuration.

        $list = new Lists();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-1.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($lists['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($lists['e3'], array('e7' => null, 'e8' => null));

        // ### 2
        // Test the loading with a non default field separator.

        $list = new Lists();
        $list->setFieldSeparator(';');
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-2.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($lists['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($lists['e3'], array('e7' => null, 'e8' => null));

        // ### 3
        // Test the default line pre processor.
        // It should:
        //    - Remove any trailing sequence "\r?\n".
        //    - trim any leading and trailing spaces.

        $list = new Lists();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-3.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($lists['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($lists['e3'], array('e7' => null, 'e8' => null));

        // ### 4
        // Test the loading with non default configuration:
        //      - Set the field separator to ";" (instead of " ").
        //      - Set a new line pre processor (do not trim spaces).

        $list = new Lists();
        $list->setFieldSeparator(';');
        $list->setLinePreProcessor(function($inLine) { return preg_replace('/\r?\n$/', '', $inLine); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-4.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey(' e3', $lists);
        $this->assertEquals($lists['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($lists['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($lists[' e3'], array('e7' => null, 'e8' => null));

        // ### 5
        // Test loading with a non default vertex validator.

        $list = new Lists();
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^(e|f|g)\d+$/', $inVertexName); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-5.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(4, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('f3', $lists);
        $this->assertEquals($lists['e1'], array('e2' => null, 'e3' => null, 'e4' => null));
        $this->assertEquals($lists['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($lists['f3'], array('e7' => null, 'e8' => null));
        $this->assertEquals($lists['g515466666'], array());

        // ### 6
        // Test the loading with a non default vertex unserializer, and a non default vertex validator.
        // The unserializer will URL decode the vertices' names.

        $list = new Lists();
        $list->setVertexUnserializer(function($v) { return urldecode(utf8_decode($v)); });
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^e\d+( \|)?$/', $inVertexName); });
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-unweighted-graph-6.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1 |', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['e1 |'], array('e2 |' => null, 'e3 |' => null, 'e4 |' => null));
        $this->assertEquals($lists['e2'], array('e5' => null, 'e6' => null));
        $this->assertEquals($lists['e3'], array('e7' => null, 'e8' => null));
    }

    public function testLoadListsFromCsvWeightedGraph() {

        // ### 1
        // Test the loading with the default configuration.

        $list = new Lists();
        $list->setWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-1.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($lists['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($lists['e3'], array('e7' => 6, 'e8' => 7));

        // ### 2
        // Test the loading with a non default field separator.

        $list = new Lists();
        $list->setFieldSeparator(';');
        $list->setWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-2.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($lists['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($lists['e3'], array('e7' => 6, 'e8' => 7));

        // ### 3
        // Test the default line pre processor.
        // It should:
        //    - Remove any trailing sequence "\r?\n".
        //    - trim any leading and trailing spaces.

        $list = new Lists();
        $list->setWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-3.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($lists['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($lists['e3'], array('e7' => 6, 'e8' => 7));

        // ### 4
        // Test the loading with non default configuration:
        //      - Set the field separator to ";" (instead of " ").
        //      - Set a new line pre processor (do not trim spaces).

        $list = new Lists();
        $list->setFieldSeparator(';');
        $list->setLinePreProcessor(function($inLine) { return preg_replace('/\r?\n$/', '', $inLine); });
        $list->setWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-4.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('  e1', $lists);
        $this->assertArrayHasKey('  e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['  e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($lists['  e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($lists['e3'], array('e7' => 6, 'e8' => 7));

        // ### 5
        // Test the loading with non default configuration:
        //      - Set the weight indicator to '-' (instead of ":").
        //      - Set a non default vertex validator.

        $list = new Lists();
        $list->setWeightIndicator('-');
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^(e|f|g)\d+$/', $inVertexName); });
        $list->setWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-5.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(4, array_keys($lists));
        $this->assertArrayHasKey('e1', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('f3', $lists);
        $this->assertArrayHasKey('g515466666', $lists);
        $this->assertEquals($lists['e1'], array('e2' => 1, 'e3' => 2, 'e4' => 3));
        $this->assertEquals($lists['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($lists['f3'], array('e7' => 6, 'e8' => 7));
        $this->assertEquals($lists['g515466666'], array());

        // ### 6
        // Test the loading with a non default vertex unserializer, and a non default vertex validator.
        // The unserializer will URL decode the vertices' names.

        $list = new Lists();
        $list->setVertexUnserializer(function($v) { return urldecode(utf8_decode($v)); });
        $list->setVertexValidator(function($inVertexName) { return 1 === preg_match('/^e\d+( \|)?$/', $inVertexName); });
        $list->setWeighted();
        $csvPath = $this->__dataPath . DIRECTORY_SEPARATOR . 'r-weighted-graph-6.csv';
        $lists = $list->loadListsFromCsv($csvPath);
        $this->assertCount(3, array_keys($lists));
        $this->assertArrayHasKey('e1 |', $lists);
        $this->assertArrayHasKey('e2', $lists);
        $this->assertArrayHasKey('e3', $lists);
        $this->assertEquals($lists['e1 |'], array('e2 |' => 1, 'e3 |' => 2, 'e4 |' => 3));
        $this->assertEquals($lists['e2'], array('e5' => 4, 'e6' => 5));
        $this->assertEquals($lists['e3'], array('e7' => 6, 'e8' => 7));
    }
}