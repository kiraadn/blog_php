<?php

namespace sistema\Suporte;

use Twig\Lexer;
use sistema\Nucleo\Helpers;
use Twig\TwigFunction;
use sistema\Controllers\UsuarioController;

class Template
{

    private \Twig\Environment $twing;

    /**
     * recebe um directorio
     */
    public function __construct(string $diretorio)
    {
        $loader = new \Twig\Loader\FilesystemLoader($diretorio);
        $this->twing = new \Twig\Environment($loader);

        //Necessario para carregar/criar funcoes par usar directamente nas views 
        $lexer = new Lexer($this->twing, array(
            $this->helpers()
        ));
        $this->twing->setLexer($lexer);
    }

    /**
     * retorna a funcao render do twing
     */
    public function renderizar(string $view, array $dados): string
    {
        return $this->twing->render($view, $dados);
    }

    private function helpers(): void
    {
        array(
            $this->twing->addFunction(
                    new \Twig\TwigFunction('url', function (string $url = null) {
                                return Helpers::url($url);
                            })
            ),
            $this->twing->addFunction(
                    new \Twig\TwigFunction('saudacao', function () {
                                return Helpers::saudacao();
                            })
            ),
            $this->twing->addFunction(
                    new \Twig\TwigFunction('resumirTexto', function (string $texto, int $limite) {
                                return Helpers::resumirTexto($texto, $limite);
                            })
            ),
            $this->twing->addFunction(
                    new \Twig\TwigFunction('flash', function () {
                                return Helpers::flash();
                            })
            ),
            $this->twing->addFunction(
                    new \Twig\TwigFunction('usuario', function () {
                                return UsuarioController::usuario();
                            })
            ),
            $this->twing->addFunction(
                    new \Twig\TwigFunction('contarTempo', function (string $data) {
                                return Helpers::contarTempo($data);
                            })
            ),           
            $this->twing->addFunction(
                    new \Twig\TwigFunction('tempoCarregamento', function () {
                        
                            $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
                                return number_format($time,4);
                            })
            ),           
        );
    }
}
