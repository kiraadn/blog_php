<?php

namespace sistema\Controllers\Admin;

use \sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\UsuarioModelo;
use sistema\Controllers\UsuarioController;

/**
 * Description of AdminLogin
 *
 * @author kira
 */
class AdminLogin extends Controlador
{

    /**
     * 
     */
    public function __construct()
    {
        parent::__construct('templates/admin/views');
    }

    /**
     * Validar Dados de Login - Form de login
     */
    public function login()
    {
        //redirecionamento automatico
        $usuario = UsuarioController::usuario();
        if($usuario && $usuario->level == 3){
            Helpers::redirecionar('admin/dashboard');
        }
        
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {
            //verifica se ha dados vazios
            if(in_array('', $dados)){
                $this->verificarDados($dados);
            } else{ 
                // caso nao
                $usuario = (new UsuarioModelo())->login($dados, 3);
                if ($usuario) {
                    Helpers::redirecionar('admin/dashboard', []);
                }
            }   

        }
        echo $this->template->renderizar('login.html', []);
    }

    /**
     * Verifica de os dados do Form de login froam preenchidos
     * @param array $dados
     * @return bool
     */
    private function verificarDados(array $dados): bool
    {
        if (empty($dados['email'])) {
            $this->mensagem->alerta('Campo email é obrigatório ')->flash();
            return false;
        }
        if (empty($dados['senha'])) {
            $this->mensagem->alerta('Campo senha é obrigatório ')->flash();
            return false;
        }
        return true;
    }
}
