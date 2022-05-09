<?php

$config = [];
foreach (glob(__DIR__ . "/../config/*.php") as $key => $value) {
    $data = explode("/", $value);
    $config[substr($data[count($data) - 1], 0, -4)] = require($value);
}

return $config;