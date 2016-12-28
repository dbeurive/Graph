<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\lists;

class ListsConverterTest extends \PHPUnit_Framework_TestCase
{
    static private $__outputPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'output';
    static private $__imagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'images';
    static private $__dots = array();

    static public function tearDownAfterClass() {

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'ListsConverterTest.sh';
        file_put_contents($path, implode(PHP_EOL, self::$__dots), FILE_APPEND);
        chmod($path, 0755);
    }

    public function testCompleteDirectedUnweighted() {

        $lists = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('e5' => null, 'e6' => null),
            'e4' => array('e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'a4' => array('e5' => null),
            'a2' => array('e8' => null)
        );

        $expected = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('e5' => null, 'e6' => null),
            'e4' => array('e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'a4' => array('e5' => null),
            'a2' => array('e8' => null),
            'e6' => array(),
            'e7' => array(),
            'e3' => array(),
            'e5' => array(),
            'e8' => array()
        );

        $converter = new Lists();
        $converter->complete($lists);

        $this->assertCount(count($expected), $lists);

        /**
         * @var string $_key
         * @var array $_value
         */
        foreach ($expected as $_key => $_value) {
            $this->assertArrayHasKey($_key, $lists);
            $this->assertCount(count($_value), $lists[$_key]);
            /**
             * @var string $__key
             * @var null $_null
             */
            foreach ($_value as $__key => $_null) {
                $this->assertArrayHasKey($__key, $lists[$_key]);
            }
        }
    }

    public function testCompleteDirectedWeighted() {

        $lists = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('e5' => 4, 'e6' => 5),
            'e4' => array('e7' => 6, 'e8' => 7),
            'a3' => array('e2' => 8),
            'a1' => array('e4' => 9),
            'a4' => array('e5' => 10),
            'a2' => array('e8' => 11)
        );

        $expected = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('e5' => 4, 'e6' => 5),
            'e4' => array('e7' => 6, 'e8' => 7),
            'a3' => array('e2' => 8),
            'a1' => array('e4' => 9),
            'a4' => array('e5' => 10),
            'a2' => array('e8' => 11),
            'e6' => array(),
            'e7' => array(),
            'e3' => array(),
            'e5' => array(),
            'e8' => array()
        );

        $converter = new Lists();
        $converter->complete($lists);

        $this->assertCount(count($expected), $lists);

        /**
         * @var string $_key
         * @var array $_value
         */
        foreach ($expected as $_key => $_value) {
            $this->assertArrayHasKey($_key, $lists);
            $this->assertCount(count($_value), $lists[$_key]);
            /**
             * @var string $__key
             * @var null $_weight
             */
            foreach ($_value as $__key => $_weight) {
                $this->assertArrayHasKey($__key, $lists[$_key]);
                $this->assertEquals($_weight, $lists[$_key][$__key]);
            }
        }
    }

    public function testCompleteUndirectedUnweighted() {

        $lists = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('a3' => null, 'e1' => null, 'e6' => null, 'e5' => null),
            'e4' => array('a1' => null, 'e1' => null, 'e7' => null, 'e8' => null),
            'e5' => array('a4' => null, 'e2' => null),
            'e8' => array('e4' => null, 'a2' => null)
        );

        $expected = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('a3' => null, 'e1' => null, 'e6' => null, 'e5' => null),
            'e3' => array('e1' => null),
            'e4' => array('a1' => null, 'e1' => null, 'e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'e5' => array('a4' => null, 'e2' => null),
            'a4' => array('e5' => null),
            'e6' => array('e2' => null),
            'e7' => array('e4' => null),
            'e8' => array('e4' => null, 'a2' => null),
            'a2' => array('e8' => null)
        );

        $converter = new Lists();
        $converter->setUndirected();
        $converter->complete($lists);

        $this->assertCount(count($expected), $lists);

        /**
         * @var string $_key
         * @var array $_value
         */
        foreach ($expected as $_key => $_value) {
            $this->assertArrayHasKey($_key, $lists);
            $this->assertCount(count($_value), $lists[$_key]);
            /**
             * @var string $__key
             * @var null $_null
             */
            foreach ($_value as $__key => $_null) {
                $this->assertArrayHasKey($__key, $lists[$_key]);
            }
        }
    }

    public function testCompleteUndirectedWeighted() {

        $lists = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('a3' => 4, 'e1' => 1, 'e6' => 6, 'e5' => 7),
            'e4' => array('a1' => 8, 'e1' => 3, 'e7' => 10, 'e8' => 11),
            'e5' => array('a4' => 12, 'e2' => 7),
            'e8' => array('e4' => 11, 'a2' => 15)
        );

        // Note: make sure that A -> B has the same weight that B -> A.
        //       This is because the graph is undirected.

        $expected = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('a3' => 4, 'e1' => 1, 'e6' => 6, 'e5' => 7),
            'e3' => array('e1' => 2),
            'e4' => array('a1' => 8, 'e1' => 3, 'e7' => 10, 'e8' => 11),
            'a3' => array('e2' => 4),
            'a1' => array('e4' => 8),
            'e5' => array('a4' => 12, 'e2' => 7),
            'a4' => array('e5' => 12),
            'e6' => array('e2' => 6),
            'e7' => array('e4' => 10),
            'e8' => array('e4' => 11, 'a2' => 15),
            'a2' => array('e8' => 15)
        );

        // var_dump($lists); exit(0);

        $converter = new Lists();
        $converter->setUndirected();
        $converter->complete($lists);

        $this->assertCount(count($expected), $lists);

        /**
         * @var string $_key
         * @var array $_value
         */
        foreach ($expected as $_key => $_value) {
            $this->assertArrayHasKey($_key, $lists);
            $this->assertCount(count($_value), $lists[$_key]);
            /**
             * @var string $__key
             * @var null $_weight
             */
            foreach ($_value as $__key => $_weight) {
                $this->assertArrayHasKey($__key, $lists[$_key]);
                $this->assertEquals($_weight, $lists[$_key][$__key]);
            }
        }
    }

    // Note that reversion does not make sense for undirected graphs.
    public function testReverseUnweighted() {

        $lists = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('e5' => null, 'e6' => null),
            'e4' => array('e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'a4' => array('e5' => null),
            'a2' => array('e8' => null)
        );

        $expected = array(
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

        $converter = new Lists();
        $reversed = $converter->reverse($lists);

        $this->assertCount(count($expected), $reversed);

        /**
         * @var string $_key
         * @var array $_value
         */
        foreach ($expected as $_key => $_value) {
            $this->assertArrayHasKey($_key, $reversed);
            $this->assertCount(count($_value), $reversed[$_key]);
            /**
             * @var string $__key
             * @var null $_null
             */
            foreach ($_value as $__key => $_null) {
                $this->assertArrayHasKey($__key, $reversed[$_key]);
            }
        }
    }

    // Note that reversion does not make sense for undirected graphs.
    public function testReverseWeighted() {

        $lists = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('e5' => 4, 'e6' => 5),
            'e4' => array('e7' => 6, 'e8' => 7),
            'a3' => array('e2' => 8),
            'a1' => array('e4' => 9),
            'a4' => array('e5' => 10),
            'a2' => array('e8' => 11)
        );

        $expected = array(
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

        $converter = new Lists();
        $reversed = $converter->reverse($lists);

        $this->assertCount(count($expected), $reversed);

        /**
         * @var string $_key
         * @var array $_value
         */
        foreach ($expected as $_key => $_value) {
            $this->assertArrayHasKey($_key, $reversed);
            $this->assertCount(count($_value), $reversed[$_key]);
            /**
             * @var string $__key
             * @var null $_weight
             */
            foreach ($_value as $__key => $_weight) {
                $this->assertArrayHasKey($__key, $reversed[$_key]);
                $this->assertEquals($_weight, $reversed[$_key][$__key]);
           }
        }
    }

    public function testToGraphvizDirectedUnweighted() {

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'testToGraphvizDirectedUnweighted.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'testToGraphvizDirectedUnweighted';

        $lists = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('e5' => null, 'e6' => null),
            'e4' => array('e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'a4' => array('e5' => null),
            'a2' => array('e8' => null)
        );

        $expected = implode (PHP_EOL, array(
            'digraph "MyGraph" {',
            '"e1" -> "e2";',
            '"e1" -> "e3";',
            '"e1" -> "e4";',
            '"e2" -> "e5";',
            '"e2" -> "e6";',
            '"e4" -> "e7";',
            '"e4" -> "e8";',
            '"a3" -> "e2";',
            '"a1" -> "e4";',
            '"a4" -> "e5";',
            '"a2" -> "e8";',
            '}'
        ));

        $converter = new Lists();

        $dot = $converter->toGraphviz($lists);
        $this->assertEquals($expected, $dot);
        file_put_contents($path, $dot);

        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";
    }

    public function testToGraphvizDirectedWeighted() {

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'testToGraphvizDirectedWeighted.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'testToGraphvizDirectedWeighted';

        $lists = array(
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

        $converter = new Lists();

        $dot = $converter->toGraphviz($lists);
        $this->assertEquals($expected, $dot);
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";
    }

    public function testToGraphvizUndirectedUnweighted() {

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'testToGraphvizUndirectedUnweighted.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'testToGraphvizUndirectedUnweighted';

        $lists = array(
            'e1' => array('e2' => null, 'e3' => null, 'e4' => null),
            'e2' => array('a3' => null, 'e1' => null, 'e6' => null, 'e5' => null),
            'e3' => array('e1' => null),
            'e4' => array('a1' => null, 'e1' => null, 'e7' => null, 'e8' => null),
            'a3' => array('e2' => null),
            'a1' => array('e4' => null),
            'e5' => array('a4' => null, 'e2' => null),
            'a4' => array('e5' => null),
            'e6' => array('e2' => null),
            'e7' => array('e4' => null),
            'e8' => array('e4' => null, 'a2' => null),
            'a2' => array('e8' => null)
        );

        $expected = implode (PHP_EOL, array(
            'graph "MyGraph" {',
            '"e1" -- "e2";',
            '"e1" -- "e3";',
            '"e1" -- "e4";',
            '"e2" -- "a3";',
            '"e2" -- "e6";',
            '"e2" -- "e5";',
            '"e4" -- "a1";',
            '"e4" -- "e7";',
            '"e4" -- "e8";',
            '"e5" -- "a4";',
            '"e8" -- "a2";',
            '}'
        ));

        $converter = new Lists();

        $dot = $converter->toGraphviz($lists, false);
        $this->assertEquals($expected, $dot);
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";
        // exit(0);
    }

    public function testToGraphvizUndirectedWeighted() {

        $path = self::$__outputPath . DIRECTORY_SEPARATOR . 'testToGraphvizUndirectedWeighted.dot';
        $image = self::$__imagePath . DIRECTORY_SEPARATOR . 'testToGraphvizUndirectedWeighted';

        $lists = array(
            'e1' => array('e2' => 1, 'e3' => 2, 'e4' => 3),
            'e2' => array('a3' => 4, 'e1' => 1, 'e6' => 6, 'e5' => 7),
            'e3' => array('e1' => 2),
            'e4' => array('a1' => 8, 'e1' => 3, 'e7' => 10, 'e8' => 11),
            'a3' => array('e2' => 4),
            'a1' => array('e4' => 8),
            'e5' => array('a4' => 12, 'e2' => 7),
            'a4' => array('e5' => 12),
            'e6' => array('e2' => 6),
            'e7' => array('e4' => 10),
            'e8' => array('e4' => 11, 'a2' => 15),
            'a2' => array('e8' => 15)
        );

        $expected = implode (PHP_EOL, array(
            'graph "MyGraph" {',
            '"e1" -- "e2" [label = "1"];',
            '"e1" -- "e3" [label = "2"];',
            '"e1" -- "e4" [label = "3"];',
            '"e2" -- "a3" [label = "4"];',
            '"e2" -- "e6" [label = "6"];',
            '"e2" -- "e5" [label = "7"];',
            '"e4" -- "a1" [label = "8"];',
            '"e4" -- "e7" [label = "10"];',
            '"e4" -- "e8" [label = "11"];',
            '"e5" -- "a4" [label = "12"];',
            '"e8" -- "a2" [label = "15"];',
            '}'
        ));

        $converter = new Lists();

        $dot = $converter->toGraphviz($lists, false);
        $this->assertEquals($expected, $dot);
        file_put_contents($path, $dot);
        self::$__dots[] = "dot -Tgif -o \"$image.gif\" \"$path\"";
    }
}