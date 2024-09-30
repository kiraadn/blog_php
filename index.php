<?php

//Arquivo index responsavel pela inicializacao do sistema
require 'vendor/autoload.php';
use sistema\Modelo\PostModel;

//echo $pagina = (filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?? 1 );

require 'rotas.php';
//$limite = 10;
//$offset = ($pagina - 1) * $limite;
//echo '<hr>';
//$posts = (new PostModel());
//
//$total = $posts->readAll(null, 'COUNT(id)','id')->total();
//
//$paginar = $posts->readAll()->limite($limite)->offset($offset)->resultado(true);
//
////Para arrays/qnt de dados menores
////$paginar = array_slice($posts, $offset, $limite);
//
////total  = ceil (arredondar
//$total = ceil($total / $limite);
//
//foreach ($paginar as $posts) {
//    echo $posts->id .' - ' .$posts->titulo . "<br>";
//}
//
////links de navegacao
//echo '<hr>';
//echo "Página {$pagina} de {$total}";
//echo '<hr>';
//
////Botoes de navegacao
//if ($pagina > 1) {
//    echo '<a href = "?pagina=' . ($pagina - 1) . '">Anterior</a>';
//}
//$inicio = max(1, $pagina - 3);
//$fim = min($total, $pagina + 3);
//
////Links do meio
//for ($i = $inicio; $i <= $fim; $i++) {
//    if ($i == $pagina) {
//        echo ' ' . $i . ' ';
//    } else {
//        echo ' ' . '<a href = "?pagina=' . $i . '">' . $i . '</a>' . ' ';
//    }
//}
//
////Link avancar
//if ($pagina < $total) {
//    echo '<a href = "?pagina=' . ($pagina + 1) . '">Proxima</a>';
//}
//
//echo '<hr><hr>';
//
