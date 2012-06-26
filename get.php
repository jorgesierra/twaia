<?php
/**
 * Gets the tweets from the mongo DB and return jsons
 */
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
require_once 'mongo_connect.php';

// just toggle debug
$debug = false;
if(isset($_GET["debug"])){
    $debug = true;
}

$collection = $db->aia;

$rangeQuery = array();

$limit = 50;
if(isset($_GET["from"]) && $_GET["from"] !=0){
    $rangeQuery=array("id_str" => array('$gt'=>$_GET["from"]));
    
    $limit = 50;
} 
$cursor = $collection->find($rangeQuery);
$cursor = $cursor->limit($limit);

//order by id desc
$cursor->sort(array("id_str"=>-1));

// iterate through the results
echo json_encode(iterator_to_array($cursor));
if(false && $debug){
    print_r(iterator_to_array($cursor));
}
