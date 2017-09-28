<?php

namespace Brammm\Vat\Validator;

trait ProvidesIsEven
{
    protected function isEven(int $val): bool
    {
        return ($val / 2 == floor($val / 2)) ? true : false;
    }
}
