<?php

// register the rest endpoint, give it name, and designate a callback function
function makeInitialSringEndpoint(){
  add_action( 'rest_api_init', function () {
    register_rest_route( 'madlib/v1', '/gen-fields', array(
      'methods' => 'GET',
      'callback' => 'callStringApi'
    ));
  });
}

// function to run when the rest endpoint is hit
function callStringApi( WP_REST_Request $request ) {

  // https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints
  $initial_string = $request['text'];
  $initial_string = $request['poop'];

  // some differences to note...
  // $encoded_string = urlencode($initial_string);
  $encoded_string = rawurlencode($initial_string);

  $madlib = genMadlibParagraph($encoded_string);

  $fields = get_madlib_fields($madlib);

  return ['madlib' => "chris", 'fields' => "yo"];
}

/**
 * Curl request to api for madlib 'markup' generation
 *
 * @param string $text - initial url encoded string of text that
 * is being turned into the madlib text.
 * @return object $res - the response object that we will send back
 */
function genMadlibParagraph($text){

  $curl = curl_init();
  curl_setopt_array($curl, array(
      CURLOPT_URL => "http://libberfy.herokuapp.com/?q=" . $text,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache"
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
          $res = "cURL Error #:" . $err;
        } else {
            $res = json_decode($response);
          }

          return $res->madlib;

          // return "At Indiegogo you'll find a welcoming , <adjective> <noun> that embraces collaboration , fearlessness and authenticity . We are a rapidly <verb_ending_with_ing> organization and our platform is used by people all over the world to <verb> <noun> for their creative , cause-related , or entrepreneurial ideas . Our customers are passionate about their funding campaigns , and so are we ! We are a team of <adjective> , results-driven , team-players who are lucky <adjective> to be able to <verb>. We love our dogs , good <noun> , coffee , and post-it notes ! Lots of <noun> notes !";
        }

        function get_madlib_fields($madlib){
          $num_replacements = preg_match_all('/\<\w*\>/', $madlib, $out);
          $all_fields = array();

          for ($x = 0; $x <= $num_replacements-1; $x++) {
            if (preg_match_all('/\<\w*\>/', $madlib, $match) ) {
              $tag_str = $match[0][$x];
              // echo $tag_str;
              array_push( $all_fields, substr($tag_str, 1, -1) );
            }
          }

          return $all_fields;
        }







        makeInitialSringEndpoint();