<?php
/**
 * User: Rick Lan
 * Date: 8/10/2018
 * Time: 1:42 PM
 */
include_once '_apiKeyConfig.php';
set_time_limit(0);

use Httpful\Request;

// get a list of companies first, then get the first returned company id
include_once '03_ListAllCompanies.php';
$aCompanies = json_decode($companies);
$companyID = $companies[1]->ID;

// customers
$customers = [];

$hasNextPage = true;
$page = BASEURL . "/companies/$companyID/customers/";

while ($hasNextPage) {
    $hasNextPage = false;

    // get customer list
    $response = Request::get($page)
        ->addHeader('Authorization', 'Bearer ' . API_KEY)
        ->sendsAndExpects('application/json;charset=utf-8')
        ->sendIt();

    $result = json_decode($response->body);
    if (count($result)) {

        // append result to the list
        $customers = array_merge($customers, $result);

        // get header
        $headers = $response->headers->toArray();

        // if a link header is provide, the result contain more than 1 page,
        // you can use it to retrieve the next set of result.
        if (!empty($links = $headers['link'])) {
            $aLinks = explode(',', $links);
            // loop through links and extract url
            foreach ($aLinks as $link) {
                preg_match('/^<(.*)>; rel="next"/', trim($link), $matchedURL);
                if (!empty($page = $matchedURL[1])) {
                    $hadNextPage = true;
                    $hasNextPage = true;
                    break;
                }
            }
        }
    }
}

if (DISPLAY_RESULT) {
    echo "All customers from company $companyID:" . PHP_EOL;
    print_r($customers);
}




