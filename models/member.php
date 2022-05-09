<?php


class Member {

    public static function member_edit($id) {
        $data = DB::table("member")->join("login", "login.id_member", "member.id_member")->where("member.id_member", "=", $id)->first();

        return $data;
    }
}