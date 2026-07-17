<?php

class InicioSesion {
    private ConsultaUsuario $consultaUsuario;

    public function __construct(ConsultaUsuario $consultaUsuario) {
        $this->consultaUsuario = $consultaUsuario;
    }

    public function autenticar(string $ci, string $contra): ?Usuario {
        $usuario = $this->consultaUsuario->buscarUsuario($ci);

        if ($usuario === null) {
            return null;
        }

        if (!$usuario->estaActivo()) {
            return null;
        }

        if ( !password_verify($contra, $usuario->getContra() ) ){
            return null;
        }

        return $usuario;
    }
}

?>