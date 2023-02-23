<?php

class artistaController{

    private $albumModel;
    private $valoracionesModel;

    private $artistaModel;

    private $view;
    function __construct(){
        $this->albumModel = new albumModel();
        $this->valoracionesModel = new valoracionesModel();
        $this->artistaModel = new artistaModel();
    }

    // Buscar todos los artistas que tengan una cantidad de álbumes 
    //              mayor o igual a cierto número.
    // - Se deben controlar posibles errores.
    // - Se debe mostrar el artista con su cantidad de álbumes.

   //ESTE EJERCICIO CREO Q SE PODRIA SIMPLIFICAR CON UNA CONSULTA A LA BASE DE DATOS
   //NO LOGRO HACERLA---PUNTO 2
    function getArtistas(){
        $data = $_POST;
        //chequeo que lleguen los datos
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }
        $numero = $_POST['numero'];
        //voy a buscar todos los artistas
        $artistas = $this->artistaModel->getArtistas();
        //chequeo q existan artistas
        if(empty($artistas)){
            return $this->view->error("No hay artistas");
        }
        //creo los artistas q cumplen
        $artistasFiltrados = array();
        //recorro todos los artistas
        foreach($artistas as $artista){
            //triago la cantidad de albumes de cada artista
            $cantidadAlbumes = $this->albumModel->getCantAlbumesPorArtista($artista->id);
            //chequeo que cumpla la condicions
            if($cantidadAlbumes >= $numero){
                //creo el artistaFiltrado con los atributos que si cumplen
                $artistaFiltrado = array(
                    "artista" => $artista,
                    "cantidad" => $cantidadAlbumes
                );
            }
            //los agrego al array 
            array_push($artistasFiltrados, $artistaFiltrado);
        }
        if(empty($artistasFiltrados)){
            return $this->view->error("No hay artistas q cumplan la condicion");
        }

        return $this->view->exito($artistasFiltrados);
    }
}