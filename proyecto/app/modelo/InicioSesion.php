<?php

/**
 * Verifica las credenciales de acceso al sistema. Delega la búsqueda del usuario en AccesoDatosUsuario y compara la contraseña con el hash almacenado.
 *
 * @class InicioSesion
 */
class InicioSesion {

    /** Objeto de acceso a datos utilizado para recuperar el usuario. */
    private AccesoDatosUsuario $accesoDatosUsuario;

    /**
     * Constructor parametrizado.
     *
     * @param AccesoDatosUsuario $accesoDatosUsuario Capa de acceso a datos de usuarios.
     */
    public function __construct(AccesoDatosUsuario $accesoDatosUsuario) {
        $this->accesoDatosUsuario = $accesoDatosUsuario;
    }

    /**
     * Autentica a un usuario a partir de su cédula y contraseña.
     * Se retorna NULL tanto si el usuario no existe como si la contraseña es incorrecta,
     * para no revelar si la cédula está registrada en el sistema.
     *
     * @param string $ci Cédula del usuario, sin puntos ni guiones.
     * @param string $contra Contraseña en texto plano ingresada por el usuario.
     * @return Usuario|null El objeto Usuario si las credenciales son válidas, NULL en caso contrario.
     */
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