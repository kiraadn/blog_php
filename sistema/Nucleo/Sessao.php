<?php

namespace sistema\Nucleo;


class Sessao
{
    public function __construct()
    {
        //verifica se existe uma id de sessao para cria uma nova ou nao
        if(!session_id()){
            session_start();
        }
    }
    
    
    
    /**
     * Metodo para criar uma sessao
     * @param string $chave
     * @param mixed $valor
     * @return Sessao
     */
    public function create(string $chave, mixed $valor): Sessao
    {
        $_SESSION[$chave] = (is_array($valor) ? (object) $valor : $valor);
        
        return $this;
    }
    
     /**
      * Metodo para carregar uma sessao
      * @return object|null
      */
    public function carregar():?object
    {
        return (object) $_SESSION;
    }
       
    /**
     * Metodo para checar/verificar uma sessao
     * @param string $chave
     * @return bool
     */
    public function view(string $chave): bool
    {
        return (isset($_SESSION[$chave]));
    }
      
    /**
     * Metodo para limpar uma sessao
     * @param string $chave
     * @return Sessao
     */
    public function clean(string $chave): Sessao
    {
        unset($_SESSION[$chave]);
        return $this;
    }
    
    /**
     * Metodo para deletar uma sessao
     * @return Sessao
     */
     
    public function delete(): Sessao
    {
        session_destroy();
        return $this;
    }
    
    /**
     * Vai permitir acessar VARs privadas
     * @param type $atributo
     * @return type
     */
    
    public function __get($atributo)
    {
        if(!empty($_SESSION[$atributo])){
            return $_SESSION[$atributo];
        }
    }
    
    /**
     * Permite verificar msg e apagar quando ja exibidas
     * @return Mensagem|null
     */
    public function flash(): ?Mensagem
    {
        if($this->view('flash')){
            $flash = $this->flash;
            $this->clean('flash');
            return $flash;
        }
        return null;
    }
}
