<?php

class Barang extends Query
{

    protected static $table = "barang";

    public static function stoks()
    {
        $data = DB::table("barang")
            ->select([
                "barang.*",
                "kategori.id_kategori",
                "kategori.nama_kategori"
            ])
            ->join("kategori", "barang.id_kategori", "kategori.id_kategori")
            ->where("stok", "<=", 3)
            ->orderBy("id", "desc")
            ->get();


        return $data;
    }

    public static function barangs()
    {
        $data = DB::table("barang")
            ->select([
                "barang.*",
                "kategori.id_kategori",
                "kategori.nama_kategori",
            ])
            ->join("kategori", "kategori.id_kategori", "barang.id_kategori")
            ->orderBy("id", "desc")
            ->get();

        return $data;
    }

    public static function generate_id()
    {
        $latest_data = DB::table("barang")->orderBy("id", "desc")->first();

        $urut = substr($latest_data['id_barang'], 2, 3);
        $tambah = (int) $urut + 1;
        if (strlen($tambah) == 1) {
            $format = 'BR00' . $tambah . '';
        } else if (strlen($tambah) == 2) {
            $format = 'BR0' . $tambah . '';
        } else {
            $ex = explode('BR', $latest_data['id_barang']);
            $no = (int) $ex[1] + 1;
            $format = 'BR' . $no . '';
        }
        return $format;
    }
    
    public static function findByIdBarang($id) {
        return DB::table("barang")->join("kategori", "kategori.id_kategori", "barang.id_kategori")->where("id_barang", "=", $id)->first();
    }
}
