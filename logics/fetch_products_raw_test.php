<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$products = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_products_raw.php');
if (isset($_REQUEST['filter'])) {
    $products_final = array();
    $filterArr = explode('-', $_REQUEST['filter']);
    foreach ($products as $product) {
        foreach ($filterArr as $filter) {
            $filtered = $product["CATEGORY"] == $filter;
            if ($filtered) {
                $products_final[] = $product;
                break;
            }
        }
    }
} else {
    $products_final = $products;
}



$shop_codes = array_unique(array_column($products_final, 'SHOP_CODE'));

echo "<pre>";
print_r($shop_codes);
echo "</pre>";

// $stores = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_stories_store.php');
// if (isset($_REQUEST['filter'])) {
//     $stores_final = array();
//     $filterArr = explode('-', $_REQUEST['filter']);
//     foreach ($stores as $store) {
//         foreach ($filterArr as $filter) {
//             // $filtered = $store["CATEGORY"] == $filter;
//             $filtered = in_array($store["CODE"], $products_final);
//             if ($filtered) {
//                 $stores_final[] = $store;
//                 break;
//             }
//         }
//     }
// }
