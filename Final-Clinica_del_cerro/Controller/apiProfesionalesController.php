<?php

class apiProfesionalesController
{

    private $model;

    function __construct()
    {
        $this->model = new profesionalModel();
    }

    function getProfesionales($params = [])
    {
        $profesionales = $this->model->getProfesionales();
        if (empty($profesionales)) {
            return $this->view->responses("No se encontraron profesionales", 404);
        }
        return $this->view->responses($profesionales, 200);
    }
}