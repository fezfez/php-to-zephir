<?php

namespace PhpToZephir;

class NodeFetcher
{
    public function foreachNodes($nodesCollection, array $nodes = array())
    {
        if (is_array($nodesCollection) === true) {
            $nodesCollection = $nodesCollection;
        } elseif (is_string($nodesCollection) === false &&  method_exists($nodesCollection, 'getIterator') === true) {
            $nodesCollection = $nodesCollection->getIterator();
        } else {
            return $nodes;
        }

        foreach ($nodesCollection as $node) {
            $nodes[] = $node;
            $nodes = $this->foreachNodes($node, $nodes);
        }

        return $nodes;
    }
}
