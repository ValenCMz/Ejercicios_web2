<?php

class albumModel{
    private $db;

    function __construct(){
        $this->db = new PDO("mysql:host=localhost;" . 'dbname=db_album;charset=utf8;' , 'root' , '');
    }

    // BASE DE DATOS
    // ARTISTA(id: int, nombre: string, premium: boolean)
    // ALBUM(id: int, titulo: string, productor: string, genero: string, 
    // fechaLanzamiento: string, id_artista: int)
    // VALORACION(id: int, estrellas: int, id_album: int,id_user: int)

    function getAlbumPorTitulo($titulo){
        $query = $this->db->prepare("SELECT * FROM ALBUM WHERE titulo = ?");
        $query->execute(array($titulo));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getCantAlbumesPorArtista($id_artista){
        $query = $this->db->prepare("SELECT COUNT(*) FROM ALBUM WHERE id_artista = ?");
        $query->execute(array($id_artista));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getIdArtistaPorAlbum($id_album){
        $query = $this->db->prepare("SELECT id_artista FROM ALBUM WHERE id = ?");
        $query->execute(array($id_album));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getAlbumesPorArtista($id_artista){
        $query = $this->db->prepare("SELECT * FROM ALBUM WHERE id_artista = ?");
        $query->execute(array($id_artista));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function insertarAlbum($titulo,$productor,$genero,$fechaLanzamiento,$id_artista){
        $query = $this->db->prepare("INSERT INTO ALBUM(titulo,productor,genero,fechaLanzamiento,id_artista)VALUES(?,?,?,?,?)");
        $query->execute(array($titulo,$productor,$genero,$fechaLanzamiento,$id_artista));
        return $this->db->lastInsertId();
    }
    // BASE DE DATOS
    // ARTISTA(id: int, nombre: string, premium: boolean)
    // ALBUM(id: int, titulo: string, productor: string, genero: string, 
    // fechaLanzamiento: string, id_artista: int)
    // VALORACION(id: int, estrellas: int, id_album: int,id_user: int)
}
