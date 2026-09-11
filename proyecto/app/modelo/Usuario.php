<?php

/**
 * Entidad que representa a un usuario.
 * Los atributos se asignan en el constructor y solo se exponen mediante métodos de acceso.
 * Un mismo usuario puede tener más de un rol.
 *
 * @class Usuario
 */
class Usuario {

    /** Cédula de identidad del usuario (clave primaria). */
    private string $ci;

    /** Contraseña del usuario, almacenada como hash. */
    private string $contra;

    /** Nombre completo del usuario. */
    private string $nombre;

    /** Indica si el usuario posee el rol solicitante. */
    private bool $solicitante;

    /** Indica si el usuario posee el rol técnico. */
    private bool $tecnico;

    /** Indica si el usuario posee el rol administrador. */
    private bool $administrador;

    /** Indica si el usuario está habilitado para operar en el sistema. */
    private bool $activo;

    /**
     * Constructor parametrizado de la entidad Usuario.
     *
     * @param string $ci Cédula de identidad, sin puntos ni guiones.
     * @param string $contra Hash de la contraseña.
     * @param string $nombre Nombre completo del usuario.
     * @param bool $activo TRUE si el usuario está habilitado.
     * @param bool $solicitante TRUE si posee el rol solicitante.
     * @param bool $tecnico TRUE si posee el rol técnico.
     * @param bool $administrador TRUE si posee el rol administrador.
     */
    public function __construct(string $ci, string $contra, string $nombre, bool $activo, bool $solicitante, bool $tecnico, bool $administrador) {
        $this->ci = $ci;
        $this->contra = $contra;
        $this->nombre = $nombre;
        $this->activo = $activo;
        $this->solicitante = $solicitante;
        $this->tecnico = $tecnico;
        $this->administrador = $administrador;
    }

    /**
     * Obtiene la cédula del usuario.
     *
     * @return string La cédula de identidad.
     */
    public function getCi(): string {
        return $this->ci;
    }

    /**
     * Obtiene el hash de la contraseña del usuario.
     *
     * @return string El hash almacenado en la base de datos.
     */
    public function getContra(): string {
        return $this->contra;
    }

    /**
     * Obtiene el nombre del usuario.
     *
     * @return string El nombre completo.
     */
    public function getNombre(): string{
        return $this->nombre;
    }

    /**
     * Indica si el usuario está habilitado.
     *
     * @return bool TRUE si el usuario está activo, FALSE en caso contrario.
     */
    public function estaActivo(): bool {
        return $this->activo;
    }

    /**
     * Indica si el usuario posee el rol solicitante.
     *
     * @return bool TRUE si tiene el rol, FALSE en caso contrario.
     */
    public function getRolSolicitante(): bool {
        return $this->solicitante;
    }

    /**
     * Indica si el usuario posee el rol técnico.
     *
     * @return bool TRUE si tiene el rol, FALSE en caso contrario.
     */
    public function getRolTecnico(): bool {
        return $this->tecnico;
    }

    /**
     * Indica si el usuario posee el rol administrador.
     *
     * @return bool TRUE si tiene el rol, FALSE en caso contrario.
     */
    public function getRolAdministrador(): bool {
        return $this->administrador;
    }

}

?>