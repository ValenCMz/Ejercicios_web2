<?php

class valoracionesModel{
    private $db;

    function __construct(){
        $this->db = new PDO("mysql:host=localhost;" . 'dbname=db_album;charset=utf8;' , 'root' , '');
    }

    
    // BASE DE DATOS
    // ARTISTA(id: int, nombre: string, premium: boolean)
    // ALBUM(id: int, titulo: string, productor: string, genero: string, 
    // fechaLanzamiento: string, id_artista: int)
    // VALORACION(id: int, estrellas: int, id_album: int,id_user: int)
    
    function getValoracionesPorAlbum($id_album){
        $query = $this->db->prepare("SELECT * FROM VALORACION WHERE id_album = ?");
        $query->execute(array($id_album));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getValoracionDelAlbumPorElUsuario($id_album, $id_user){
        $query = $this->db->prepare("SELECT * FROM VALORACION WHERE id_album = ? AND id_user = ?");
        $query->execute(array($id_album,$id_user));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function insertarValoracion($estrellas, $id_album, $id_user){
        $query = $this->db->prepare("INSERT INTO VALORACION(estrellas,id_album,id_user)VALUES(?,?,?)");
        $query->execute(array($estrellas,$id_album,$id_user));
        return $this->db->lastInsertId();
    }

    function updateValoracion($estrellas,$id_album,$id_user){
        $query = $this->db->prepare("UPDATE VALORACION SET estrellas = ? WHERE id_album = ? AND id_user =?");
        $query->execute(array($estrellas,$id_album,$id_user));
        return $this->db->lastInsertId();
    }
}