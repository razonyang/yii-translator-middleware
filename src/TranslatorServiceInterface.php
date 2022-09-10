<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Translator\TranslatorInterface;

interface TranslatorServiceInterface
{
    /**
     * Returns the translator instance from the given request atrributes.
     *
     * @param array $atrributes
     *
     * @return TranslatorInterface
     */
    public function fromAttributes(array $atrributes): TranslatorInterface;

    /**
     * Wrap the given request and returns a new request instance with the translator.
     *
     * @param ServerRequestInterface $request
     *
     * @return ServerRequestInterface the new request instance with translator.
     */
    public function wrap(ServerRequestInterface $request): ServerRequestInterface;
}
