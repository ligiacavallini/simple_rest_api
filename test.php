<?php
$url = '../api/address';
$data = array('name' => 'Ligia', 'phone' => '567845435', 'street' => 'Main street');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

print_r($result);
?>

