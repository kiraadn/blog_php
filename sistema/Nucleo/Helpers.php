<?php

namespace sistema\Nucleo;

use Exception;
use sistema\Nucleo\Sessao;

class Helpers
{
    
    public static  function validarSenha(string $senha): bool
    {
        //tamanho min = 6  e maximo 50
        if(mb_strlen($senha) >= 6  && mb_strlen($senha) <= 20){
            return true;
        } 
        return false;
    }
    
    /**
     * Cria uma senha encriptada
     * @param string $senha
     * @return string
     */
    public static function gerarSenha(string $senha): string
    {
//        $options = [
//            'cost' => 10,
//            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
//        ];
        return password_hash($senha, PASSWORD_DEFAULT);
    }
    
    /**
     * Verifica a senha informada pelo usuario e a da BD
     * @param string $senha
     * @param string $hash
     * @return bool
     */
    public static function verificarSenha(string $senha, string $hash): bool
    {
        return password_verify($senha, $hash);
    }
    
    
    
    /**
     * Instancia e retorna as mensagens flash por sessao
     * @return string|null
     */
    public static function flash(): ?string
    {
        $sessao = new Sessao();
        
        if($flash = $sessao->flash()){
            echo $flash;
        }
            
        return null;
    }
    
   /**
    * Metodo para redicionar as urls nao existentes
    * @param string $url
    * @return void
    */ 
   public static function redirecionar(string $url = null): void
    {
        header('HTTP/1.1 302 Found');
        
        $local = ($url ? self::url($url) : self::url());
       
        header("Location: {$local}");
        exit();
    }

    /**
     * Funcao para Url Amigavel
     * @param string $string
     * @return string
     */
    public static function slug(string $string): string
    {
        $mapa['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþ@#$%&*()_-+={[}]/?̈|;:.,\\\'<>"˚°ª!   ';

        $mapa['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuuyb                                  ';

        $slug = strtr(utf8_decode($string), utf8_decode($mapa['a']), $mapa['b']);
        $slug = strip_tags(trim($slug));
        $slug = str_replace(' ', '-', $slug);
        $slug = str_replace(['-----', '----', '---', '--', '-'], '-', $slug);
        return strtolower(utf8_decode($slug));
    }

    /**
     * Funcao para criar uma data mais bonita ao uysuario como a do canto do windows
     * 
     * @return string
     */
    public static function dataActual(): string
    {
        $diaMes = date('d');
        $diaSemana = date('w');
        $mes = date('n') - 1;
        $ano = date('Y');

        $nomeDiasDaSemana = [
            'domingo',
            'segunda-feira',
            'terça-feira',
            'quarta-feira',
            'quinta-feira',
            'sexta-feira',
            'sabado'
        ];

        $mesesDoAno = [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];

        $dataFormatada = $nomeDiasDaSemana[$diaSemana] . ', ' . $diaMes . ' de ' . $mesesDoAno[$mes] . ' de ' . $ano;

        return $dataFormatada;
    }

    /**
     * Formatar e indicar a url nas rotas e mais
     * @param string $url
     * @return string
     */
    public static function url(string $url = null): string
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

        $ambiente = ($servidor == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);

        if (str_starts_with($url, '/'))
        {
            return $ambiente . $url;
        }

        return $ambiente . '/' . $url;
    }

    public static function localhost(): bool
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

        if ($servidor == 'localhost')
        {
            return true;
        }

        return false;
    }

    /**
     * Validador Expressoes regulares
     */
    public static function limparNumero(string $numero): string
    {
        return preg_replace('/[^0-9]/', '', $numero);
    }

    //Validar CPF

    public static function validarCpf(string $cpf): bool
    {
        $cpf = self::limparNumero($cpf);

        if (mb_strlen($cpf) != 11 OR preg_match('/(\d)\1{10}', $cpf))
        {
            throw new Exception('O CPF precisa ter 11 digitos');
        }

        for ($t = 9; $t < 11; $t++):
            for ($d = 0, $c = 0; $c < $t; $c++):
                $d += $cpf[$c] * (($t + 1) - $c);
            endfor;
            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d)
            {
                throw new Exception('CPF Inválido');
            }

        endfor;

        return true;
    }

    /**
     * Aula 30 - criando o proprio filtro
     */
    public static function validarUrlCriado(string $url): bool
    {
        if (mb_strlen($url) < 10)
        {
            return false;
        }

        if (!str_contains($url, '.'))
        {
            return false;
        }

        if (str_contains($url, 'http://') or str_contains($url, 'https://'))
        {
            return true;
        }
        return false;
    }

    //Aula 29 - Tipos de Filtros
    public static function validarEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function validarUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Funcao para contar Tempo decorrido de uma data
     * @param string $data - recebe a data de cadastro 
     * @return string
     */
    public static function contarTempo(string $data): string
    {
        $agora = strtotime(date('Y-m-d H:i:s'));
        $tempo = strtotime($data);
        //difereca em segundos
        $diferenca = $agora - $tempo;

        $segundos = $diferenca;
        $minutos = round($diferenca / 60);
        $horas = round($diferenca / 3600);
        $dias = round($diferenca / 86400);
        $semanas = round($diferenca / 604800);
        $meses = round($diferenca / 2419200);
        $anos = round($diferenca / 29030400);

        if ($segundos <= 60)
        {
            return 'agora';
        } elseif ($minutos <= 60)
        {
            return $minutos == 1 ? 'há 1 minuto' : 'há ' . $minutos . ' minutos';
        } elseif ($horas <= 24)
        {
            return $horas == 1 ? 'há 1 hora' : 'há ' . $horas . ' horas';
        } elseif ($dias <= 7)
        {
            return $dias == 1 ? 'há 1 dia' : 'há ' . $dias . ' dias';
        } elseif ($semanas <= 4)
        {
            return $semanas == 1 ? 'há 1 semana' : 'há ' . $semanas . ' semanas';
        } elseif ($meses <= 12)
        {
            return $meses == 1 ? 'há 1 mês' : 'há ' . $meses . ' meses';
        } else
        {
            return $anos == 1 ? 'há 1 ano' : 'há ' . $anos . ' anos';
        }
    }
    
    

    /**
     * Funcao para formatar valores numericos em moeda
     * @param float $valor - Recebe o valor a ser formatado
     */
    public static function formatarValor(float $valor = null): string
    {
        return number_format(($valor ? $valor : 10), 2, ',', '.');
    }

    /**
     * Funcao para formatar valores numericos em telefone
     * @param float $valor - Recebe o numero a ser formatado
     */
    public static function formatarTelefone(string $valor = null): string
    {
        return number_format(($valor ?: 0), 0, ' ', ' ');
    }

    public static function saudacao(): string
    {

        $hora = date("H");
        $time = date("H:i:s");

        switch ($hora) {
            case $hora >= 0 && $hora <= 5:
                $saudacao = 'Boa madrugada';
                break;
            case $hora >= 6 and $hora <= 12:
                $saudacao = 'Bom dia';
                break;
            case $hora >= 13 and $hora <= 18:
                $saudacao = 'Boa tarde';
                break;
            default:
                $saudacao = 'Boa noite';
        }


        // return $time . ' ' . $saudacao;
        return $saudacao;
    }

    /**
     * Resume um texto
     * 
     * @param string $texto o texto que vem da BD ou do user
     * @param int $limite quantidades de caracteres do texto resumido
     * @param string $continue optional - o que vem no lugar do texto que nao sera exibido
     * @return string texto resumido 
     */
    public static function resumirTexto(string $texto, int $limite, $continue = '...'): string
    {

        $textoLimpo = trim(strip_tags($texto));
        if (mb_strlen($textoLimpo) <= $limite) :
            return $textoLimpo;
        endif;

        $resumirTexto = mb_substr($textoLimpo, 0, mb_strrpos(
                        mb_substr($textoLimpo, 0, $limite),
                        ''
        ));

        return $resumirTexto . $continue;
    }
}
