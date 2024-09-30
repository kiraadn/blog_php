<?php

namespace sistema\Controllers\Admin;

use sistema\Nucleo\Sessao;
use sistema\Nucleo\Helpers;
use sistema\Controllers\UsuarioController;
use sistema\Modelo\PostModel;
use sistema\Modelo\CategoriaModel;
use sistema\Modelo\UsuarioModelo;

/**
 * Description of AdminDashboard
 *
 * @author kira
 */
class AdminDashboard extends AdminController
{

    public function dashboard(): void
    {
        $posts = new PostModel();
        $categorias = new CategoriaModel();
        $usuarios = new UsuarioModelo();

        echo $this->template->renderizar('dashboard.html', [
            'title' => 'Dashboard',
            'posts' => [
                'posts' => $posts->readAll()->ordem('id DESC')->limite(5)->resultado(true),
                'total' => $posts->readAll(null,'COUNT(id)', 'id')->total(),
                'activo' => $posts->readAll('status = :s', 's=1 COUNT(status)', 'status')->total(),
                'inactivo' => $posts->readAll('status = :s', 's=0 COUNT(status)', 'status')->total()
            ],
            'categorias' => [
                'categorias' => $categorias->readAll()->ordem('created_at DESC')->limite(5)->resultado(true),
                'total' => $categorias->readAll()->total(),
                'activo' => $categorias->readAll('status = 1')->total(),
                'inactivo' => $categorias->readAll('status = 0')->total()
            ],
            'usuarios' => [
                'total' => $usuarios->readAll()->total(),
                'activo' => $usuarios->readAll('status = 1')->total(),
                'inactivo' => $usuarios->readAll('status = 0')->total(),
                'admin' => $usuarios->readAll('level = 3')->total(),
                'adminActivo' => $usuarios->readAll('level = 3 AND status = 1')->total(),
                'adminInactivo' => $usuarios->readAll('level = 3 AND status = 0')->total(),
                'usuario' => $usuarios->readAll('level != 3')->total(),
                'usuarioActivo' => $usuarios->readAll('level != 3 AND status = 1')->total(),
                'usuarioInactivo' => $usuarios->readAll('level != 3 AND status = 0')->total(),
                'logins' => $usuarios->readAll()->ordem('last_login DESC')->limite(4)->resultado(true)
            ]
        ]);
    }

    public function logout(): void
    {
        $sessao = new Sessao();
        //guardar o ultimo login
        $usuario = UsuarioController::usuario();
        $usuario->last_logout = date('Y-m-d H:i:s');
        $usuario->save();
        $sessao->clean('usuarioId');

        $this->mensagem->info('Logout feito com sucesso!')->flash();
        Helpers::redirecionar('admin/login');
    }
}
