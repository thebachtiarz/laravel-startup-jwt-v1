<?php

/**
 * use libraries
 */

use Illuminate\Support\Str;

/**
 * use models
 *
 * @return void
 */

use App\Models\User;

/** */

/**
 * handler template theme asset
 *
 * @return void
 */
# online version
function online_asset()
{
    return 'http://bachtiars.com/AdminLTE-3.0.1/';
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
    return offline_asset() . '/dist/img/AdminLTELogo.png';
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
