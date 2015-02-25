<?php

namespace PhpToZephir;

class NodeFetcher
{
    /**
     * @param mixed  $nodesCollection
     * @param array  $nodes
     * @param string $parentClass
     *
     * @return array
     */
    public function foreachNodes($nodesCollection, array $nodes = array(), $parentClass = "")
    {
        if (is_string($nodesCollection) === false &&  method_exists($nodesCollection, 'getIterator') === true) {
            $nodesCollection = $nodesCollection->getIterator();
        } elseif (is_array($nodesCollection) === false) {
            return $nodes;
        }

        foreach ($nodesCollection as $node) {
            $nodes[] = array('node' => $node, 'parentClass' => $parentClass);
            $nodes = $this->foreachNodes($node, $nodes, is_object($node) ? get_class($node) : '');
        }

        return $nodes;
    }
}
