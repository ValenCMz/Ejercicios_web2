<?php
class turnoModel
{
    private $db;
    function __construct()
    {
        $this->db = new PDO("mysql:host=localhost" . 'dbname=del_cerro;charset=utf8;', 'root', '');
    }

    function getTurnosPorProfesional($id_profesional)
    {
        $query = $this->db->prepare("SELECT * FROM TURNOS WHERE id_profesiona_fk = ?");
        $query->execute(array($id_profesional));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function agregarTurno($fecha, $dni, $id_profesional)
    {
        $query = $this->db->prepare("INSERT INTO TURNO(fecha,dni_paciente,id_profesional_fk)VALUES(?,?,?)");
        $query->execute(array($fecha, $dni, $id_profesional));
        return $this->db->lastInsertId();
    }

    function getTurnoPorFechaYProfesional($fecha, $id_profesional)
    {
        $query = $this->db->prepare("SELECT * FROM TURNO WHERE fecha = ? AND id_profesiona_fk = ?");
        $query->execute(array($fecha, $id_profesional));
        return $query->fetch(PDO::FETCH_OBJ);
    }

}