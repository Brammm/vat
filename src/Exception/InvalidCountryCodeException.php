<?php

namespace Brammm\Vat\Exception;

use InvalidArgumentException;

class InvalidCountryCodeException extends InvalidArgumentException
{
    public static function forCountryCode(string $countryCode, $code = 0, $previous = null): self
    {
        return new self(
            $countryCode . ' is not a valid country code.',
            $code,
            $previous
        );
    }
}
