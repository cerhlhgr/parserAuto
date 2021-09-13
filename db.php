<?php


class db
{
    public $db;

    function __construct()
    {
        $this->db = pg_connect("host=127.0.0.1 port=5432 dbname=cars user=postgres password=");
    }

    public function marks($marks, $model)
    {
        // $result = pg_query($this->db, "SELECT author, email FROM authors");
        $arr = [
            'mark' => $marks,
            'model' => $model
        ];
        $res = pg_insert($this->db, 'marks', $arr);
    }


    public function models($model, $cusovType, $modific)
    {
        // $result = pg_query($this->db, "SELECT author, email FROM authors");
        $arr = [
            'model' => $model,
            'type-cusov' => $cusovType,
            'modific' => $modific

        ];
        $res = pg_insert($this->db, 'Models', $arr);
    }

    public function engine($date)
    {
        // $result = pg_query($this->db, "SELECT author, email FROM authors");
        $arr = [
            'engineType' => $date["engineType"],
            'FuelGrade' => $date["FuelGrade"],
            'EngineVolume' => $date["EngineVolume"],
            'Power' => $date["Power"]
        ];
        $res = pg_insert($this->db, 'engine', $arr);
    }

}