<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RazonYang\Yii\TranslatorMiddleware\Exception\InvalidTranslatorExcepction;
use RazonYang\Yii\TranslatorMiddleware\Exception\TranslatorNotFoundExcepction;
use Yiisoft\Translator\Translator;
use Yiisoft\Translator\TranslatorInterface;

class TranslatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LocaleParserInterface $localeParser,
        private TranslatorInterface $translator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $locale = $this->localeParser->parse($request) ?? $this->translator->getLocale();
        $request = $request->withAttribute(self::class, $this->translator->withLocale($locale));

        return $handler->handle($request);
    }

    /**
     * Returns the translator instance from given request.
     *
     * @see getTranslatorFromAttributes
     */
    public static function getTranslator(ServerRequestInterface $request): TranslatorInterface
    {
        return self::getTranslatorByAttributes($request->getAttributes());
    }

    /**
     * Returns the translator instance from given request attributes.
     *
     * @throws TranslatorNotFoundExcepction if no translator instace found.
     * @throws InvalidTranslatorExcepction if the translator instace with wrong type.
     *
     * @return TranslatorInterface
     */
    public static function getTranslatorByAttributes(array $attributes): TranslatorInterface
    {
        if (!isset($attributes[self::class])) {
            throw new TranslatorNotFoundExcepction();
        }

        $t = $attributes[self::class];
        if (!($t instanceof TranslatorInterface)) {
            throw new InvalidTranslatorExcepction();
        }

        return $t;
    }
}
