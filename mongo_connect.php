<?php
define("MONGO_HOST", "127.0.0.1");
define("MONGO_PORT", "27017");
try{
    $mongo = new Mongo();
    $mongo = new Mongo("mongodb://".MONGO_HOST.":".MONGO_PORT);
} catch (Exception $e){
    echo "An error ocurred, cant connect to mongo, check configuration file. <br/> error Message: ".$e->getMessage();
}

$db = $mongo->aia;