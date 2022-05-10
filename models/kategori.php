<?php

class Kategori extends Query {

    protected static $table = "kategori";
    protected static $primaryKey = "id_kategori";

    public static function kategories() {
        $kategories = DB::table("kategori")->get();
        return $kategories;
    }
}