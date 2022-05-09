<?php
include __DIR__ . "/../functions/index.php";
include __DIR__ . "/../models/index.php";

if(!function_exists("config")) {
    function config($arg) {

        $cfg = require(__DIR__ .  "/load_config.php");
        $args = explode(".", $arg);

        $data = "";

        try {
            foreach ($args as $key => $v) {
                if($data != '') {
                    $data = $data[$v];
                } else {
                    $data = $cfg[$v];
                }
            }
        } catch (\Throwable $th) {
            $data = "";
        }
        
        return $data;
    }
}