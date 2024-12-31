<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\datastar\twigextensions\nodes;

use putyourlightson\datastar\Datastar;
use putyourlightson\datastar\services\SseService;
use Twig\Compiler;
use Twig\Node\Node;

class FragmentNode extends Node
{
    use NodeTrait;

    /**
     * @uses SseService::mergeFragments
     */
    public function compile(Compiler $compiler): void
    {
        $selector = $this->hasNode('selector') ? $this->getNode('selector') : null;

        if ($selector !== null) {
            $this->removeFragments($compiler, $selector);
        } else {
            $this->compileWithOptions($compiler, 'mergeFragments');
        }
    }

    /**
     * @uses SseService::removeFragments
     */
    private function removeFragments(Compiler $compiler, Node $selector): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write("ob_start();\n")
            ->write("\$selector = ")
            ->subcompile($selector)
            ->raw(";\n")
            ->write(Datastar::class . "::getInstance()->sse->removeFragments(\$selector);\n");
    }
}
