<?php

namespace sistema\Modelo;

use sistema\Nucleo\Modelo;
use sistema\Nucleo\Sessao;
use sistema\Nucleo\Helpers;

/**
 * Description of UsuarioModelo
 *
 * @author kira
 */
class UsuarioModelo extends Modelo
{

    const TABELA = 'usuarios';

    public function __construct()
    {
        parent::__construct(self::TABELA);
    }

    public function readByEmail(string $email): ?UsuarioModelo
    {
        $busca = $this->readAll("email = :email", "email={$email}");
        return $busca->resultado();
    }

    public function login(array $dados, int $level = 1)
    {
        $usuario = (new UsuarioModelo())->readByEmail($dados['email']);

        //se usuario vazio == nao existe ou nao
        if (!$usuario) {
            $this->mensagem->erro("Dados de Login incorrectos!")->flash();
            return false;
        }

        if (!Helpers::verificarSenha($dados['senha'], $usuario->password)) {
            $this->mensagem->erro("Dados de Login incorrectos!")->flash();
            return false;
        }

        if ($usuario->status != 1) {
            $this->mensagem->alerta("Para fazer login, active sua conta!")->flash();
            return false;
        }

        if ($usuario->level < $level) {
            $this->mensagem->alerta("Você não tem permissão para aceder a esta área!")->flash();
            return false;
        }

        //guardar o ultimo login
        $usuario->last_login = date('Y-m-d H:i:s');
        $usuario->save();

        (new Sessao)->create('usuarioId', $usuario->id);

        $this->mensagem->sucesso("{$usuario->name}, seja bem-vindo ao painel de control")->flash();
        return true;
    }

    public function save(): bool
    {
        //Verificar se o email recebido ja existe na BD
        if ($this->readAll("email = :e AND id != :id", "e={$this->email}&id={$this->id}")->resultado()) {
            $this->mensagem->alerta("O e-mail " . $this->dados->email . " já existe no sistema!");
            return false;
        }

        //segue com a estrutura Pai do metodo save na superclasse Modelo
        parent::save();
        return true;
    }
}
