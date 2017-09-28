<?php

namespace Brammm\Vat\Exception;

use InvalidArgumentException;

class InvalidVatNumberException extends InvalidArgumentException
{
    public static function forVatNumber(string $vatNumber, $code = 0, $previous = null): self
    {
        return new self(
            $vatNumber . ' is not a valid VAT number.',
            $code,
            $previous
        );
    }
}
