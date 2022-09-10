<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware;

use Psr\Http\Message\ServerRequestInterface;
use RazonYang\Yii\TranslatorMiddleware\Exception\InvalidTranslatorExcepction;
use RazonYang\Yii\TranslatorMiddleware\Exception\TranslatorNotFoundExcepction;
use Yiisoft\Mutex\Synchronizer;
use Yiisoft\Translator\TranslatorInterface;

class TranslatorService implements TranslatorServiceInterface
{
    private array $translators = [];

    public function __construct(
        private Synchronizer $synchronizer,
        private LocaleParserInterface $localeParser,
        private TranslatorInterface $translator,
        private string $atrribute = 'translator',
    ) {
        $this->translators = [
            $translator->getLocale() => $translator,
        ];
    }

    public function get(ServerRequestInterface $request): TranslatorInterface
    {
        return $this->fromAttributes($request->getAttributes());
    }

    public function fromAttributes(array $atrributes): TranslatorInterface
    {
        if (!isset($atrributes[$this->atrribute])) {
            throw new TranslatorNotFoundExcepction();
        }

        $t = $atrributes[$this->atrribute];
        if (!($t instanceof TranslatorInterface)) {
            throw new InvalidTranslatorExcepction();
        }

        return $t;
    }

    /**
     * @return ServerRequestInterface
     */
    public function wrap(ServerRequestInterface $request): ServerRequestInterface
    {
        $locale = $this->localeParser->parse($request) ?? $this->translator->getLocale();
        $translator = $this->getByLocale($locale);
        return $request->withAttribute($this->atrribute, $translator);
    }

    private function getByLocale(string $locale): TranslatorInterface
    {
        if (!isset($this->translators[$locale])) {
            $this->createTranslator($locale);
        }

        return $this->translators[$locale];
    }

    private function createTranslator(string $locale): void
    {
        $lock = sprintf("%s::createTranslator", self::class);
        $this->synchronizer->execute(
            $lock,
            function () use ($locale) {
                if (!isset($this->translators[$locale])) {
                    $this->translators[$locale] = $this->translator->withLocale($locale);
                }
            },
            10
        );
    }
}
