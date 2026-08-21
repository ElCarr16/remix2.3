<?php
$c = curl_init('http://127.0.0.1:8000/');
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($c);
file_put_contents('test2.html', $res);
echo "Saved to test2.html\n";
