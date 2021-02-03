<?php

namespace App\Form\Type;


abstract class CurrencyTypeEnum
{
    const CURRENCY_USD    = "USD";
    const CURRENCY_EUR = "EUR";

    /** @var array user friendly named type */
    protected static $currencyName = [
        self::CURRENCY_USD    => 'Dolar',
        self::CURRENCY_EUR => 'Euro',
    ];

    /**
     * @param  string $currencyShortName
     * @return string
     */
    public static function getCurrencyName($currencyShortName)
    {
        if (!isset(static::$currencyName[$currencyShortName])) {
            return "Unknown type ($currencyShortName)";
        }

        return static::$currencyName[$currencyShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::CURRENCY_USD,
            self::CURRENCY_EUR
        ];
    }
}
