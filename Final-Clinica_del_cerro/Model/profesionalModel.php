<?php
class profesionalModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO("mysql:host=localhost" . 'dbname=del_cerro;charset=utf8;', 'root', '');
    }

    function agregarProfesional($nombre, $especialidad)
    {
        $query = $this->db->prepare("INSERT INTO PROFESIONAL(nombre,especialidad)VALUES(?,?)");
        $query->execute(array($nombre, $especialidad));
        return $this->db->lastInsertId();
    }
}