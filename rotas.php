<?php

//Arquivo responsavel por gerir todas as rotas do sistema
use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controllers');
    SimpleRouter::get(URL_SITE, 'SiteController@index');
    SimpleRouter::get(URL_SITE . 'sobre', 'SiteController@sobre');
//    SimpleRouter::get(URL_SITE . 'post/{id}', 'SiteController@post');
    SimpleRouter::get(URL_SITE . 'post/{slug}', 'SiteController@post');
//    SimpleRouter::get(URL_SITE . 'categoria/{id}', 'SiteController@categoria');
    SimpleRouter::get(URL_SITE . 'categoria/{slug}', 'SiteController@categoria');
    SimpleRouter::post(URL_SITE . 'buscar', 'SiteController@buscar');

    SimpleRouter::get(URL_SITE . '404', 'SiteController@erro404');

    //    Rotas do Dashboard
    SimpleRouter::group(['namespace' => 'Admin'], function () {

        SimpleRouter::match(['get', 'post'], URL_ADMIN . 'login', 'AdminLogin@login');
         
        //DASHBOARD
        SimpleRouter::get(URL_ADMIN . 'dashboard', 'AdminDashboard@dashboard');
        SimpleRouter::get(URL_ADMIN . 'logout', 'AdminDashboard@logout');

//        ADMIN POSTS
        SimpleRouter::get(URL_ADMIN . 'posts/listar', 'AdminPosts@index');
        SimpleRouter::match(['get', 'post'], URL_ADMIN . 'posts/create', 'AdminPosts@create');
        SimpleRouter::match(['get', 'post'], URL_ADMIN . 'posts/edit/{id}', 'AdminPosts@edit');
        SimpleRouter::get(URL_ADMIN . 'posts/delete/{id}', 'AdminPosts@delete');
        SimpleRouter::get(URL_ADMIN . 'posts/datatable', 'AdminPosts@datatable');

//        ADMIN CATEGORIAS
        SimpleRouter::get(URL_ADMIN . 'categorias/listar', 'AdminCategorias@index');
        SimpleRouter::match(['get', 'post'], URL_ADMIN . 'categorias/create', 'AdminCategorias@create');
        SimpleRouter::match(['get', 'post'], URL_ADMIN . 'categorias/edit/{id}', 'AdminCategorias@edit');
        SimpleRouter::get(URL_ADMIN . 'categorias/delete/{id}', 'AdminCategorias@delete');
        
//        ADMIN USERS
        SimpleRouter::get(URL_ADMIN . 'users/listar', 'AdminUsuarios@index');
        SimpleRouter::match(['get', 'post'], URL_ADMIN . 'users/create', 'AdminUsuarios@create');
        SimpleRouter::match(['get', 'post'], URL_ADMIN . 'users/edit/{id}', 'AdminUsuarios@edit');
        SimpleRouter::get(URL_ADMIN . 'users/delete/{id}', 'AdminUsuarios@delete');
        
    });
    
        


    SimpleRouter::start();
} catch (Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) {
    if (Helpers::localhost()) {
        echo $ex->getMessage();
    } else {
        Helpers::redirecionar('404');
    }
}