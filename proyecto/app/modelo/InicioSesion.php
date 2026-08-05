<?php

class InicioSesion {

    private AccesoDatosUsuario $accesoDatosUsuario;

    public function __construct(AccesoDatosUsuario $accesoDatosUsuario) {
        $this->accesoDatosUsuario = $accesoDatosUsuario;
    }

    public function autenticar(string $ci, string $contra): ?Usuario {
        $usuario = $this->accesoDatosUsuario->buscarUsuario($ci);
        

        if ($usuario === null) {
            return null;
            //die("No se encontró el usuario");
        }

        if ( !password_verify($contra, $usuario->getContra() ) ){
            return null;
            //die("La contraseña es incorrecta");
        }

        return $usuario;
    }
}

?>