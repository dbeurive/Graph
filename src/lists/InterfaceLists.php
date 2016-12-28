<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

/**
 * This interface defines the methods used during the lists initialisations.
 */

namespace dbeurive\Graph\lists;

/**
 * Interface InterfaceLists
 *
 * This interface defines the methods used during the lists initialisations.
 * Please note that these methods are implemented within traits.
 * Traits are used to simulate multiple inheritance.
 *
 * @package dbeurive\Graph\lists
 */

interface InterfaceLists
{
    /**
     * Set the direction property.
     */
    public function initDirection();

    /**
     * Set the weight property.
     */
    public function initWeight();

    /**
     * Test whether the graph is directed or not.
     * @return bool Return true if the graph is directed.
     *         Return false otherwise.
     */
    public function isDirected();

    /**
     * Test whether the graph is weighted or not.
     * @return bool Return true if the graph is weighted.
     *         Return false otherwise.
     */
    public function isWeighted();
}