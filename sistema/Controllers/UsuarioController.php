<?php

namespace sistema\Controllers;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;
use sistema\Modelo\UsuarioModelo;

class UsuarioController extends Controlador
{

    public function __construct()
    {
        parent::__construct('templates/users/views');
    }
    
    public static function usuario(): ?UsuarioModelo
    {
        $sessao = new Sessao();
        
        if(!$sessao->view('usuarioId')){
            return null;
        }
        return (new UsuarioModelo())->read($sessao->usuarioId);
    }
    

}
