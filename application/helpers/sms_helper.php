<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('forget'))
{
	function forget($field)
	{
		$curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://www.fast2sms.com/dev/bulk",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($field),
          CURLOPT_HTTPHEADER => array(
            "authorization: ZjIpM4RgbYwVSluqfGTWi1y5avU3ELNc8hsO6drDteoFzBCAkXu0hrt7PmXjywQeaBcUNM5bWKV3E96l",
            "cache-control: no-cache",
            "accept: */*",
            "content-type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
          return "1";
        } else {
          return '0';
        }

	}

}

