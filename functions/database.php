<?php


class DB
{
    static public $_table;
    static private $pdo;
    static private $_where = [];
    static private $_join = [];
    static private $_orderby = "";
    static private $_update = [];
    static private $_insert = [];

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
    public static function table($tb)
    {
        try {

            // Reset properties
            self::$_where = [];
            self::$_join = [];
            self::$_update = [];
            self::$_insert = [];
            self::$_select = "*";
            self::$_orderby = "";


            self::$_table = $tb;

            if (!self::$pdo) {
                // Dapatkan database konfigurasi
                $host = config("database.host");
                $dbname = config("database.database");
                $user = config("database.username");
                $pass = config("database.password");
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;", $user, $pass);
            }
        } catch (PDOException $e) {
            echo 'KONEKSI GAGAL' . $e->getMessage();
        }

        return new static();
    }


    /**
     * Memilih column pada table
     *
     * @param string $_select
     * @return DB
     */
    public static function select($_select = "*")
    {
        self::$_select = $_select;

        return new static();
    }


    /**
     * Dapatkan semua data
     *
     * @return array
     */
    public static function get()
    {
        $row = self::$pdo->prepare(self::raw_query("get"));
        
        $row->execute();
        $rowCount = $row->rowCount();
        if ($rowCount > 0) {
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
    public static function first()
    {

        $row = self::$pdo->prepare(self::raw_query("get"));
        $row->execute();
        $rowCount = $row->rowCount();
        if ($rowCount > 0) {
            return $row->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    public static function update($datas)
    {
        self::$_update = $datas;
        $update = self::$pdo->prepare(self::raw_query("update"));

        return $update->execute();
    }

    public static function insert($datas)
    {
        self::$_insert = $datas;
        $insert = self::$pdo->prepare(self::raw_query("insert"));

        return $insert->execute();
    }

    public static function delete()
    {
        $delete = self::$pdo->prepare(self::raw_query("delete"));

        return $delete->execute();
    }

    public static function where($column, $operator, $value)
    {
        if (count(self::$_where) > 0) {
            self::$_where[] = " and $column $operator '$value'";
        } else {
            self::$_where[] = " where $column $operator '$value'";
        }

        return new static();
    }

    public static function join($relation, $columna, $columnb)
    {
        self::$_join[] = " inner join $relation on $columna=$columnb";

        return new static();
    }

    public static function orderBy($column, $scending)
    {
        self::$_orderby = " ORDER BY $column $scending";

        return new static();
    }

    public static function raw_query($type = "")
    {
        $query = "";
        $_table = self::$_table;
        switch ($type) {
            case 'get':
                $_select = gettype(self::$_select) == "array" ? implode(",", self::$_select) : self::$_select;
                $query = "select $_select from $_table";

                foreach (self::$_join as $key => $value) {
                    $query .= $value;
                }

                foreach (self::$_where as $key => $value) {
                    $query .= $value;
                }



                $query .= self::$_orderby;
                break;

            case 'update':
                $query = "UPDATE $_table SET";

                $_update = [];
                foreach (self::$_update as $key => $value) {
                    $_update[] = " $key='$value'";
                }
                $query .= implode(",", $_update);

                foreach (self::$_where as $key => $value) {
                    $query .= $value;
                }
                break;

            case 'insert':

                $_column = [];
                $_values = [];

                foreach (self::$_insert as $column => $value) {
                    $_column[] = $column;
                    $_values[] = "'$value'";
                }
                $_column = implode(",", $_column);
                $_values = implode(",", $_values);

                $query = "INSERT INTO $_table ($_column) VALUES($_values)";

                break;

            case "delete":
                $query = "DELETE FROM $_table ";

                foreach (self::$_where as $key => $value) {
                    $query .= $value;
                }
                break;
        }


        return $query;
    }
}
