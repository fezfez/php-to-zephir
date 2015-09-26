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
    public function foreachNodes($nodesCollection, array $nodes = array(), array $parentClass = array())
    {
        if (is_object($nodesCollection) === true && $nodesCollection instanceof NodeAbstract) {
            foreach ($nodesCollection->getSubNodeNames() as $subNodeName) {
                $parentClass[] = $this->getParentClass($nodesCollection);
                $nodes = $this->fetch($nodesCollection->$subNodeName, $nodes, $parentClass);
            }
        } elseif (is_array($nodesCollection) === true) {
            $nodes = $this->fetch($nodesCollection, $nodes, $parentClass, false);
        }

        return $nodes;
    }

    private function fetch($nodeToFetch, $nodes, $parentClass, $addSelf = false)
    {
        if (is_array($nodeToFetch) === false) {
            $nodeToFetch = array($nodeToFetch);
        }
        
        foreach ($nodeToFetch as &$node) {
            $nodes[] = array('node' => $node, 'parentClass' => $parentClass);
            if ($addSelf === true) {
                $parentClass[] = $this->getParentClass($node);
            }
            $nodes = $this->foreachNodes($node, $nodes, $parentClass);
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
