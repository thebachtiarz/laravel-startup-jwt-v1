<?php

/**
 * use libraries
 */

use Illuminate\Support\Carbon;

/**
 * use models
 */


/** */

/**
 * get date time now for database
 *
 * @return void
 */
function Carbon_DBtimeNow()
{
    return Carbon::now()->toDateTimeString();
}

function Carbon_AccessTimeNow()
{
    return Carbon::now()->toCookieString();
}

function Carbon_HumanTimeNow()
{
    return Carbon::now()->format('l, j F Y H:i:s');
}

/**
 * convert date time to interval time
 *
 * @param string $datetime
 * @return void
 */
function Carbon_diffForHumans($datetime)
{
    return Carbon::parse($datetime)->diffForHumans();
}

/**
 * convert currency rupiah id
 *
 * @param int $currency
 * @return void
 */
function ID_currency($currency)
{
    return ($currency != 0) ? 'Rp. ' . number_format($currency, 0, ".", ".") : 'Rp. 0';
}
