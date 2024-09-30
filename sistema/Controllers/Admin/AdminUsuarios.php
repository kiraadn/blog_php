<?php

namespace sistema\Controllers\Admin;

use sistema\Modelo\UsuarioModelo;
use sistema\Nucleo\Helpers;

/**
 * Description of AdminUsuarios
 *
 * @author kira
 */
class AdminUsuarios extends AdminController
{

    public function index(): void
    {
        $usuario = new UsuarioModelo();

        echo $this->template->renderizar('usuarios/index.html', [
            'title' => 'Gestão de Usuários',
            'usuarios' => $usuario->readAll()->resultado(true),
            'total' => [
                'total' => $usuario->total(),
                'activo' => $usuario->readAll('status = 1')->total(),
                'inactivo' => $usuario->readAll('status = 0')->total(),
                'usuarios' => $usuario->readAll('level != 3')->total(),
                'usuariosActivo' => $usuario->readAll('status = 1 AND level != 3')->total(),
                'usuariosInactivo' => $usuario->readAll('status = 0 AND level != 3')->total(),
                'admin' => $usuario->readAll('level = 3')->total(),
                'adminActivo' => $usuario->readAll('status = 1 AND level = 3')->total(),
                'adminInactivo' => $usuario->readAll('status = 0 AND level = 3')->total()
            ]
        ]);
    }

    /**
     * Cadastro
     * @return void
     */
    public function create(): void
    {

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {

            if ($this->validarDados($dados)) {

                //validar Dados
                if (empty($dados['password']) OR ($dados['password'] !== $dados['repetir_password'])) {
                    $this->mensagem->alerta('As senhas do usuário não conferem!')->flash();
                } else {
                    $usuario = new UsuarioModelo();

                    $usuario->name = $dados['name'];
                    $usuario->email = $dados['email'];
                    $usuario->password = Helpers::gerarSenha($dados['password']);
                    $usuario->level = $dados['level'];
                    $usuario->status = $dados['status'];

                    if ($usuario->save()) {
                        $this->mensagem->sucesso('Usuário cadastrado com sucesso')->flash();
                        Helpers::redirecionar('admin/users/listar');
                    } else {
                        $usuario->mensagem()->flash();
                    }
                }
            }
        }
        echo $this->template->renderizar('usuarios/create.html', [
            'title' => 'Cadastrar Usuário'
        ]);
    }

    /**
     * Editar
     * @param int $id
     * @return void
     */
    public function edit(int $id): void
    {
        $usuario = (new UsuarioModelo())->read($id);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {
            if ($this->validarDados($dados)) {

                $usuario = (new UsuarioModelo())->read($id);

                $usuario->name = $dados['name'];
                $usuario->email = $dados['email'];
                $usuario->password = (!empty($dados['password'])) ? Helpers::gerarSenha($dados['password']) : $usuario->password;
                $usuario->level = $dados['level'];
                $usuario->status = $dados['status'];
                $usuario->updated_at = date('Y-m-d H:i:i');

                if ($usuario->save()) {
                    $this->mensagem->sucesso('Usuário actualizado com sucesso')->flash();
                    Helpers::redirecionar('admin/users/listar');
                } else {
                    $usuario->mensagem()->flash();
                }
            }
        }
        echo $this->template->renderizar('usuarios/create.html', [
            'title' => 'Editar Usuário',
            'usuario' => $usuario
        ]);
    }

    public function delete($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (is_int($id)) {
            $usuarios = (new UsuarioModelo())->read($id);
            if (!$usuarios) {
                $this->mensagem->alerta('Usuario a ser removido não existe!')->flash();
                Helpers::redirecionar('admin/users/listar');
            } else {
                if ($usuarios->destroy()) {
                    (new UsuarioModelo())->delete("id = {$id}");
                    $this->mensagem->sucesso('Usuario removido com sucesso')->flash();
                    Helpers::redirecionar('admin/users/listar');
                } else {
                    $this->mensagem->erro($usuarios->erro())->flash();
                    Helpers::redirecionar('admin/users/listar');
                }
            }
        }
    }

    public function validarDados(array $dados): bool
    {
        if (empty($dados['name'])) {
            $this->mensagem->alerta('Introduza o nome do usuário.')->flash();
            return false;
        }

        if (empty($dados['email'])) {
            $this->mensagem->alerta('Introduza o e-mail do usuário.')->flash();
            return false;
        }

        if (!Helpers::validarEmail($dados['email'])) {
            $this->mensagem->alerta('Introduza um e-mail  válido.')->flash();
            return false;
        }
        
        if(!empty($dados['password'])){
           if(!Helpers::validarSenha($dados['password'])) {
               $this->mensagem->alerta('A senha deve ter entre 6 e 20 caracteres.')->flash();
               return false;
           }
        }

        return true;
    }
}
