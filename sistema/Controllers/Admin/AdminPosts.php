<?php

namespace sistema\Controllers\Admin;

use sistema\Modelo\PostModel;
use sistema\Modelo\CategoriaModel;
use sistema\Nucleo\Helpers;
use Verot\Upload\Upload;

/**
 * Description of AdminDashboard
 *
 * @author kira
 */
class AdminPosts extends AdminController
{

    private string $capa;

    //DataTables

    public function datatable(): void
    {
        $datatable = $_REQUEST;
        $datatable = filter_var_array($datatable, FILTER_SANITIZE_STRING);

        $limite = filter_var($datatable['length'], FILTER_SANITIZE_NUMBER_INT);
        $offset = filter_var($datatable['start'], FILTER_SANITIZE_NUMBER_INT);
        $busca = $datatable['search']['value'];
        
        $colunas = [
           0 => 'id',
            2 => 'titulo',
            3 => 'categoria_id',
            4 => 'views',
            5 => 'status'
        ];
        
        $ordem =" ".$colunas[$datatable['order'][0]['column']]." ".$datatable['order'][0]['dir']." " ;
        
        
        $posts = (new PostModel());

        if (empty($busca)) {
            $posts->readAll()->ordem($ordem)->limite($limite)->offset($offset);
            $total = (new PostModel())->readAll(null, 'COUNT (id)', 'id')->total();
        } else {
            $posts->readAll("id LIKE '%{$busca}%' OR titulo LIKE '%{$busca}%' ")->limite($limite)->offset($offset);
            $total = $posts->total();
        }

       
        $dados = [];

        foreach ($posts->resultado(true) as $post) {
            $dados[] = [
                $post->id,
                $post->capa,
                $post->titulo,
                $post-> categoria()->titulo ?? '-----',
                Helpers::formatarValor($post->views),
                $post->status,
                date('d/m/Y H:i:s', strtotime( $post->created_at)),
                ' <a href="'.Helpers::url('admin/posts/listar#info'.$post->id).'" data-bs-toggle="offcanvas" class="text-secondary"><i data-bs-toggle="tooltip"  title="Status" class="bi bi-menu-button-wide-fill "></i></a> <a href=" '.Helpers::url('admin/posts/edit/'.$post->id).'" data-bs-toggle="tooltip" title="Editar" class="text-primary mx-2"><i class="bi bi-pencil-fill"></i></a> <a href=" '.Helpers::url('admin/posts/delete/'.$post->id).'" data-bs-toggle="tooltip" title="Remover" class="text-danger"><i class="bi bi-trash-fill"></i></a> 
                    
'
            ];
        }

        $retorno = [
            "draw" => $datatable['draw'],
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $dados
        ];

        echo json_encode($retorno);
    }

    public function index(): void
    {
        $post = new PostModel();
        echo $this->template->renderizar('posts/index.html', [
            'title' => 'Gestão de Posts',
//            'posts' => $post->readAll()->resultado(true),
            'total' => [
                'total' => $post->readAll(null, 'COUNT (id)', 'id')->total(),
                'activo' => 110,
                'inactivo' => 10
            ]
        ]);
    }

    public function create(): void
    {

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {

            //validar dados
            if ($this->validarDados($dados)) {
                $post = new PostModel();

                $post->usuario_id = $this->usuario->id;
                $post->titulo = $dados['titulo'];
                $post->categoria_id = $dados['categoria_id'];
                $post->slug = Helpers::slug($dados['titulo']);
                $post->texto = $dados['texto'];
                $post->status = $dados['status'];
                $post->usuario_updated = $this->usuario->id;
                $post->capa = $this->capa;

                if ($post->save()) {
                    $this->mensagem->sucesso('Post cadastrado com sucesso')->flash();
                    Helpers::redirecionar('admin/posts/listar');
                } else {
                    $this->mensagem->erro($post->erro())->flash();
                    Helpers::redirecionar('admin/posts/listar');
                }
            }
        }
        echo $this->template->renderizar('posts/create.html', [
            'title' => 'Cadastrar Post',
            'categorias' => (new CategoriaModel())->readAll()->resultado(true)
        ]);
    }

    public function edit(int $id): void
    {
        $post = (new PostModel())->read($id);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {

            //validadar dados
            if ($this->validarDados($dados)) {
                $post = (new PostModel())->read($id);

                $post->titulo = $dados['titulo'];
                $post->categoria_id = $dados['categoria_id'];
                $post->slug = Helpers::slug($dados['titulo']);
                $post->texto = $dados['texto'];
                $post->status = $dados['status'];
                $post->updated_at = date('Y-m-d H:i:i');
                $post->usuario_updated = $this->usuario->id;

                if (!empty($_FILES['capa'])) {
                    if ($post->capa && file_exists("uploads/images/{$post->capa}")) {
                        unlink("uploads/images/{$post->capa}");
                        unlink("uploads/images/thumbs/thumb-{$post->capa}");
                    }
                    $post->capa = $this->capa ?? null;
                }
                if ($post->save()) {
                    $this->mensagem->sucesso('Post actualizado com sucesso')->flash();
                    Helpers::redirecionar('admin/posts/listar');
                } else {
                    $this->mensagem->erro($post->erro())->flash();
                    Helpers::redirecionar('admin/posts/listar');
                }
            }
        }
        echo $this->template->renderizar('posts/create.html', [
            'title' => 'Editar Post',
            'post' => $post,
            'categorias' => (new CategoriaModel())->readAll()->resultado(true)
        ]);
    }

    public function delete($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (is_int($id)) {
            $post = (new PostModel())->read($id);
            if (!$post) {
                $this->mensagem->alerta('Post a ser removido não existe!')->flash();
                Helpers::redirecionar('admin/posts/listar');
            } else {
                if ($post->destroy()) {

                    //Deletar o arquivo
                    if ($post->capa && file_exists("uploads/images/{$post->capa}")) {
                        unlink("uploads/images/{$post->capa}");
                        unlink("uploads/images/thumbs/thumb-{$post->capa}");
                    }
                    (new PostModel())->delete("id = {$id}");
                    $this->mensagem->sucesso('Post removido com sucesso')->flash();
                    Helpers::redirecionar('admin/posts/listar');
                } else {
                    $this->mensagem->erro($post->erro())->flash();
                    Helpers::redirecionar('admin/posts/listar');
                }
            }
        }
    }

    /**
     * Validar Dados ao fazer login
     * @param array $dados
     * @return bool
     */
    private function validarDados(array $dados): bool
    {

        if (empty($dados['titulo'])) {
            $this->mensagem->alerta('Escreva um título para o post!')->flash();
            return false;
        }

        if (empty($dados['texto'])) {
            $this->mensagem->alerta('Escreva um texto para o post!')->flash();
            return false;
        }

        //validar e efectuar o upload 
        if (!empty($_FILES['capa'])) {
            $upload = new Upload($_FILES['capa'], 'pt-PT');

            if ($upload->uploaded) {
                $titulo = $upload->file_new_name_body = uniqid() . '-' . Helpers::slug($dados['titulo']);
                $upload->image_text = SITE_NOME;
                $upload->image_text_color = '#000000';
                $upload->image_text_opacity = 80;
                $upload->image_text_position = 'BL';
                $upload->image_text_background = '#FFFFFF';
                $upload->image_text_background_opacity = 50;
                $upload->jpeg_quality = 90;
                $upload->image_convert = 'jpg';
                $upload->process('uploads/images');

                if ($upload->processed) {
                    $this->capa = $upload->file_dst_name;
                    $upload->file_new_name_body = 'thumb-' . $titulo;
                    $upload->image_resize = true;
                    $upload->image_x = 540;
                    $upload->image_y = 304;
                    $upload->image_text = SITE_NOME;
                    $upload->image_text_color = '#000000';
                    $upload->image_text_opacity = 80;
                    $upload->image_text_position = 'BL';
                    $upload->image_text_background = '#FFFFFF';
                    $upload->image_text_background_opacity = 50;
                    $upload->jpeg_quality = 70;
                    $upload->image_convert = 'jpg';
                    $upload->process('uploads/images/thumbs/');
                    $upload->clean();
                } else {
                    $this->mensagem->alerta($upload->error)->flash();
                    return false;
                }
            }
        }
        return true;
    }
}
