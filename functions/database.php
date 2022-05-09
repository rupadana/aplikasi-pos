<?php


class DB {

    static public $_table;
    static private $pdo;
    static private $_where = [];
    static private $_join= [];

    /**
     * Deklarasi variable select 
     * Digunakan untuk menyimpan data yang ingin dipilih
     *
     * @var string|array
     */
    static private $_select = "*";

    
    /**
     * Memilih table
     * Melakukan koneksi ke database
     *
     * @param string $tb
     * @return DB
     */
    public static function table($tb) {
        try{

            // Reset properties
            self::$_where = [];
            self::$_join = [];
            self::$_select = "*";

            
            self::$_table = $tb;
            
            if(!self::$pdo) {
                // Dapatkan database konfigurasi
                $host = config("database.host");
                $dbname = config("database.database");
                $user = config("database.username");
                $pass = config("database.password");
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;", $user,$pass);
            }

        }catch(PDOException $e){
            echo 'KONEKSI GAGAL' .$e -> getMessage();
        }

        return new static();
    }


    /**
     * Memilih column pada table
     *
     * @param string $_select
     * @return DB
     */
    public static function select($_select = "*") {
        self::$_select = $_select;

        return new static();
    }


    /**
     * Dapatkan semua data
     *
     * @return array
     */
    public static function get() {
        $row = self::$pdo->prepare(self::raw_query("get"));
        $row->execute();
        $rowCount = $row->rowCount();
        if($rowCount > 0) {
            return $row->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Dapatkan 1 data saja
     *
     * @return array|null
     */
    public static function first() {
        $row = self::$pdo->prepare(self::raw_query("get"));
        $row->execute();
        $rowCount = $row->rowCount();
        if($rowCount > 0) {
            return $row->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    public static function where($column, $operator, $value) {
        if(count(self::$_where) > 0) {
            self::$_where[] = " and $column $operator '$value'";
            
        } else {
            self::$_where[] = " where $column $operator '$value'";
        }

        return new static();
    }

    public static function join($relation, $columna, $columnb) {
        self::$_join[] = " inner join $relation on $columna=$columnb";

        return new static();
    }

    public static function raw_query($type = "") {
        $query = "";
        switch ($type) {
            case 'get':
                $_select = gettype(self::$_select) == "array" ? implode(",", self::$_select) : self::$_select;
                $_table = self::$_table;
                $query = "select $_select from $_table";
                
                foreach (self::$_join as $key => $value) {
                    $query.= $value;
                }

                foreach (self::$_where as $key => $value) {
                   $query.=$value;
                }



                // $query .= $_where;
                break;
            
        }
        return $query;
    }
}