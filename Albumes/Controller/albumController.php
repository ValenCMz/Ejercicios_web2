<?php
//ACA HACER LOS REQUIRE

class albumController{
    // Mostrar todas las valoraciones realizadas a un álbum.
    // - Se deben controlar posibles errores.

    // BASE DE DATOS
    // ARTISTA(id: int, nombre: string, premium: boolean)
    // ALBUM(id: int, titulo: string, productor: string, genero: string, 
    // fechaLanzamiento: string, id_artista: int)
    // VALORACION(id: int, estrellas: int, id_album: int,id_user: int)
    private $albumModel;
    private $valoracionesModel;
    function __construct(){
        $this->albumModel = new albumModel();
        $this->valoracionesModel = new valoracionesModel();
    }

    // Punto 1----------------------------------------------------------------------
    function getValoracionesAlbum(){
        //asumo q los datos llegan por el metodo post
        $data = $_POST;
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }
        //voy a buscar el album por el titulo
        $album = $this->albumModel->getAlbumPorTitulo($data['titulo']);
        //chequeo que exista el album
        if(empty($album)){
            return $this->view->error("No existe el album buscado");
        }
        //voy a buscar las valoraciones del album
        $valoraciones = $this->valoracionesModel->getValoracionesPorAlbum($album->id);
        //chequeo que tenga valoraciones
        if(empty($valoraciones)){
            return $this->view->error("El album no tiene valoraciones");
        }
        //renderizo la vista
        return $this->view->exito($valoraciones);
    }

    


}