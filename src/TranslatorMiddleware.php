<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TranslatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TranslatorServiceInterface $translatorService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($this->translatorService->wrap($request));
    }
}
