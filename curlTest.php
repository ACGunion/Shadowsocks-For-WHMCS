<?php
$username = "APIUSER";
$password = "TESTAPI";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://oldrive.000webhostapp.com/ShaDowSocks_API");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
curl_exec($ch);
curl_close($ch);