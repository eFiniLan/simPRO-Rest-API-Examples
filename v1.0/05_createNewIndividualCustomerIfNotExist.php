<?php
/**
 * User: Rick Lan
 * Date: 8/10/2018
 * Time: 1:42 PM
 */
include_once '_config.php';
set_time_limit(0);

use Httpful\Request;

// get a list of companies first, then get the first returned company id
include_once '03_ListAllCompanies.php';
$aCompanies = json_decode($companies);
$companyID = $aCompanies[1]->ID;

// define minimum mandatory fields
$giveName = 'GiveName' . rand(1000,9999);
$familyName = 'FamilyName' . rand(1000, 9999);
$customerDetails = [
    'GivenName' => $giveName,
    'FamilyName' => $familyName
];

$response = Request::get(BASEURL . '/companies/' . $companyID . '/customers/individuals/?GiveName=' . $giveName .'&FamilyName=' . $familyName)
    ->addHeader('Authorization', 'Bearer ' . API_KEY)
    ->sendsAndExpects('application/json;charset=utf-8')
    ->sendIt();
$customers = $response->body;

// customer not found, we create a new one
if ($customers == '[]') {
    // get customer list
    $response = Request::post(BASEURL . '/companies/' . $companyID . '/customers/individuals/',
        json_encode($customerDetails))
        ->addHeader('Authorization', 'Bearer ' . API_KEY)
        ->sendsAndExpects('application/json;charset=utf-8')
        ->sendIt();

    $customer = $response->body;
} else {
    $customers = json_decode($customers);
    // first customer id
    $customerID = $customers[0]->ID;

    // retrieve the full customer information
    $response = Request::get(BASEURL . '/companies/' . $companyID . '/customers/individuals/' . $customerID)
        ->addHeader('Authorization', 'Bearer ' . API_KEY)
        ->sendsAndExpects('application/json;charset=utf-8')
        ->sendIt();
    $customer = $response->body;
}

if (DISPLAY_RESULT) {
    echo "Customer:" . PHP_EOL;
    var_dump(json_decode($customer, true, 512, JSON_PRETTY_PRINT));
}




