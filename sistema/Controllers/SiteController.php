<?php

namespace sistema\Controllers;

use sistema\Nucleo\Controlador;
use sistema\Modelo\PostModel;
use sistema\Modelo\CategoriaModel;
use sistema\Nucleo\Helpers;
use sistema\Biblioteca\Paginacao;

class SiteController extends Controlador
{

    public function __construct()
    {
        parent::__construct('templates/site/views');
    }

    public function index(): void
    {
        $postModel = (new PostModel());
        $posts = (new PostModel())->readAll("status = 1");
        
        $paginacao = new Paginacao($postModel, 8);
        $paginaAtual = $paginacao->obterPaginaAtual();
        $totalPaginas = $paginacao->obterTotalPaginas();
        $linksPaginacao = $paginacao->obterLinksPaginacao();
        $temPaginaAnterior = $paginacao->temPaginaAnterior();
        $temProximaPagina = $paginacao->temProximaPagina();
        $limite = $paginacao->__getLimite();
        
        
      
        echo $this->template->renderizar('index.html', [
            'titulo' => 'Index',
            'slides' => $posts->ordem("id DESC")->limite(4)->resultado(true),
            'posts' => $paginacao->obterRegistrosPaginados(),
            'maisLidos' => (new PostModel())->readAll('status = 1')->ordem('views DESC')->limite(5)->resultado(true),
            'categorias' => $this->categorias(),
            'pagina_atual' => $paginaAtual,
            'total_paginas' => $totalPaginas,
            'links_paginacao' => $linksPaginacao,
            'tem_pagina_anterior' => $temPaginaAnterior,
            'tem_proxima_pagina' => $temProximaPagina,
            'limite' => $limite
        ]);
    }

    public function post(string $slug): void
    {
        $posts = (new PostModel())->readBySlug($slug);
        //Verifica se existe algum post com o id/slug fornecido: - caso nao rediciona para pagina de erro
        if (!$posts) {
            Helpers::redirecionar('404');
        }
        $posts->saveViews();

        echo $this->template->renderizar('post.html', [
            'titulo' => SITE_NOME . ' - ' . $posts->titulo,
            'post' => $posts,
            'maisLidos' => (new PostModel())->readAll('status = 1')->ordem('views DESC')->limite('5')->resultado(true),
            'categorias' => $this->categorias()
        ]);
    }

    public function sobre(): void
    {
        echo $this->template->renderizar('sobre.html', [
            'titulo' => 'sobre',
            'subtitulo' => 'teste de pagina sobre',
            'categorias' => $this->categorias()
        ]);
    }


    public function categoria(string $slug): void
    {
        $categoria = (new CategoriaModel())->readBySlug($slug);
        //Verifica se existe alguma categoria com o id/slug fornecido: - caso nao rediciona para pagina de erro
        
        if (!$categoria) {
            Helpers::redirecionar('404');
        }
        
        $posts = (new PostModel())->readAll("categoria_id={$categoria->id} AND status= 1")->ordem("titulo ASC")->resultado(true);
       
        //Gravar views
        $categoria->saveViews();

        echo $this->template->renderizar('categoria.html', [
            'titulo' => SITE_NOME . ' - ' . $categoria->titulo,
            'posts' => $posts,
            'categorias' => $this->categorias()
        ]);
    }

    public function buscar(): void
    {
        $busca = filter_input(INPUT_POST, 'busca', FILTER_DEFAULT);

        if (isset($busca)) {
            $posts = (new PostModel())->search($busca);

            foreach ($posts as $post) {
                echo "<li class='list-group-item fw-bold'><a href=" . Helpers::url('post/') . $post->id . ">$post->titulo</a></li>";
            }
        }
    }

    public function erro404(): void
    {
        echo $this->template->renderizar('404.html', [
            'titulo' => 'UH OH! Página não encontrada!'
        ]);
    }

    /**
     * Metodo para listar as categorias nas views
     * @return type
     */
    public function categorias()
    {
        return (new CategoriaModel())->readAll("status = 1")->ordem('titulo ASC')->resultado(true);
    }
}
