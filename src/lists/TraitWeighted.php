<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This file implements the required initialisation process, and the required methods for a "weighted set of adjacency lists".
 */

namespace dbeurive\Graph\lists;

/**
 * Class TraitWeighted
 *
 * This trait implements the required initialisation process, and the required methods for a "weighted set of adjacency lists".
 *
 * @package dbeurive\Graph\lists
 */
trait TraitWeighted
{
    /**
     * @see InterfaceLists
     */
    public function initWeight() {
        /** @var $this AbstractLists */
        $this->_setWeighted();
    }

    /**
     * @see InterfaceLists
     */
    public function isWeighted() {
        return true;
    }

    // --------------------------------------------------

    /**
     * Set the string used to prefix the weight of a vertex.
     * Please see the documentation for the method AbstractLists::_loadListsFromCsv() for details.
     * @param string $inWeightIndicator The string used to prefix the weight of a vertex. Default is: ":".
     * @return $this
     * @see AbstractLists::_loadListsFromCsv()
     */
    public function setWeightIndicator($inWeightIndicator) {
        return $this->_setWeightIndicator($inWeightIndicator);
    }
}