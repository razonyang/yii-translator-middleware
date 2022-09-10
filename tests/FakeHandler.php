<?php

namespace RazonYang\Yii\TranslatorMiddleware\Tests;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FakeHandler implements RequestHandlerInterface
{
    public function __construct(
        private $callback,
        private int $statusCode = 200,
        private string $body = 'OK',
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        call_user_func($this->callback, $request);
        return new Response($this->statusCode, [], $this->body);
    }
}
