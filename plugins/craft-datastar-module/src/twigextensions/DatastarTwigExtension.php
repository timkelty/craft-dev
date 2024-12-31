<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\datastar\twigextensions;

use putyourlightson\datastar\twigextensions\tokenparsers\ExecuteScriptTokenParser;
use putyourlightson\datastar\twigextensions\tokenparsers\FragmentTokenParser;
use putyourlightson\datastar\variables\DatastarVariable;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class DatastarTwigExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @inerhitdoc
     */
    public function getGlobals(): array
    {
        return [
            'datastar' => new DatastarVariable(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTokenParsers(): array
    {
        return [
            new FragmentTokenParser(),
            new ExecuteScriptTokenParser(),
        ];
    }
}
