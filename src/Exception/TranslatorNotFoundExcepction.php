<?php

declare(strict_types=1);

namespace RazonYang\Yii\TranslatorMiddleware\Exception;

use Exception;
use Throwable;

final class TranslatorNotFoundExcepction extends Exception
{
    public function __construct(string $message = 'No translator instance found.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
