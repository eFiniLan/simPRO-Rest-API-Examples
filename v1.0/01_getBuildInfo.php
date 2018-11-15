<?php
/**
 * Display build info
 * This gives you information of the build you are working on.
 *
 * User: Rick Lan
 * Date: 8/10/2018
 * Time: 1:42 PM
 */
include_once '_config.php';
set_time_limit(0);

use Httpful\Request;

// get a list of companies first
$response = Request::get(BASEURL . '/info/')
    ->addHeader('Authorization', 'Bearer ' . API_KEY)
    ->sendsAndExpects('application/json;charset=utf-8')
    ->sendIt();
$info = $response->body;

if (DISPLAY_RESULT) {
    echo 'List of build info:' . PHP_EOL;
    var_dump(json_decode($info, true, 512, JSON_PRETTY_PRINT));
}