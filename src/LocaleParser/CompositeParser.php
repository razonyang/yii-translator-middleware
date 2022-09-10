<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware\LocaleParser;

use InvalidArgumentException;
use RazonYang\Yii\TranslatorMiddleware\LocaleParserInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * CompositeParser implements the LocaleParserInterface that supports multiple parsers.
 */
final class CompositeParser implements LocaleParserInterface
{
    private array $parsers = [];
    public function __construct(
        LocaleParserInterface ...$parsers,
    ) {
        if (!$parsers) {
            throw new InvalidArgumentException('No parser specified.');
        }

        $this->parsers = $parsers;
    }

    public function parse(ServerRequestInterface $request): ?string
    {
        foreach ($this->parsers as $parser) {
            $locale = $parser->parse($request);

            if ($locale) {
                return $locale;
            }
        }

        return null;
    }
}
