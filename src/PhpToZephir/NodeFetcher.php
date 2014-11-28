<?php

namespace PhpToZephir;

class NodeFetcher
{
    /**
     * @param  mixed $nodesCollection
     * @param  array $nodes
     * @return array
     */
    public function foreachNodes($nodesCollection, array $nodes = array())
    {
        if (is_string($nodesCollection) === false &&  method_exists($nodesCollection, 'getIterator') === true) {
            $nodesCollection = $nodesCollection->getIterator();
        } elseif (is_array($nodesCollection) === false) {
            return $nodes;
        }

        foreach ($nodesCollection as $node) {
            $nodes[] = $node;
            $nodes = $this->foreachNodes($node, $nodes);
        }

        return $nodes;
    }
}
