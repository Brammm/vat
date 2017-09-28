<?php

namespace Brammm\Vat\Tests\Validator;

use Brammm\Vat\Validator\Validator;

class ValidValidator implements Validator
{
    /**
     * @inheritdoc
     */
    public function validate(string $countryCode, string $vatNumber): void
    {
        return;
    }

    /**
     * @inheritdoc
     */
    public function supportsCountry(string $countryCode): bool
    {
        return true;
    }
}
