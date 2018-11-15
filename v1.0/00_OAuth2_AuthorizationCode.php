<?php
/**
 * This example explains how to use "league/oauth2-client" package to get an access token via OAuth2 Authorization Code flow.
 * Please note that this example will need to run in a browser.
 *
 * User: Rick Lan
 * Date: 15/11/2018
 *
 */

include_once '_config.php';
set_time_limit(0);

use Httpful\Request;

$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => AUTHORIZATION_CODE_ID,    // The client ID assigned to you by the provider
    'clientSecret'            => AUTHORIZATION_CODE_SECRET,   // The client password assigned to you by the provider
    'redirectUri'             => REDIRECT_URL,
    'urlAuthorize'            => BASEURL . '../../../oauth2/auth',
    'urlAccessToken'          => BASEURL . '../../../oauth2/token',
    'urlResourceOwnerDetails' => '',
]);

if (!isset($_GET['code'])) {

    $authorizationUrl = $provider->getAuthorizationUrl();
    header('Location: ' . $authorizationUrl);
    exit;

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        ?>
        <html>
        <body>
        <pre><?php
        echo 'Access Token: ' . $accessToken->getToken() . "<br>";
        echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
        echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
        echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired');
        ?><br/><br/><?php

        $apiKey = $accessToken->getToken();
        $response = Request::get(BASEURL . '/info/')
            ->addHeader('Authorization', 'Bearer ' . $accessToken->getToken())
            ->sendsAndExpects('application/json;charset=utf-8')
            ->sendIt();
        $info = $response->body;

        if (DISPLAY_RESULT) {
            echo 'Build info:' . PHP_EOL;
            echo '<pre>' . var_dump(json_decode($info, true, 512, JSON_PRETTY_PRINT)) . '</pre>';
        }
        ?>
        </pre>
        </body>
        </html>
        <?php
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }
}

