<?php

/**
 * use libraries
 */

use Illuminate\Support\Carbon;

/**
 * use models
 *
 * @return void
 */


/** */


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
