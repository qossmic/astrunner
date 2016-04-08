<?php 

namespace SensioLabs\AstRunner\AstParser\NikicPhpParser;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitor;
use SensioLabs\AstRunner\AstMap\AstInherit;
use SensioLabs\AstRunner\AstMap\AstInheritInterface;

class AstHelper
{
    static $cacheFindClassLikeNodes = [];

    public static function findClassLikeNodes($nodes)
    {
        $collectedNodes = [];

        foreach ($nodes as $i => &$node) {
            if ($node instanceof Node\Stmt\ClassLike) {
                $collectedNodes[] = $node;
            } elseif ($node instanceof Use_) {
                continue;
            } elseif (is_array($node)) {
                $collectedNodes = array_merge(static::findClassLikeNodes($node, false), $collectedNodes);
            } elseif ($node instanceof Node) {
                $collectedNodes = array_merge(static::findClassLikeNodes(
                    static::getSubNodes($node),
                    false
                ), $collectedNodes);
            }
        }

        return $collectedNodes;
    }

    /**
     * @param Node\Stmt\ClassLike $klass
     * @return AstInheritInterface[]
     */
    public static function findInheritances(Node\Stmt\ClassLike $klass)
    {
        $buffer = [];

        if ($klass instanceof Class_ && $klass->namespacedName instanceof Name) {

            if ($klass->extends instanceof Name) {
                $buffer[] = AstInherit::newExtends(
                    $klass->extends->toString(),
                    $klass->extends->getLine()
                );
            }

            if (!empty($klass->implements)) {
                foreach ($klass->implements as $impl) {

                    if (!$impl instanceof Name) {
                        continue;
                    }

                    $buffer[] = AstInherit::newImplements(
                        $impl->toString(),
                        $impl->getLine()
                    );
                }
            }
        }

        if ($klass instanceof Trait_ || $klass instanceof Class_) {
            foreach ($klass->stmts as $traitUses) {
                if (!$traitUses instanceof Node\Stmt\TraitUse) {
                    continue;
                }

                foreach ($traitUses->traits as $traitUsage) {
                    if (!$traitUsage instanceof FullyQualified) {
                        continue;
                    }

                    $buffer[] = AstInherit::newUses(
                        $traitUsage->toString(),
                        $traitUsage->getLine()
                    );
                }
            }
        }

        if ($klass instanceof Interface_ && isset($klass->namespacedName) && $klass->namespacedName instanceof Name) {
            foreach ($klass->extends as $extends) {
                $buffer[] = AstInherit::newExtends(
                    $extends->toString(),
                    $extends->getLine()
                );
            }
        }

        return $buffer;
    }

    public static function getSubNodes(Node $node)
    {
        $subnodes = [];
        foreach ($node->getSubNodeNames() as $name) {
            $subnodes[] = $node->$name;
        }
        return $subnodes;
    }
}
