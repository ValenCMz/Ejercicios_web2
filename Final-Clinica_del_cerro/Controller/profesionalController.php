<?php

class profesionalController
{
    private $profesionalModel;

    function __construct()
    {
        $this->profesionalModel = new profesionalModel();
    }

    function agregarProfesional()
    {
        if (!$this->authHelper->estaLogueado()) {
            return $this->view->error("No esta logueado");
        }
        if (!$this->authHelper->esAdmin()) {
            return $this->view->error("El usuario no es admin");
        }
        $data = $_POST;
        if (empty($data)) {
            return $this->view->error("Hubo un problema con los datos");
        }
        $agregado = $this->profesionalModel->agregarProfesional($data['nombre'], $data['especialidad']);
        if (empty($agregado)) {
            return $this->view->error("No se pudo agregar con exito");
        }
        return $this->view->exito("Se agrego con exito");
    }
}