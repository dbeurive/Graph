<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the set of lists used to represent an undirected weighted graph.
 */

namespace dbeurive\Graph\lists;

/**
 * Class UndirectedWeighted
 *
 * This class implements the set of lists used to represent an undirected weighted graph.
 *
 * @package dbeurive\Graph\lists
 */

class UndirectedWeighted extends AbstractLists implements InterfaceLists
{
    use TraitUndirected;
    use TraitWeighted;

    /**
     * UndirectedWeighted constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initDirection();
        $this->initWeight();;
    }
}