<?php

declare(strict_types=1);


define("BIN_LOOKUP_URL", "https://lookup.binlist.net");
define("BIN_LOOKUP_AUTH_USERNAME", "");
define("BIN_LOOKUP_AUTH_PASSWORD", "");


define("EXCHANGES_RATES_URL", "https://api.exchangeratesapi.io/latest");
define("EXCHANGES_RATES_AUTH_USERNAME", "");
define("EXCHANGES_RATES_AUTH_PASSWORD", "");


define("EU_ISSUED_COMMISSION_RATE", 0.01);
define("NON_EU_ISSUED_COMMISSION_RATE", 0.02);

use PHPUnit\Framework\TestCase;

class DemoTest extends TestCase
{
    public function testCommission()
    {

        $commission = new \Commissions\Commissions();
        $commission = $commission->calculateCommissions(__DIR__ . '/input.txt');

        $commision_array = ( explode("\n", $commission) );
        array_pop($commision_array);
        $this->assertEquals(['1', '0.45', '1.66', '2.3', '44.76'], $commision_array);

    }
}