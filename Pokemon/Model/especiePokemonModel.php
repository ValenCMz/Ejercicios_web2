<?php

class especiePokemonModel{

    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=ejercicio_pokemon;charset=utf8;' , 'root' , '');
    }

    function getEspeciePorId($id_especie){
        $query =$this->db->prepare("SELECT * FROM especiePokemon WHERE id = ? ");
        $query->execute(array($id_especie));
        return $query->fetch(PDO::FETCH_OBJ);
    }


}