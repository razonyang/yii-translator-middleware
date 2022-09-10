<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware;

use Psr\Http\Message\ServerRequestInterface;

/**
 * LocaleParserInterface is an interface that parse the locale from server request.
 */
interface LocaleParserInterface
{
    /**
     * Returns the locale from the given request.
     *
     * @return string|null returns null if no locale specified.
     */
    public function parse(ServerRequestInterface $request): ?string;
}
