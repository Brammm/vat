<?php

namespace Brammm\Vat\Validator;

interface Validator
{
    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate(string $vatNumber): bool;
}
