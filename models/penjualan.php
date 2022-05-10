<?php

class Penjualan extends Query {

    protected static $table="penjualan";
    protected static $primaryKey = "id_penjualan";

    public static function penjualans() {
        return DB::table("penjualan")
        ->select([
            "penjualan.*",
            "barang.id_barang",
            "barang.nama_barang",
            "member.id_member",
            "member.nm_member",
        ])
        ->leftjoin("barang","barang.id_barang", "penjualan.id_barang")
        ->leftjoin("member", "member.id_member", "penjualan.id_member")
        ->get();
    }

    public static function jumlah() {
        return DB::table("penjualan")->select("SUM(total) as bayar")->first();
    }
}