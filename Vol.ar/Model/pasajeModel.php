<?php

class pasajeModel{
    private $db;
    function __construct(){
        $this->db = new PDO("mysql:host=localhost". 'dbname=vol.ar;charset=utf8;','root','');
    }

    function getCantidadDePasajes($id_vuelo){
        $query = $this->db->prepare("SELECT COUNT(*) FROM PASAJE WHERE id_vuelo = ?");
        $query->execute(array($id_vuelo));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function insertarPasaje($fecha_venta,$clase,$equipaje,$id_vuelo,$id_usuario){
        $query = $this->db->prepare("INSERT INTO PASAJE(fecha_venta,clase,equipaje,id_vuelo,id_usuario)VALUES(?,?,?,?,?)");
        $query->execute(array($fecha_venta,$clase,$equipaje,$id_vuelo,$id_usuario));
        return $query->rowCount();
    }

    function getPasajesPorVuelo($id_vuelo){
        $query = $this->db->prepare("SELECT * FROM PASAJE WHERE id_vuelo = ?");
        $query->execute(array($id_vuelo));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

     // AEROLINEA(id: int, nombre: string)
    // VUELO(id: int, origen: string, destino: string, fecha: string, estado: string, capacidad: int, internacional: bool,
    // id_aerolinea: int)
    // Además, nos brinda una tabla donde se almacena la información de pasajes vendidos.
    // PASAJE(id: int, fecha_venta: string, clase: int, equipaje: int, id_vuelo: int, id_usuario: int)
    // Donde clase es un número entre el 1 y el 3, y equipaje es el peso en kilos.
}