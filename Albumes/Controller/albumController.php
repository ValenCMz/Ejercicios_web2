<?php
//ACA HACER LOS REQUIRE

class albumController{
    // Mostrar todas las valoraciones realizadas a un álbum.
    // - Se deben controlar posibles errores.


    private $albumModel;
    private $valoracionesModel;
    private $artistasModel;
    function __construct(){
        $this->albumModel = new albumModel();
        $this->valoracionesModel = new valoracionesModel();
        $this->artistasModel = new artistasModel();
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

    // Valorar un álbum.
    // - Se deben controlar posibles errores.
    // - Chequear que el usuario no haya valorado el artista anteriormente.
    // - Si el usuario ya lo valoró, se reemplaza si la nueva es menor.

    //Para chequear que el usuario no haya valorado el artista anteriormente
        //primero voy a buscar el id_artista por el album que quiero valorar
        //necesito el id_artista para ir a buscar todos los albumes de este artista
        //por cada album del artista
            //me fijo si el usuario ya valoro un album del artista
                //si valoro un album del artista significa q valoro al artista
    function insertarValoracion(){
        //asumo q los datos llegan por post
        $data = $_POST;
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }
        //chequeo q el usuario este logueado
        if(!$this->authHelper->estaLogueado){
            return $this->view->error("El usuario no esta logueado");
        }
        $estrellas = $data['estrellas'];
        $id_album = $data['id_album'];
        //obtengo el id del usuario
        $id_user = $this->authHelper->getIdUser();
        if(empty($id_user)){
            return $this->view->error("No se pudo obtener el id del usuario");
        }
        //obtengo el id del artista a travez de su album
        $id_artista = $this->albumModel->getIdArtistaPorAlbum($id_album);
        if(empty($id_artista)){
            return $this->view->error("El artista no tiene ese album");
        }
        //obtengo los albumes de ese artista
        $albumes = $this->albumModel->getAlbumesPorArtista($id_artista);
        if(empty($albumes)){
            return $this->view->error("No se encontraron albumes del artista dado");
        }
        foreach($albumes as $album){
            $valoracion_anterior = $this->valoracionesModel->getValoracionDelAlbumPorElUsuario($album->id, $id_user);
        }
        if(!empty($valoracion_anterior)){
            if($valoracion_anterior->estrellas > $estrellas){
               $seActualizo =  $this->valoracionesModel->updateValoracion($estrellas,$album->id,$id_user);
               if(empty($seActualizo)){
                    return $this->view->error("No se pudo actualizar la valoracion");
               }
               return $this->view->exito("El usuario ya a valorado este artista y se actualizo la valoracion");
            }
        }
        $seInsertoLaValoracion = $this->valoracionesModel->insertarValoracion($estrellas,$id_album,$id_user);
        if(empty($seInsertoLaValoracion)){
            return $this->view->error("No se pudo valorar el album");
        }
        return $this->view->exito("Se valoro con exito el album");

    }
   

      // Agregar un álbum al sistema.
    // - Se debe controlar que el usuario esté logueado al sistema.
    // - Se deben controlar posibles errores de carga.
    // - Se debe controlar que no exista un álbum con el mismo nombre.
    // - SI el álbum pertenece a un artista premium, se debe insertar 
    // automáticamente una valoración de 5 estrellas para ese álbum.

    function insertarAlbum(){
        if(!$this->authHelper->estaLogueado()){
            return $this->view->error("El usuario no esta logueado");
        }
        $id_user = $this->authHelper->getIdUser();
        if(empty($id_user)){
            return $this->view->error("No se pudo obtener el id del usuario");
        }
        $data = $_POST;
        if(empty($data)){
            return $this->view->error("Hubo un problema con los datos");
        }
        $album = $this->albumModel->getAlbumPorTitulo($data['titulo']);
        if(!empty($album)){
            return $this->view->error("Ya existe un album con este nombre");
        }
            // - SI el álbum pertenece a un artista premium, se debe insertar 
            // automáticamente una valoración de 5 estrellas para ese álbum.
        $artista = $this->artistasModel->getArtistaPorId($data['id_artista']);
        if(empty($artista)){
            return $this->view->error("No existe artista con ese id");
        }
        $seInserto = $this->albumModel->insertarAlbum($data['titulo'],$data['productor'],$data['genero'],$data['fechaLanzamiento'],$data['id_artista']);
        if(empty($seInserto)){
            return $this->view->error("No se pudo insertar el album con exito");
        }
        if($artista->premium==true){
            $this->valoracionesModel->insertarValoracion(5,$seInserto,$id_user);
            return $this->view->exito("Se inserto con exito el album y se le agrego una valoracion de 5 estrellas");
        }else{
            return $this->view->exito("Se inserto con exito el album");
        }
    }
}
