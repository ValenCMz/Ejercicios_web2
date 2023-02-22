<?php

class comisionistaModel{
    private $db;

    function __construct(){
        $this->db = new PDO("mysql:host=localhost;" . "dbname=db_encomiendas;charset=utf8;" , 'root' ,'');
    }

       //BASE DE DATOS
    //ENCOMIENDA(id_encomienda: int; peso: float; destinatario: string, id_comisionista(FK): int;
    //idTracking: string; estado: int; fecha: date)

    //COMISIONISTA(id_comisionista: int; nombre: string; capacidad_vehiculo: float; 
    //ciudad_destino: string)

    function getComisionistaDisponiblePorPeso($peso){
        //chequeamos q la capacidad del vehiculo del comisionista sea mayor al peso q tiene q llevar
        $query = $this->db->prepare("SELECT * FROM comisionista WHERE capacidad_vehiculo > ?");
        $query->execute(array($peso));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getComisionistasPorCiudad($ciudad){
        $query = $this->db->prepare("SELECT * FROM comisionista WHERE ciudad_destino = ?");
        $query->execute(array($ciudad));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}