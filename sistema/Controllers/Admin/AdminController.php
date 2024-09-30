<?php


namespace sistema\Controllers\Admin;
use \sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Controllers\UsuarioController;
use sistema\Nucleo\Sessao;

/**
 * Description of AdminController
 *
 * @author kira
 */
class AdminController extends Controlador
{
    protected $usuario;


    /**
     * 
     */
    public function __construct()
    {
        parent::__construct('templates/admin/views');
        
        //Bloquenado acesso
        $this->usuario = UsuarioController::usuario();
       
        if(!$this->usuario OR $this->usuario->level !=3){
            $this->mensagem->erro("Faça o login para aceder ao painel de controle")->flash();
            
            //caso nao exista o user limpa a sessao
            $sessao = new Sessao();
            $sessao->clean('usuarioId');
            
            Helpers::redirecionar('admin/login');        
        }
    }
}
