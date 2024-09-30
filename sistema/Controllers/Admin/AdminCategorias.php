<?php

namespace sistema\Controllers\Admin;

use sistema\Modelo\CategoriaModel;
use sistema\Nucleo\Helpers;
use sistema\Modelo\PostModel;

/**
 * Description of AdminCategorias
 *
 * @author kira
 */
class AdminCategorias extends AdminController
{

    public function index(): void
    {
        $categoria = new CategoriaModel();
        echo $this->template->renderizar('categorias/index.html', [
            'title' => 'Gestão de Categorias',
            'categorias' => $categoria->readAll()->resultado(true),
            'total' => [
                'total' => $categoria->total(),
                'activo' => $categoria->readAll('status = 1')->total(),
                'inactivo' => $categoria->readAll('status = 0')->total(),
                'titulo' => 'Total Categorias'
            ]
        ]);
    }

    public function create(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {
            //validar dados
            if ($this->validarDados($dados)) {

                $categoria = (new CategoriaModel())->readBySlug(Helpers::slug($dados['titulo']));
                if ($dados['titulo'] == $categoria->titulo) {
                    $this->mensagem->alerta('Esta Categoria já existe no sistema!')->flash();
                    Helpers::redirecionar('admin/categorias/create');
                }

                $categorias = new CategoriaModel();
                $categorias->titulo = $dados['titulo'];
                $categorias->texto = $dados['texto'];
                $categorias->slug = Helpers::slug($dados['titulo']);
                $categorias->status = $dados['status'];

                if ($categorias->save()) {
                    $this->mensagem->sucesso('Categoria cadastrada com sucesso')->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $this->mensagem->erro($categoria->erro())->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                }
            }
        }
        echo $this->template->renderizar('categorias/create.html', [
            'title' => 'Cadastrar Categoria',
            'ficha' => 'Cadastro',
            'categoria' => $dados
        ]);
    }

    public function edit(int $id): void
    {
        $categoria = (new CategoriaModel())->read($id);

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {
            if ($this->validarDados($dados)) {
                $categoria = (new CategoriaModel())->read($id);

                $categoria->titulo = $dados['titulo'];
                $categoria->texto = $dados['texto'];
                $categoria->slug = (is_null($categoria->slug)) ? Helpers::slug($dados['titulo']) : $categoria->slug;
                $categoria->status = $dados['status'];
                $categoria->updated_at = date('Y-m-d H:i:i');

                if ($categoria->save()) {
                    $this->mensagem->sucesso('Categoria actualizada com sucesso')->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $this->mensagem->erro($categoria->erro())->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                }
            }
        }

        echo $this->template->renderizar('categorias/create.html', [
            'title' => 'Editar Categoria',
            'ficha' => 'Edição',
            'categoria' => $categoria
        ]);
    }

    private function validarDados(array $dados): bool
    {
        if (empty($dados['titulo'])) {
            $this->mensagem->alerta('Escreva um título para a Categoria')->flash();
            return false;
        }

        return true;
    }

    /**
     * Deletar dados
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        //Vamos apagar
        $categoria = (new CategoriaModel())->read($id);

        if (is_int($id)) {
            //Verificar se a categoria esta associada a um post
            $post = (new PostModel())->readAll(" categoria_id = {$id}")->resultado(true);
            if ($post) {
                $this->mensagem->erro('Categoria associada a um post, impossível deletar!')->flash();
                Helpers::redirecionar('admin/categorias/listar');
            } elseif (!$categoria) {
                $this->mensagem->alerta('Categoria a ser removida não existe!')->flash();
                Helpers::redirecionar('admin/categorias/listar');
            } else {
                if ($categoria->destroy()) {
                    (new CategoriaModel())->delete("id = {$id}");
                    $this->mensagem->sucesso('Categoria removida com sucesso!')->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $this->mensagem->erro($categoria->erro())->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                }
            }
        }
    }
}
