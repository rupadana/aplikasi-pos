<?php

class Query {
    protected static $table = "";
    protected static $primaryKey = "id";


    public static function find($id) {
        $data = DB::table(static::$table)->where(static::$primaryKey, "=", $id)->first();
        return $data;
    }

    public static function delete($id) {
        return DB::table(static::$table)->where(static::$primaryKey, "=", $id)->delete();
    }
}