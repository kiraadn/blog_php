<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;

use sistema\Nucleo\Modelo;
use sistema\Modelo\PostModel;
/**
 * Description of CategoriaModel
 *
 * @author kira
 */
class CategoriaModel extends Modelo
{
    
    const TABELA = 'categorias';

    public function __construct()
    {
        parent::__construct(self::TABELA);
    }
    
}
