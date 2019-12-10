<?php

/**
 * use libraries
 */

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * use models
 *
 * @return void
 */

use App\Models\Auth\User;

/** */

/**
 * handler template theme asset
 *
 * @return void
 */
# online version
function online_asset()
{
    // return 'http://bachtiars.com/AdminLTE-3.0.1/';
    return offline_asset();
}
# offline version
function offline_asset()
{
    return asset('AdminLTE-3.0.1');
}

/**
 * icon apps
 *
 * @return void
 */
function apps_icon()
{
    // return online_asset() . '/dist/img/AdminLTELogo.png';
    return offline_asset() . '/dist/img/AdminLTELogo.png';
}

/**
 * set user type status
 *
 * @param string $status
 * @return void
 */
function setAuthStatus($status = '')
{
    if ($status) {
        if ($status == 'buyer') {
            return 'kingbuyer';
        } elseif ($status == 'employee') {
            return 'goodemployee';
        } elseif ($status == 'cashier') {
            return 'bankminister';
        } elseif ($status == 'admin') {
            return 'bestnimda';
        }
    }
    return NULL;
}

/**
 * get user type status
 *
 * @param string $status
 * @return void
 */
function getAuthStatus($status = '')
{
    if ($status) {
        if ($status == 'kingbuyer') {
            return 'buyer';
        } elseif ($status == 'goodemployee') {
            return 'employee';
        } elseif ($status == 'bankminister') {
            return 'cashier';
        } elseif ($status == 'bestnimda') {
            return 'admin';
        }
    }
    return NULL;
}

/**
 * get user name by code
 *
 * @param string $code
 * @return void
 */
function getUserNameByCode($code = '')
{
    if ($code) {
        $user = User::select(['name'])->where('code', $code)->first();
        return ($user) ? $user['name'] : NULL;
    }
    return NULL;
}

/**
 * response status
 *
 * @return void
 */
function successResponse($msg, ...$response_data)
{
    return ['status' => 'success', 'message' => $msg, 'response_data' => $response_data];
}

function infoResponse($msg)
{
    return ['status' => 'info', 'message' => $msg];
}

function errorResponse($msg)
{
    return ['status' => 'error', 'message' => $msg];
}

function customResponse($stat, $msg, ...$response_data)
{
    return ['status' => $stat, 'message' => $msg, 'response_data' => $response_data];
}
/** */

/**
 * create new user code
 *
 * @return void
 */
function createNewUserCode()
{
    return Str::random(64);
}

/**
 * create access token for validation
 *
 * @return void
 */
function createAccessTokenUser()
{
    return Str::random(32);
}

/**
 * create custom amount random string
 *
 * @param int $rand_amount
 * @return void
 */
function randString($rand_amount)
{
    return Str::random($rand_amount);
}

function randArray($array_data)
{
    return Arr::random($array_data);
}

/**
 * set first char with start
 *
 * @param string $message
 * @param string $start
 * @return void
 */
function setStartWith($message, $start = '/')
{
    return Str::start($message, $start);
}

/**
 * check first char from words
 *
 * @param string $message
 * @param string $check
 * @return void
 */
function checkStartWith($message, $check)
{
    return Str::startsWith($message, $check);
}

/**
 * create slug name
 *
 * @param string $message
 * @param string $separator
 * @return void
 */
function slugIt($message, $separator = '-')
{
    return Str::slug($message, $separator);
}

/**
 * create allowed url for user
 *
 * @param array $data
 * @return void
 */
function globalUrlAllowedMap($data)
{
    return [
        'index' => $data['index'], 'type' => $data['type'], 'name' => $data['url_name'], 'icon' => $data['url_icon'], 'link' => $data['url_link'], 'description' => $data['url_desc']
    ];
}

function _throwErrorResponse($message = 'Sorry, you cant find anything here', $code = '404')
{
    return response()->json(errorResponse($message), $code);
}
