<?php
    // echo "Arquivo configuracao do sistema";

    //define o fuso horario
    date_default_timezone_set('Africa/Harare');

    //Configurações da Conexão com a BD
    define('DB_HOST', 'localhost');
    define('DB_PORTA', '3306');
    define('DB_NOME', 'blog');
    define('DB_USUARIO', 'root');
    define('DB_SENHA', '');
    
    
    /**
     * Definindo Constantes - aula 31
     */
    define('SITE_NOME', 'Gaarra');
    define('SITE_DESCRICAO', 'Gaarra - Tecnologia em sistemas');


    define('URL_PRODUCAO', 'https://gaarra.com');
    define('URL_DESENVOLVIMENTO', 'http://localhost/blog');


    define('URL_SITE', 'blog/');
    define('URL_ADMIN', 'blog/admin/');