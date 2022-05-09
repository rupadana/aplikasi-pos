<?php

class Kategori {
    public static function kategories() {
        $kategories = DB::table("kategori")->get();

        return $kategories;
    }
}