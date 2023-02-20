<?php
require_once('./Model/pokemonModel.php');
require_once('./View/pokemonView.php');
require_once('./Model/especiePokemonModel.php');

    // POKEDEX(id: int, id_user: int, version: string)
    // ESPECIEPOKEMON(id: int, nombre: string, tipo: string, debilidad: string)
    // POKEMON(id: int, nivel: int, resistencia: int, apodo: string, id_especie: int, id_pokedex: int)

class pokemonController{

    private $pokemonView;
    private $pokemonModel;

    private $especiePokemonModel;
    private $pokedexModel;

    public function __construct(){
        $this->pokemonView = new pokemonView();
        $this->pokemonModel = new pokemonModel();
        $this->especiePokemonModel = new especiePokemonModel();
        $this->pokedexModel = new pokedexModel();
    }

    // Listar los pokemones de una determinada especie y mayores a un nivel en una pokedex específica.
    // - Informar los errores que pueden aparecer.
    // - No se espera que haya un usuario logeado.

    //PUNTO 1
    public function getPokemones(){
        //Asumo q los datos q necesito llegan por post
        $id_especie = $_POST['id_especie'];
        $nivel = $_POST['nivel'];
        $id_pokedex = $_POST['id_pokedex'];
        //checkeo q estos datos esten llegando correctamente
        if(!empty($$id_especie)&&!empty($nivel)&&!empty( $id_pokedex)){
            //voy a buscar la especie pokemon a travez del id
            $especie = $this->especiePokemonModel->getEspeciePorId($id_especie);
            //chequeo que exista esa especie
            if(!empty($especiePokemon)){
                //voy a buscar la pokedex por su id
                $pokedex = $this->pokedexModel->getPokedexPorId($id_pokedex);
                //si hay una pokedex con ese id
                if(!empty($pokedex)){
                    //voy a buscar los pokemons con la condicion pedida
                    $pokemones = $this->pokemonModel->getPokemonesConCondicion($especie, $pokedex,$nivel);//voy a buscar los pokemones de una especie espefica de un usuario especifico y con un minimo de nivel
                    //si hay pokemones que cumplan la condicion
                    if(!empty($pokemones)){
                        $this->pokemonView->showPokemones($pokemones);
                        $this->pokemonView->mensaje("Se encontraron los pokemones");
                    }else{
                        $this->pokemonView->mensaje("No hay pokemones con esas caracteristicas");
                    }
                }else{
                    $this->pokemonView->mensaje("El usuario no tiene una pokedex");
                }
            }else{
                $this->pokemonView->mensaje("No existe esta especie pokemon");
            }
        }else{
            $this->pokemonView->mensaje("Ups hubo un problema al recibir la informacion");
        }
    }

    //PUNTO 2

    // Agregar un pokemon a una pokedex. 
    // *Se debe verificar que la pokedex no cuente actualmente con un pokemon de esa especie
    //     -Chequear que el usuario esté logueado
    //     -Informar los errores que pueden aparecer. 

    function agregarPokemonAPokedex(){
        //chequeo q el usuario este logueado
        if(!$this->authHelper->estaLogueado()){
            return $this->pokemonView->mensaje("El usuario no esta logueado");
        }
        //asumo que los datos llegan por post
        $nivel  = $_POST['nivel'];
        $resistencia  = $_POST['resistencia'];
        $apodo  = $_POST['apodo'];
        $id_especie = $_POST['id_especie'];
        //chequeo que todos los datos lleguen correctamente
        if(empty($nivel)&& empty($resistencia)&& empty($apodo)&& empty($id_especie)&& empty($id_pokedex)){
            return $this->pokemonView->mensaje("No se ingresaron correctamente los datos");
        }
        $id_usuario = $this->authHelper->getIdUsuario();
        if(empty($id_usuario)){
            return $this->pokemonView->mensaje("No se encontro el id del usuario");
        }
        //busco la pokedex por id
        $pokedex = $this->pokedexModel->getPokedexPorUsuario($id_usuario);
        //chequeo que exista una pokedex
        if(empty($pokedex)){
            return $this->pokemonView->mensaje("No se encontro una pokedex");
        }
        //triago los pokemones de esa pokedex
        $especie = $this->especiePokemonModel->getEspeciePorId($id_especie);
        if(empty($especie)){
            return $this->pokemonView->mensaje("No se pudo obtener la especie");
        }
        //voy a buscar un pokemon por especie 
        $pokemon = $this->pokemonModel->getPokemonPorEspecieYId($especie->id,$pokedex->id);
        //si existe un pokemon de esa especie no puedo insertar
        if(!empty($pokemon)){
            return $this->pokemonView->mensaje("Ya tienes un pokemon de esta especie");
        }
        $pokemonAgregado = $this->pokemonModel->agregarPokemonAPokedex($nivel,$resistencia,$apodo,$id_especie,$id_pokedex);
        if(empty($pokemonAgregado)){
            return $this->pokemonView->mensaje("No se agrego con exito"); 
        }
        return $this->pokemonView->mensaje("Se agrego con exito el pokemon a tu pokedex");
   }
        
    // Un usuario debe poder eliminar un pokemon de una pokedex propia.
    // *Se debe verificar que la pokedex cuente con al menos 1 pokemon una vez eliminado.
    //     En caso contrario, no permitir la eliminación
    //     -Chequear que el usuario esté logueado
    //     -Informar los errores que pueden aparecer. 

    function eliminarPokemonRefactor(){
        if(!$this->authHelper->estaLogueado()){
            return $this->pokemonView->mensaje("El usuario no esta logueado");
        }
        $id_pokemon = $_POST['id_pokemon'];
        if(empty($id_pokemon)){
            return $this->pokemonView->mensaje("No se recibio la informacion correctamente");
        }  
        $id_usuario = $this->authHelper->getIdUsuario(); 
        if(empty($id_usuario)){
            return $this->pokemonView->mensaje("no se pudo obtener el id del usuario");
        }
        $pokedex = $this->pokedexModel->getPokedexPorUsuario($id_usuario);
        if(empty($pokedex)){
            return $this->pokemonView->mensaje("No existe esta pokedex");
        }
        $cantidadPokemones = $this->pokemonModel->getCantidadPokemones($pokedex->id);
        if($cantidadPokemones > 1){
            $pokemonEliminado = $this->pokemonModel->eliminarPokemon($id_pokemon,$pokedex->id);
        }
        
        if(empty($pokemonEliminado)){
            return $this->pokemonView->mensaje("No se pudo eliminar el pokemon");   
        }

        return $this->pokemonView->mensaje("Se elimino con exito");   
 
    }

    // PUNTO 4----------------------------------------------------------------------------------
    // a. Defina los endpoints necesarios para dar soporte por API REST a las tres tablas de la BD.
    //     No es necesario implementarlos.
    // b. Siguiendo el patrón MVC implemente la API REST solo para el siguiente requerimiento.
    //  No implemente los MODELOS. Puede usar la Vista de API REST brindada por la cátedra 
    //  (no es necesario copiarla).
    //     -Listar todas las especies de pokemons y listar una sola determinada por su ID.

    // POKEDEX(id: int, id_user: int, version: string)
    // ESPECIEPOKEMON(id: int, nombre: string, tipo: string, debilidad: string)
    // POKEMON(id: int, nivel: int, resistencia: int, apodo: string, id_especie: int, id_pokedex: int)

    // a. ENDPOINT POKEDEX
    //     api/pokedex/        (obtener todos las pokedex)
    //     api/pokedex/ID     (Obtener una pokedex por su id)
    //     api/pokedex/ID     (Eliminar una pokedex por su id)
    //     api/pokedex/ID       (Editar una pokedex por su id)
    //     api/pokedex/        (agregar una pokedex)

    // a. ENDPOINT Pokemon
    //     api/pokemon/        (obtener todos los pokemon)
    //     api/pokemon/ID     (Obtener un pokemon por su id)
    //     api/pokemon/ID     (Eliminar un pokemon por su id)
    //     api/pokemon/ID       (Editar un pokemon por su id)
    //     api/pokemon/        (agregar un pokemon)

    // a. ENDPOINT especiePokemon
    //     api/especiePokemon/        (obtener todos las especiePokemon)
    //     api/especiePokemon/ID     (Obtener una especiePokemon por su id)
    //     api/especiePokemon/ID     (Eliminar una especiePokemon por su id)
    //     api/especiePokemon/ID       (Editar una especiePokemon por su id)
    //     api/especiePokemon/        (agregar una especiePokemon)

   

}

 // b. Siguiendo el patrón MVC implemente la API REST solo para el siguiente requerimiento.
    // No implemente los MODELOS. Puede usar la Vista de API REST brindada por la cátedra 
    // (no es necesario copiarla).
    //    -Listar todas las especies de pokemons y listar una sola determinada por su ID.

    class apiRestController{
        
        private $model;
        private $view;

        function getTodasLasEspeciesPokemon($params=[]){
            $especies = $this->model-getEspeciesPokemon();
            if(!empty($especies)){
                $this->view->responses($especies,200);
            }else{
                $this->view->respones("No se obtuvieron las especies", 404);
            }
        }

        function getEspeciesPorId($params = []){
            $especieId = $params[':ID'];
            if(empty($especieId)){
                $this->view->responses("No existe la especie", 404);
            }
            $especies = $this->model->getEpesciesPorId($especieId);
            if(empty($especies)){
                $this->view->responses("No se encontro la especie", 404);
            }
            $this->view->responses($especies,200);

        }
    }

    //ROUTEAPI

    $router->addRoute('especiesPokemon', 'GET', 'apiRestController', 'getTodasLasEspeciesPokemon');
    $router->addRoute('especiesPokemon/:ID', 'GET' , 'apiRestController', 'getEspeciesPorId');