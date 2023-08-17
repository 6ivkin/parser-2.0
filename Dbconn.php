<?php

class Dbconn
{
    static function init(): PDO
    {
        return new PDO('mysql:host=localhost;dbname=pricing_table;port=3306', 'root', '');
    }

/*    public static function updoadElfgroup(): void
    {
        self::clearDatabase();
        $csv = array_map('str_getcsv', file('./elfgroup/products.csv'));

        try {
            $sql = "INSERT INTO products(product, price, site)";
            foreach ($csv as $element) {
                $sql .= " VALUES(" . rtrim($element[3], '.') . ", $element[4], elfgroup.ru),";
                var_dump($sql);
            }
            $sql = rtrim($sql, ',');
            $stmt = self::init()->prepare($sql);
            $stmt->execute();
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }*/

    public static function updoadSantech(): void
    {
        self::clearDatabase();
        $arr = file_get_contents('./santech/santech.txt');
        $json = json_decode($arr, true);

        try {
            $sql = "INSERT INTO products(id, product, price, site)";
            foreach ($json as $array) {
                foreach ($array as $element) {
                    $sql .= ' VALUES(' . $element['name'] . ', ' . $element['price'] . ', santech.ru),';
                }
            }
            $sql = rtrim($sql, ',');
            $stmt = self::init()->prepare($sql);
            $stmt->execute();
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    public static function updoadArmsnab(): void
    {
        self::clearDatabase();
        $arr = file_get_contents('./armsnab/products.txt');
        $json = json_decode($arr, true);

        try {
            $sql = "INSERT INTO products(id, product, price, site)";
            foreach ($json as $array) {
                if (!empty($array)) {
                    foreach ($array as $element) {
                        $sql .= ' VALUES(' . $element['name'] . ', ' . $element['price'] . ', armsnabrf.ru),';
                    }
                }
            }
            $sql = rtrim($sql, ',');
            $stmt = self::init()->prepare($sql);
            $stmt->execute();
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    public static function clearDatabase(): void
    {
        try {
            $sql = "DELETE FROM products";
            $stmt = self::init()->prepare($sql);
            $stmt->execute();
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }
}
