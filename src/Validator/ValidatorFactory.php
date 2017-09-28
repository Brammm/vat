<?php

namespace Brammm\Vat\Validator;

class ValidatorFactory
{
    public static function createEUValidator()
    {
        return new AggregateValidator(...[
            new ValidatorAT(),
            new ValidatorBE(),
            new ValidatorBG(),
            new ValidatorCY(),
            new ValidatorCZ(),
            new ValidatorDE(),
            new ValidatorDK(),
            new ValidatorEE(),
            new ValidatorEL(),
            new ValidatorES(),
            new ValidatorFI(),
            new ValidatorFR(),
            new ValidatorGB(),
            new ValidatorHR(),
            new ValidatorHU(),
            new ValidatorIE(),
            new ValidatorIT(),
            new ValidatorLT(),
            new ValidatorLU(),
            new ValidatorLV(),
            new ValidatorMT(),
            new ValidatorNL(),
            new ValidatorPL(),
            new ValidatorPT(),
            new ValidatorRO(),
            new ValidatorSE(),
            new ValidatorSI(),
            new ValidatorSK(),
        ]);
    }
}
