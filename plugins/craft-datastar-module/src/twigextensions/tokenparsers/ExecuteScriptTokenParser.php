<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\datastar\twigextensions\tokenparsers;

use putyourlightson\datastar\twigextensions\nodes\ExecuteScriptNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class ExecuteScriptTokenParser extends AbstractTokenParser
{
    /**
     * @inheritdoc
     */
    public function getTag(): string
    {
        return 'executescript';
    }

    /**
     * @inheritdoc
     */
    public function parse(Token $token): ExecuteScriptNode
    {
        $lineno = $token->getLine();
        $parser = $this->parser;
        $stream = $parser->getStream();
        $expressionParser = $parser->getExpressionParser();

        $nodes = [];

        if ($stream->test(Token::NAME_TYPE, 'with')) {
            $stream->next();
            $nodes['options'] = $expressionParser->parseExpression();
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        $nodes['body'] = $parser->subparse([$this, 'decideExecuteScriptEnd'], true);

        $stream->expect(Token::BLOCK_END_TYPE);

        return new ExecuteScriptNode($nodes, [], $lineno, $this->getTag());
    }

    public function decideExecuteScriptEnd(Token $token): bool
    {
        return $token->test('endexecutescript');
    }
}
