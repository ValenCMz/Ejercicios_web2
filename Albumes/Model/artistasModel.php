<?php

class artistasModel{

    private $db;

    function __construct(){
        $this->db = new PDO("mysql:host=localhost;" . 'dbname=db_album;charset=utf8;' , 'root' , '');
    }

     // BASE DE DATOS
    // ARTISTA(id: int, nombre: string, premium: boolean)
    // ALBUM(id: int, titulo: string, productor: string, genero: string, 
    // fechaLanzamiento: string, id_artista: int)
    // VALORACION(id: int, estrellas: int, id_album: int,id_user: int)

    function getArtistas(){
        $query = $this->db->prepare("SELECT * FROM ARTISTA");
        $query->execute();
        $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getArtistaPorId($id_artista){
        $query = $this->db->prepare("SELECT * FROM ARTISTA WHERE id = ?");
        $query->execute(array($id_artista));
        return $query->fetch(PDO::FETCH_OBJ);
    }
}