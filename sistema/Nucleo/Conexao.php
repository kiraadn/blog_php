<?php

namespace sistema\Nucleo;

use PDO;
use PDOException;

/**
 * Classe Conexao
 *
 * @author kira
 */
class Conexao
{

    private static $instancia;

    public static function getInstancia(): PDO
    {
        if (empty(self::$instancia)) {
            //try catch
            try {
                //criamos uma instancia
                self::$instancia = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NOME,DB_USUARIO ,DB_SENHA, [
                    //garante que o charset do PDO seja o mesmo da Base de Dados
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    //Todo erro atraves da PDO sera uma escepcao
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    //Converte qualquer resultado como um objecto anonimo
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    //garante que o nome das colunas da BD seja utilizado
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]);
            } catch (PDOException $ex) {
                die("Erro de Conexão:: " . $ex->getMessage());
            }

        }
        return self::$instancia;
    }
    
    //Padrão Single_Ton
    protected function __construct()
    {
        
    }
    //Evita comm que a classe seja clonada
    private function __clone():void
    {
        
    }
}
