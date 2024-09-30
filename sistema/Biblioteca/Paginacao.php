<?php

namespace sistema\Biblioteca;

use sistema\Nucleo\Modelo;

/**
 * Description of Paginacao
 *
 * @author kira
 */
class Paginacao
{

    private $modelo;
    private $pagina;
    private $limite;

    public function __construct(Modelo $modelo, int $limite)
    {
        $this->modelo = $modelo;
        $this->pagina = (filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?? 1);
        $this->limite = $limite;
    }
    
    public function __getLimite()
    {
        return $this->limite;
    }

    public function obterPaginaAtual(): int
    {
        return $this->pagina;
    }
    public function obterOffset(): int
    {
        return $offset = ($this->pagina - 1) * $this->limite;
    }

    public function obterTotalRegistros(): int
    {
        return $this->modelo->readAll(null, 'COUNT(id)', 'id')->total();
    }

    public function obterRegistrosPaginados(): array
    {
        $offset = ($this->pagina - 1) * $this->limite;
        return $this->modelo->readAll("status = 1")->ordem('id DESC')->limite($this->limite)->offset($offset)->resultado(true);
    }

    public function obterTotalPaginas(): int
    {
        $total = $this->obterTotalRegistros();
        return ceil($total / $this->limite);
    }

    public function obterLinksPaginacao(): array
    {
        $links = [];
        $inicio = max(1, $this->pagina - 3);
        $fim = min($this->obterTotalPaginas(), $this->pagina + 3);

        for ($i = $inicio; $i <= $fim; $i++) {
            $links[] = [
                'pagina' => $i,
                'ativo' => $i == $this->pagina
            ];
        }

        return $links;
    }

    public function temPaginaAnterior(): bool
    {
        return $this->pagina > 1;
    }

    public function temProximaPagina(): bool
    {
        return $this->pagina < $this->obterTotalPaginas();
    }
}
