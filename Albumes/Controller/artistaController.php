<?php

class artistaController{

    private $albumModel;
    private $valoracionesModel;
    function __construct(){
        $this->albumModel = new albumModel();
        $this->valoracionesModel = new valoracionesModel();
    }

    // Buscar todos los artistas que tengan una cantidad de álbumes mayor o igual a cierto número.
    // - Se deben controlar posibles errores.
    // - Se debe mostrar el artista con su cantidad de álbumes.
}