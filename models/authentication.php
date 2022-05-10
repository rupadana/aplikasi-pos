<?php

class Authentication {

    public static function login($username, $password) {
        $pass = md5($password);
        $data = DB::table("login")->join("member", "member.id_member", "login.id_member")->where("user", "=", "$username")->where("pass", "=","$pass")->first();
        
        if($data) {
            $_SESSION["admin"] = $data;
            return true;
        } 

        return false;
    }

    public static function isLoggedIn() {
        
        if(!isset($_SESSION)) {
            session_start();
        }
        
        if(isset($_SESSION["admin"]) && $_SESSION["admin"]) {
            return true;
        } 

        return false;
    }

    
}