<?php
require 'library/tmhOAuth.php';
require 'library/tmhUtilities.php';
require 'mongo_connect.php';
include 'library/phpInsight/sentiment.class.php';
/**
 * Very basic streaming API example. In production you would store the
 * received tweets in a queue or database for later processing.
 *
 * Instructions:
 * 1) If you don't have one already, create a Twitter application on
 *      https://dev.twitter.com/apps
 * 2) From the application details page copy the consumer key and consumer
 *      secret into the place in this code marked with (YOUR_CONSUMER_KEY
 *      and YOUR_CONSUMER_SECRET)
 * 3) From the application details page copy the access token and access token
 *      secret into the place in this code marked with (A_USER_TOKEN
 *      and A_USER_SECRET)
 * 4) In a terminal or server type:
 *      php /path/to/here/streaming.php
 * 5) To stop the Streaming API either press CTRL-C or, in the folder the
 *      script is running from type:
 *      touch STOP
 *
 * @author themattharris
 */

$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => 'Kq7SQJ4S0z2TkBUWDtXg',
  'consumer_secret' => 'GbXcmCXw7EwzOi55jTDgUSZwF5JIH65A6jGVjFieEe8',
  'user_token'      => '19066237-6SsvOFImmHm4755UW5YwqdTsiTPyd2pmiOyh4LMYQ',
  'user_secret'     => 'yXxuEVGQsjtRrBqab3YcLnIX3backHbaQ0swTMrP0',
));

$method = 'https://stream.twitter.com/1/statuses/filter.json';

// show Tweets which contan the word twitter OR have been geo-tagged within
// the bounding box -122.41,37.77,-122.40,37.78 OR are by themattharris

$params = array(
  //'track'     => 'twitter',
  // First param is the SW corner of the bounding box
  'locations' => '-122.399391,37.773632,-122.396658,37.785562' //6th and Townsend, 2nd and Folsom 
  //'follow'    => '777925' // themattharris
);

$tmhOAuth->streaming_request('POST', $method, $params, 'my_streaming_callback');

// output any response we get back AFTER the Stream has stopped -- or it errors
tmhUtilities::pr($tmhOAuth);



function my_streaming_callback($data, $length, $metrics) {
    global $db;
    
    $sentiment = new Sentiment();
  	
    //Use this collection
    $collection = $db->aia;

    $data = json_decode($data, true);
    if (is_array($data) && isset($data['user']['screen_name'])) {
        print $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
        $data['sentiment'] = $sentiment->categorise($data['text']);
        $collection->insert($data);
    }
}
