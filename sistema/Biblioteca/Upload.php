<?php

namespace sistema\Biblioteca;

/**
 * Description of Upload
 *
 * @author kira
 */
class Upload
{

    private ?string $directorio;
    private ?array $arquivo;
    private ?string $nome;
    private ?string $subDirectorio;
    private ?int $tamanho;
    private ?string $resultado = null;
    private ?string $erro;

    //Metodos Getters

    /**
     * Getter do Resultado
     * @return string|null
     */
    public function getResultado(): ?string
    {
        return $this->resultado;
    }

    /**
     * Getter do Erro
     * @return string|null
     */
    public function getErro(): ?string
    {
        return $this->erro;
    }

    public function __construct(string $directorio = null)
    {
        //Cria directorios automaticamente caso estes nao sejam especificados ao se utiizar a classe
        $this->directorio = $directorio ?? 'uploads';

        if (!file_exists($this->directorio) && !is_dir($this->directorio)) {
            //Se nao for um arquivo e nao for um directorio cria um novo dir
            mkdir($this->directorio, 0755);
        }
    }

    /**
     * Para gravar os arquivos
     *  @return 
     */
    public function arquivo(array $arquivo, string $nome = null, string $subDirectorio = null, int $tamanho = null)
    {
        $this->arquivo = $arquivo;
        $this->nome = $nome ?? pathinfo($this->arquivo['name'], PATHINFO_FILENAME);
        $this->subDirectorio = $subDirectorio ?? 'arquivos';

        //Validacao dos arquivos
        $extensao = pathinfo($this->arquivo['name'], PATHINFO_EXTENSION); //pega a extensao

        $this->tamanho = $tamanho ?? 2; //define o tamanho maximo do ficheiro suportado na BD

        $extensoesPermitidas = [
            'pdf',
            'docx',
            'png',
            'jpeg',
            'jpg',
            'gif'
        ];

        $tiposValidos = [
            'application/pdf',
            'image/png',
            'image/x-png',
            'image/jpeg',
            'image/jpg',
            'image/pjpeg',
            'image/',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($extensao, $extensoesPermitidas)) {
//            $this->resultado = null;
            $this->erro = 'Extensão não permitida! Só pode enviar: .' . implode(' .', $extensoesPermitidas);
        } elseif (!in_array($this->arquivo['type'], $tiposValidos)) {
//            $this->resultado = null;
            $this->erro = 'Tipo de arquivo não permitido! ';
        } elseif ($this->arquivo['size'] > $this->tamanho * (1024 * 1024)) {
//            $this->resultado = null;
            $this->erro = "Arquivo muito grande, permitido até {$this->tamanho}MB";
        } else {
            $this->criarSubDirectorio();
            $this->renomearArquivo();
            $this->moverArquivo();
        }
    }

    /**
     * Estrutura organizadas de upload -- funcao para organizar melhor os uploads
     * @return void
     */
    private function criarSubDirectorio(): void
    {
        if (!file_exists($this->directorio . DIRECTORY_SEPARATOR . $this->subDirectorio) && !is_dir($this->directorio . DIRECTORY_SEPARATOR . $this->subDirectorio)) {
            //cria subsubDirectorios
            mkdir($this->directorio . DIRECTORY_SEPARATOR . $this->subDirectorio, 0755);
        }
    }

    private function renomearArquivo(): void
    {
        $arquivo = uniqid() . '-' . $this->organizarNome($this->nome) . strrchr($this->arquivo['name'], '.');

        // verficar se o arquivo existe dentro do subdirectorio
        if (file_exists($this->directorio . DIRECTORY_SEPARATOR . $this->subDirectorio . DIRECTORY_SEPARATOR . $arquivo)) {
            $arquivo = uniqid() . '-' . $this->organizarNome($this->nome) . strrchr($this->arquivo['name'], '.');
        }

        $this->nome = $arquivo;
    }

    private function moverArquivo(): void
    {
        try {
            if (move_uploaded_file($this->arquivo['tmp_name'], $this->directorio . DIRECTORY_SEPARATOR . $this->subDirectorio . DIRECTORY_SEPARATOR . $this->nome)) {
                $this->resultado = $this->nome;
            } else {
                $this->resultado = null;
                $this->erro = 'Erro ao enviar arquivo';
            }
        } catch (Exception $exc) {
            echo $exc->getMessage;
        }
    }

    private function organizarNome(string $string): string
    {
        $mapa['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþ@#$%&*()_-+={[}]/?̈|;:.,\\\'<>"˚°ª!   ';

        $mapa['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuuyb                                  ';

        $nomeFile = strtr(utf8_decode($string), utf8_decode($mapa['a']), $mapa['b']);
        $nomeFile = strip_tags(trim($nomeFile));
        $nomeFile = str_replace(' ', '-', $nomeFile);
        $nomeFile = str_replace(['-----', '----', '---', '--', '-'], '-', $nomeFile);
        return strtolower(utf8_decode($nomeFile));
    }
}
