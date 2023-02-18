<?php
require_once('./Model/pokemonModel.php');
require_once('./View/pokemonView.php');

    // POKEDEX(id: int, id_user: int, version: string)
    // ESPECIEPOKEMON(id: int, nombre: string, tipo: string, debilidad: string)
    // POKEMON(id: int, nivel: int, resistencia: int, apodo: string, id_especie: int, id_pokedex: int)

class pokemonController{

    private $pokemonView;
    private $pokemonModel;

    public function __construct(){
        $this->pokemonView = new pokemonView();
        $this->pokemonModel = new pokemonModel();
    }

    // Listar los pokemones de una determinada especie y mayores a un nivel en una pokedex específica.
    // - Informar los errores que pueden aparecer.
    // - No se espera que haya un usuario logeado.

    //PUNTO 1
    public function getPokemones($especie, $minNivel){
        //tendre q iniciar sesion?
        $user = $_SESSION['user'];//traigo el usuario de la sesion
        if(!empty($especie)&&!empty($minNivel)&&!empty($user)){//Checkeo que esten llegando los datos
            $especiePokemon = $this->pokemonModel->getEspecie($especie);//voy a buscar la especie pokemon a travez del nombre
            if(!empty($especiePokemon)){
                $pokedex = $this->pokemonModel->getPokedex($user->id);//voy a buscar la pokedex del usuario a travez de su id
                if(!empty($pokedex)){//si el usuario tiene una pokedex
                    $pokemones = $this->pokemonModel->getPokemonesConCondicion($especiePokemon->id, $pokedex->id,$minNivel);//voy a buscar los pokemones de una especie espefica de un usuario especifico y con un minimo de nivel
                    if(!empty($pokemones)){//si hay pokemones que cumplan la condicion
                        $this->pokemonView->showPokemones($pokemones);
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
        $nivel  = $_POST['nivel'];
        $resistencia  = $_POST['resistencia'];
        $apodo  = $_POST['apodo'];
        $id_especie = $_POST['id_especie'];
        if($this->authHelper->estaLogueado()){//verifico que este logueado
            $user = $_SESSION['user'];//traigo el usuario de la sesion
            if(!empty($nivel)&&!empty($resistencia)&&!empty($apodo)&&!empty($user)){//verifico q lleguen los datos
                $pokedex = $this->pokemonModel->getPokedex($user->id_user);//busco la pokedex del usuario
                if(!empty($pokedex)){//si el usuario tiene pokedex
                    $pokemones = $this->pokemonModel->getPokemonesPorPokedex($pokedex->id);//traigo todos los pokemones de esa pokedex
                    $i = 0;
                    $contiene = false;
                    while($contiene == false && $i <= count($pokemones)){//recorro los pokemones
                        //necesito saber si hay un pokemon con la misma especie q voy a insertar
                        $contiene = $this->esMismaEspecie($pokemones[$i], $id_especie );
                    }
                    if($contiene == false){//si entra aca es porque no hay un pokemon de la mismaEspecie
                        $this->pokemonModel->agregarPokemonAPokedex($nivel,$resistencia,$apodo,$id_especie,$pokedex->id);
                        $this->pokemonView->mensaje("Se agrego con exito el pokemon a tu pokedex");
                    }else{
                        $this->pokemonView->mensaje("Ya tienes un pokemon de esta especie");
                    }
                }else{
                    $this->pokemonView->mensaje("El usuario no tiene una pokedex");
                }
            }else{
                $this->pokemonView->mensaje("Ups hubo un problema al recibir la informacion");
            }
        }else{
            $this->pokemonView->mensaje("El usuario no esta logueado");
        }


    }

    function esMismaEspecie($pokemon, $id_especie){
        if(!empty($pokemon)&&!empty($id_especie)){
            if($pokemon->id_especie == $id_especie){
                return true;
            }else{
                return false;
            }
        }
    }



    // Un usuario debe poder eliminar un pokemon de una pokedex propia.
    // *Se debe verificar que la pokedex cuente con al menos 1 pokemon una vez eliminado.
    //     En caso contrario, no permitir la eliminación
    //     -Chequear que el usuario esté logueado
    //     -Informar los errores que pueden aparecer. 

    function eliminarPokemon($id_pokemon){
        if($this->authHelper->estaLogueado()){//verifico que este logueado
            $user = $_SESSION['user'];//traigo el usuario de la sesion
            if(!empty($id_pokemon) && !empty($user)){    
                $pokedex = $this->pokemonModel->getPokedex($user->id_user);
                if(!empty($pokedex)){
                    $pokemon = $this->pokemonModel->getPokemonPorIdYPokedex($id_pokemon,$pokedex->id);
                    if(!empty($pokemon)){
                        $cantidadPokemones = $this->pokemonModel->getCantidadPokemones($pokedex->id);
                        if($cantidadPokemones > 1){
                            $this->pokemonModel->eliminarPokemon($id_pokemon,$pokedex->id);
                            $this->pokemonView->mensaje("Se elimino con exito");   
                        }else{
                            $this->pokemonView->mensaje("No se pudo eliminar el pokemon");   
                        }
                    }else{
                        $this->pokemonView->mensaje("No existe el pokemon q desea eliminar en la pokedex");
                    }
                }else{
                    $this->pokemonView->mensaje("EL usuario no tiene una pokedex");
                }
            }else{
                $this->pokemonView->mensaje("Ups hubo un problema al recibir la informacion");
            }
        }else{
            $this->pokemonView->mensaje("El usuario no esta logueado");
        }
    }

}