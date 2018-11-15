<?php
/**
 * User: Rick Lan
 * Date: 8/10/2018
 * Time: 1:41 PM
 */
include '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// AUTHORIZATION CODE GRANT
define('AUTHORIZATION_CODE_ID', '<client_id>');
define('AUTHORIZATION_CODE_SECRET', '<client_secret>');
define('REDIRECT_URL', 'http://<your_public_url>/00_OAuth2_AuthorizationCode.php');

// API KEY GRANT
define('API_KEY', '<api_key>');

// API base url
define('BASEURL', 'https://<build>.simprosuite.com/api/v1.0');

// output to screen/console
define('DISPLAY_RESULT', true);