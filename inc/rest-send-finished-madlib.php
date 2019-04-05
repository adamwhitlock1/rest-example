<?php

// register the rest endpoint, give it name, and designate a callback function
function makeFinishedEndpoint(){
  add_action( 'rest_api_init', function () {
    register_rest_route( 'madlib/v1', '/finish-madlib', array(
      'methods' => 'GET',
      'callback' => 'sendFinished'
    ));
  });
}

// function to run when the rest endpoint is hit
function sendFinished( WP_REST_Request $request ) {

  $madlib = $request['madlib'];
  $replacements = $request['fields'];

  foreach ($replacements as $index => $word) {
    $madlib = preg_replace('/\<\w*\>/', $word, $madlib, 1);
  }

  return ['finished_madlib' => $madlib];
}

makeFinishedEndpoint();

// "It was a windy , cold November donkey . I woke up to the fragrant smell of bacon cooking in the barn . I quickly headed down the stairs to see if I could help my fish with breakfast . The mother asked if I could pour glasses of juice for the whole family . I got cups out and pours dog some orange poop to go with our farts and pancakes . We loved a great breakfast . After car , the whole family went on a walk around the lake . It started dying so we headed inside ."