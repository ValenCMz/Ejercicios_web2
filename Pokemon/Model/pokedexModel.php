<?php

class pokedexModel{

    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=ejercicio_pokemon;charset=utf8;' , 'root' , '');
    }

    function getPokedexPorId($id){
        $query = $this->db->prepare("SELECT * FROM pokedex WHERE id = ?");
        $query->execute(array($id));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getPokedexPorUsuario($id_user){
        $query = $this->db->prepare("SELECT * FROM pokedex WHERE id_user = ?");
        $query->execute(array($id_user));
        return $query->fetch(PDO::FETCH_OBJ);
    }

}