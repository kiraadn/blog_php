<?php

namespace sistema\Nucleo;

use PDOException;
use sistema\Nucleo\Conexao;
use sistema\Nucleo\Mensagem;

/**
 * Description of Modelo
 * Super class para os metodos dos modelos, metodos como Cadastrar, listar, actualizar...
 * Os mais comuns
 * 
 * @author kira
 */
abstract class Modelo
{

    protected $dados;
    protected $query;
    protected $erro;
    protected $parametros;
    protected $tabela;
    protected $ordem;
    protected $limite;
    protected $offset;
    protected $mensagem;

    public function __construct(string $tabela)
    {
        $this->tabela = $tabela;
        $this->mensagem = new Mensagem();
    }

    /**
     * Cria ordem de consulta
     * @param string $ordem
     * @return $this
     */
    public function ordem(string $ordem)
    {
        $this->ordem = " ORDER BY {$ordem}";
        return $this;
    }

    /**
     * Cria limites nas query de consulta
     * @param string $limite
     * @return $this
     */
    public function limite(int $limite)
    {
        $this->limite = " LIMIT {$limite}";
        return $this;
    }

     /**
     * Cria offsets nas query de consulta
     * @param string $offset
     * @return $this
     */
    public function offset(string $offset)
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }
    
    //Metodo que retorna os erros
    public function erro()
    {
        return $this->erro;
    }
    
    //Metodo que retorna as mensagens
    public function mensagem()
    {
        return $this->mensagem;
    }
    
    //Metodo que retorna os dados
    public function dados()
    {
        return $this->dados;
    }
    
    /**
     * Metodo Magico 
     * @param type $nome
     * @param type $valor
     */
    public function __set($nome, $valor)
    {
        if(empty($this->dados)){
            $this->dados = new \stdClass();
        }
        $this->dados->$nome = $valor;
    }
    
    /**
     * Usado para verificar com a funcao isset uma propriedade que nao foi definida na classse
     * @param type $name
     */
    public function __isset($nome)
    {
        return isset($this->dados->$nome);
    }
    
    /**
     * Usado quando tentamos usar uma propriedade que nao foi definida na classe ou esta inacessivel
     * @param type $name
     */
      public function __get($nome)
      {
          return $this->dados->$nome ?? null;
      }
    

    /**
     * Mostrar todos resultados da BD
     * 
     * @param string|null $condicao
     * @param string|null $parametros
     * @param string $colunas
     * @return $this
     */
    public function readAll(?string $condicao = null, ?string $parametros = null, string $colunas = '*')
    {
        if ($condicao) {
            $this->query = "SELECT {$colunas} FROM " . $this->tabela . " WHERE {$condicao} ";
            parse_str($parametros, $this->parametros);
            return $this;
        }

        $this->query = "SELECT {$colunas} FROM " . $this->tabela;
        return $this;
    }
    
    /**
     * Busca dados por um id especifico
     * @param type $id
     */
    public function read(int $id)
    {
        $busca = $this->readAll("id = {$id}");
        return $busca->resultado();
    }
    
       /**
     * Busca dados por um slug especifico
     * @param type $id
     */
    public function readBySlug(string $slug)
    {
        $busca = $this->readAll("slug = :s","s={$slug}");
        return $busca->resultado();
    }
    
    /**
     * 
     * @param bool $todos
     * @return null
     */
    public function resultado(bool $todos = false)
    {
        try {
            $stmt = Conexao::getInstancia()->prepare($this->query . $this->ordem ." ". $this->limite." ".$this->offset );
            $stmt->execute($this->parametros);

            //Nenhum resultado
            if (!$stmt->rowCount()) {
                return null;
            }

            //Todos resultado
            if ($todos) {
                return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
            }
            
            //Apenas 1
            return $stmt->fetchObject(static::class);
        } catch (\PDOException $ex) {
            $this->erro = $ex->getMessage();
            return null;
        }
    }

    /**
     * Metodo para cadastro com uma query dinamica
     * @param array $dados
     * @return null
     */
    protected function store(array $dados)
    {
        try {
            $colunas = implode(', ', array_keys($dados));
            $valores = ':' . implode(',:', array_keys($dados));

            $query = "INSERT INTO " . $this->tabela . " ({$colunas}) VALUES ({$valores})";

            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute($this->filtro($dados));

            return Conexao::getInstancia()->lastInsertId();
        } catch (\PDOException $ex) {
            echo $this->erro = $ex->getMessage();
            return null;
        }
    }
    
    
    /**
     * Actualizar dados na BD
     * @param array $dados
     * @param string $condicoes
     * @return null
     */
    protected function update(array $dados, string $condicoes)
    {
        try {
            foreach ($dados as $key => $valor) {
                $set[] = "{$key} = :{$key} ";
            }

            $set = implode(', ', $set);

            $query = "UPDATE " . $this->tabela . " SET {$set} WHERE {$condicoes}";

            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute($this->filtro($dados));

            return ($stmt->rowCount() ?? 1);
        } catch (PDOException $ex) {
            echo $this->erro = $ex->getMessage();
            return null;
        }
    }
    
     public function delete(string $condicoes)
    {
        try {
            $query = "DELETE FROM " . $this->tabela . " WHERE {$condicoes}";

            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute();

            return true;
        } catch (PDOException $ex) {
            $this->erro = $ex->getMessage();
            return null;
        } 
    }


    /**
     * Filtro de dados
     * @param array $dados
     */
    private function filtro(array $dados)
    {
        $filtro = [];
        foreach ($dados as $chave => $valor) {
            $filtro[$chave] = (is_null($valor)) ? null : filter_var($valor, FILTER_DEFAULT);
        }
        return $filtro;
    }

    protected function armazenar()
    {
        $dados = (array) $this->dados;
        return $dados;
    }
    
    /**
     * Guardar ou actualizar dados na BD
     * @return bool
     */
    public function save(): bool
    {
        //CADASTRAR
        if (empty($this->id)) {
           $id = $this->store($this->armazenar());
            if ($this->erro) {
                $this->mensagem->erro('Erro de sistema ao tentar cadastrar os dados');
                return false;
            }
        }
        
        //ACTUALIZAR
        if (!empty($this->id)) {
            $id= $this->id;
            $this->update($this->armazenar(), "id = {$id}");
            if ($this->erro) {
                $this->mensagem->erro('Erro de sistema ao tentar actualizar os dados');
                return false;
            }
        }
        $this->dados = $this->read($id)->dados();
        return true;
    }
    
    //verifica se o id existe para deletar ou nao
    public function destroy()
    {
        if(empty($this->id)){
            return false;
        }
        $deletar = $this->delete("id = {$this->id}");
        return $deletar;
    }




    //Contadores
      public function total(?string $condicao = null): int
    {
      
       $stmt = Conexao::getInstancia()->prepare($this->query);
       $stmt->execute($this->parametros);
       
       return $stmt->rowCount(); //retorna o total de linhas
    }
    
    
    /**
     * Seleciona o id Maximo na tabela em que se trabalha
     * @return int
     */
    private function ultimoId(): int
    {
        return Conexao::getInstancia()->query("SELECT MAX(id) as maximo FROM {$this->tabela}")->fetch()->maximo +1;
    }
    
    /**
     * Estrutura o slug do sistema...
     * @return null
     */
    protected function slug()
    {
        $checkSlug = $this->readAll("slug = :s AND id != :id", "s={$this->slug}&id={$this->id}");
        if($checkSlug->total()){
            $this->slug = "{$this->slug}-{$this->ultimoId()}";
        }
        return null;
    }
    
    public function saveViews()
    {
        $this->views += 1;
        $this->last_view_at = date('Y-m-d H:i:s');
        $this->save();
    }
}
