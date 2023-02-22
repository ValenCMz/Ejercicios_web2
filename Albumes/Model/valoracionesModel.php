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
}