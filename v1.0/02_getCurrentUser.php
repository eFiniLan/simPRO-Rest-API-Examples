<?php
/**
 * Display current logged in user info
 *
 * User: Rick Lan
 * Date: 8/10/2018
 * Time: 1:42 PM
 */
include_once '_config.php';
set_time_limit(0);

use Httpful\Request;

// get a list of companies first
$response = Request::get(BASEURL . '/currentUser/')
    ->addHeader('Authorization', 'Bearer ' . API_KEY)
    ->sendsAndExpects('application/json;charset=utf-8')
    ->sendIt();
$userInfo = $response->body;

if (DISPLAY_RESULT) {
    echo 'List of build info:' . PHP_EOL;
    var_dump(json_decode($userInfo, true, 512, JSON_PRETTY_PRINT));
}