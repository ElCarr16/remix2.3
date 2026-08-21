<?php
$c = curl_init('http://127.0.0.1:8000/?debug_blocks=1');
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($c);
