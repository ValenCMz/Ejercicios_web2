<?php

class pokemonModel{

    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=ejercicio_pokemon;charset=utf8;' , 'root' , '');
    }

    function getPokemonesConCondicion($especie, $pokedex, $nivel){
        $query = $this->db->prepare("SELECT * FROM pokemon WHERE id_especie = ? AND id_pokedex = ? AND nivel >= ?");
        $query->execute(array($especie,$pokedex,$nivel));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getPokemonesPorPokedex($id_pokedex){
        $query = $this->db->prepare("SELECT * FROM pokemon WHERE id_pokedex = ?");
        $query->execute(array($id_pokedex));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getPokemonPorEspecieYId($id_especie,$id_pokedex){
        $query = $this->db->prepare("SELECT * FROM pokemon WHERE id_especie = ? AND id_pokedex = ?");
        $query->execute(array($id_especie,$id_pokedex));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function agregarPokemonAPokedex($nivel,$resistencia,$apodo,$id_especie,$id_pokedex){
        $query = $this->db->prepare("INSERT INTO pokemon(nivel,resistencia,apodo,id_especie,id_pokedex) VALUES(?,?,?,?,?)");
        $query->execute(array($nivel,$resistencia,$apodo,$id_especie,$id_pokedex));
        return $this->db->lastInsertId();
    }

    function getPokemonPorIdYPokedex($id_pokemon, $id_pokedex){
        $query = $this->db->prepare("SELECT * FROM pokemon WHERE id = ? AND id_pokedex = ?");
        $query->execute(array($id_pokemon,$id_pokedex));
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getCantidadPokemones($id_pokedex){
        $query =$this->db->prepare("SELECT COUNT(*) FROM pokemon WHERE id_pokedex = ?");
        $query->execute(array($id_pokedex));
        return $query->rowCount();
    }

    function eliminarPokemon($id_pokemon,$id_pokedex){
        $query = $this->db->prepare("DELETE FROM pokemon WHERE id = ? AND id_pokedex = ?");
        $stmt = $query->execute(array($id_pokemon, $id_pokedex));
        return $query->rowCount();
    }
   
    // POKEDEX(id: int, id_user: int, version: string)
    // ESPECIEPOKEMON(id: int, nombre: string, tipo: string, debilidad: string)
    // POKEMON(id: int, nivel: int, resistencia: int, apodo: string, id_especie: int, id_pokedex: int)

}

