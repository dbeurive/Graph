<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

namespace dbeurive\GraphTests\lists;

class Lists extends \dbeurive\Graph\lists\AbstractLists
{
    public function setWeighted() {
        return $this->_setWeighted();
    }

    public function setUnweighted() {
        return $this->_setUnweighted();
    }

    public function setDirected() {
        return $this->_setDirected();
    }

    public function setUndirected() {
        return $this->_setUndirected();
    }

    public function setWeightIndicator($inWeightIndicator) {
        return $this->_setWeightIndicator($inWeightIndicator);
    }

    public function complete(array &$inLists) {
        return $this->_complete($inLists);
    }

    public function reverse(array &$inLists, $inOptNeedComplete=false) {
        return $this->_reverse($inLists, $inOptNeedComplete);
    }

    public function toGraphviz(array &$inLists, $inOptDirected=true, array $inNodesSpecifications=array(), array $inOptEdgesSpecifications=array(), $inOptName='MyGraph') {
        return $this->_toGraphviz($inLists, $inOptDirected, $inNodesSpecifications, $inOptEdgesSpecifications, $inOptName);
    }

    public function loadListsFromCsv($inPath) {
        return $this->_loadListsFromCsv($inPath);
    }

    public function dumpListToCsv($inPath, array $inLists) {
        return $this->_dumpListToCsv($inPath, $inLists);
    }
}