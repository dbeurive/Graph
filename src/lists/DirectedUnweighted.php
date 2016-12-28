<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).


/**
 * This file implements the set of adjacency lists used to represent a directed unweighted graph.
 */

namespace dbeurive\Graph\lists;

/**
 * Class DirectedUnweighted
 *
 * This class implements the set of adjacency lists used to represent a directed unweighted graph.
 *
 * @package dbeurive\Graph\lists
 */

class DirectedUnweighted extends AbstractLists implements InterfaceLists
{
    use TraitDirected;
    use TraitUnweighted;

    /**
     * DirectedUnweighted constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initDirection();
        $this->initWeight();
    }
}