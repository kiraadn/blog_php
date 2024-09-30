<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;

use sistema\Nucleo\Modelo;

/**
 * Classe PostModel
 *
 * @author kira
 */
class PostModel extends Modelo
{
    /**
     * Metodo para listar todos registos da BD
     * @return array
     */
//    const TABELA = 'posts';
    const TABELA = 'posts_fake';
    
    public function __construct()
    {
        parent::__construct(self::TABELA);
    }
    


    /**
     * Metodo para listar todos registos da BD
     * @return array
     */
    public function search(string $busca): array
    {
        $query = "SELECT * FROM posts WHERE status = 1 AND titulo LIKE '%{$busca}%'";
        $stmt = Conexao::getInstancia()->query($query);

        //Recebe o resultado
        $resultado = $stmt->fetchAll();
        return $resultado;
    }
    
    
    public function categoria(): ?CategoriaModel
    {
        if($this->categoria_id){
            return (new CategoriaModel())->read($this->categoria_id);
        }
        
        return null;
    }

    public function usuario(): ?UsuarioModelo
    {
        if($this->usuario_id){
            return (new UsuarioModelo())->read($this->usuario_id);
        }
        
        return null;
    }
    
    public function usuarioUpdate(): ?UsuarioModelo
    {
        if($this->usuario_updated ){
            return (new UsuarioModelo())->read($this->usuario_updated);
        }
        
        return null;
    }
    
    public function save(): bool
    {
        $this->slug();
        return parent::save();
    }
   
   
    
}
