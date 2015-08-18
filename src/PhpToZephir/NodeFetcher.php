<?php

namespace PhpToZephir;

use PhpParser\NodeAbstract;

class NodeFetcher
{
    /**
     * @param mixed  $nodesCollection
     * @param array  $nodes
     * @param string $parentClass
     *
     * @return array
     */
    public function foreachNodes($nodesCollection, array $nodes = array(), $parentClass = '')
    {
        if (is_object($nodesCollection) === true && $nodesCollection instanceof NodeAbstract) {
            foreach ($nodesCollection->getSubNodeNames() as $subNodeName) {
                $nodes = $this->fetch($nodesCollection->$subNodeName, $nodes, $this->getParentClass($nodesCollection));
            }
        } elseif (is_array($nodesCollection) === true) {
            $nodes = $this->fetch($nodesCollection, $nodes, $parentClass);
        }

        return $nodes;
    }

    private function fetch($nodeToFetch, $nodes, $parentClass)
    {
        if (is_array($nodeToFetch) === false) {
            $nodeToFetch = array($nodeToFetch);
        }
        
        foreach ($nodeToFetch as &$node) {
            $nodes[] = array('node' => $node, 'parentClass' => $parentClass);
            $nodes = $this->foreachNodes($node, $nodes, $this->getParentClass($node));
        }

        return $nodes;
    }

    /**
     * @param mixed $node
     * @return string
     */
    private function getParentClass($node)
    {
        return is_object($node) ? get_class($node) : '';
    }
}
