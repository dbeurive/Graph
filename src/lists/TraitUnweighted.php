<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the required initialisation process, and the required methods for an "unweighted set of adjacency lists".
 */

namespace dbeurive\Graph\lists;

/**
 * Class TraitUnweighted
 *
 * This trait implements the required initialisation process, and the required methods for an "unweighted set of adjacency lists".
 *
 * @package dbeurive\Graph\lists
 */
trait TraitUnweighted
{
    /**
     * @see InterfaceLists
     */
    public function initWeight() {
        /** @var $this AbstractLists */
        $this->_setUnweighted();
    }

    /**
     * @see InterfaceLists
     */
    public function isWeighted() {
        return false;
    }
}