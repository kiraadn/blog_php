<?php

namespace sistema\Nucleo;

/**
 * Classe Mensagem
 */
class Mensagem
{

    //visibilidade de atributos: public, private e protected
    private $texto;
    private $css;
    private $sweet;

    /**
     * Metodos Magicos
     */
    public function __toString()
    {
        return $this->renderizar();
    }

    //Metodo para mostrar mensagens de sucesso
    public function sucesso(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-success';
        $this->sweet = 'success';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    //Metodo para mostrar mensagens de erro
    public function erro(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-danger';
        $this->sweet = 'error';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    //Metodo para mostrar mensagens de alerta
    public function alerta(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-warning';
        $this->sweet = 'warning';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    //Metodo para mostrar mensagens de informar
    public function info(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-primary';
        $this->sweet = 'warning';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    /**
     * Metodo para renderiar a mensagem
     * @return string
     */
    public function renderizar(): string
    {

        return "<div id='autoCloseAlert' class='{$this->css} alert-dismissible fade show'>{$this->texto}</div>  <script type='text/javascript'>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: '{$this->sweet}',
            title: '{$this->texto}'
        })
    </script>"
                . "<script>
    // Fecha o alerta automaticamente após 2.8 segundos (2800 milissegundos)
    setTimeout(function() {
        var autoCloseAlert = document.getElementById('autoCloseAlert');
        autoCloseAlert.classList.remove('show');
        autoCloseAlert.classList.add('d-none');
    }, 20800);
</script>
";
    }

    /**
     * Metodo para Filtrar as mensagens
     * filter_var - filtra possiveis ataques ou fallhar da input
     * strip_tags - exclui todas as tags que esteja, na mensagem
     * 
     * @param string $mensagem - Mensagem a ser exibida
     * @return string
     */
    private function filtrar(string $mensagem): string
    {
        return filter_var(strip_tags($mensagem), FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /**
     * Metodo para criar mesagens flash/instataneas
     * @return void
     */
    public function flash(): void
    {
        (new Sessao())->create('flash', $this);
    }
}
