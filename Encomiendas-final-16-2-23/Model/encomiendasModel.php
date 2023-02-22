<?php

class encomiendaModel{
    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_encomiendas;charset=utf8;' , 'root' , '');
    }      

    function getEncomiendaPorComisionistaYFecha($id_comisionista,$fecha){
        //voy a buscar una encomienda de un comisionista especifico y una fecha dada
        $query = $this->db->prepare("SELECT * FROM encomienda WHERE id_comisionista = ? AND fecha = ?");
        $query->execute(array($id_comisionista,$fecha));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function agregarEncomienda($peso,$destinatario,$id_comisionista,$id_tracking,$estado,$fecha){
        $query = $this->db->prepare("INSERT INTO encomienda(peso,destinatario,id_comisionista,id_tracking,estado,fecha) VALUES(?,?,?,?,?,?)");
        $query->execute(array($peso,$destinatario,$id_comisionista,$id_tracking,$estado,$fecha));
        return $this->db->lastInsertId();
    }

    function buscarEncomiendaPorIdComisionistaYFecha($id, $fecha){
        $query = $this->db->prepare("SELECT * FROM encomienda WHERE id_comisionista=? AND fecha != ?");
        $query->execute(array($id, $fecha));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function agregarTracking($id){
        $query = $this->db->prepare("INSERT INTO tracking(id) VALUES(?)");
        $query->execute(array($id));
    }


}