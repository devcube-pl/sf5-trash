<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AntiFlood extends Constraint
{
    public $message = 'Poczekaj na komentarz innej osoby';

    /**
     * Limit komentarzy
     * @var int
     */
    public $limit = 1;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
