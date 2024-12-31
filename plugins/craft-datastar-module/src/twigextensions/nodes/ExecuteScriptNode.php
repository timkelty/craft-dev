<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\datastar\twigextensions\nodes;

use putyourlightson\datastar\services\SseService;
use Twig\Compiler;
use Twig\Node\Node;

class ExecuteScriptNode extends Node
{
    use NodeTrait;

    /**
     * @uses SseService::executeScript()
     */
    public function compile(Compiler $compiler): void
    {
        $this->compileWithOptions($compiler, 'executeScript');
    }
}
