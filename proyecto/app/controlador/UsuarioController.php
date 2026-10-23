<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/UsuarioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";
require_once RUTA_NUCLEO . "/Conexion.php";
require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_NUCLEO . "/Sesion.php";

class UsuarioController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRol("administrador");

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->modificar(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    private function listar(): void
    {
        $conexion = Conexion::conectar();
        $dao = new UsuarioDAO($conexion);
        $usuarios = $dao->listarUsuarios();
        Conexion::desconectar();

        RespuestaJson::exito($usuarios);
    }

    private function alta(): void
    {
        $datos = $this->leerJson();

        $ci = trim($datos["ci"] ?? "");
        $nombre = trim($datos["nombre"] ?? "");
        $apellido = trim($datos["apellido"] ?? "");
        $contra = $datos["contra"] ?? "";
        $repetirContra = $datos["repetirContra"] ?? "";
        $solicitante = (bool) ($datos["solicitante"] ?? false);
        $tecnico = (bool) ($datos["tecnico"] ?? false);
        $administrador = (bool) ($datos["administrador"] ?? false);

        if ($ci === "" || $nombre === "" || $apellido === "" || $contra === "" || $repetirContra === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!preg_match("/^[1-9][0-9]{7}$/", $ci)) {
            RespuestaJson::error("Cédula incorrecta", 422);
        }
        if (strlen($contra) < 12) {
            RespuestaJson::error("La contraseña debe contener al menos 12 caracteres", 422);
        }
        if ($contra !== $repetirContra) {
            RespuestaJson::error("Las contraseñas ingresadas no coinciden", 422);
        }

        $claveHash = password_hash($contra, PASSWORD_DEFAULT);

        $conexion = Conexion::conectar();
        $dao = new UsuarioDAO($conexion);

        $nombreCompleto = $nombre . " " . $apellido;

        if ($dao->existeUsuario($ci)) {
            if (!$dao->estaDeBaja($ci)) {
                Conexion::desconectar();
                RespuestaJson::error("Un usuario con esa cédula ya existe", 409);
            }

            $resultado = $dao->reactivarUsuario($ci, $nombreCompleto, $claveHash, $solicitante, $tecnico, $administrador);
        } else {
            $resultado = $dao->registrarUsuario($ci, $nombreCompleto, $claveHash, $solicitante, $tecnico, $administrador);
        }

        Conexion::desconectar();;

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar el usuario", 400);
        }

        RespuestaJson::exito(["mensaje" => "Usuario creado exitosamente"], 201);
    }

        private function darDeBaja(array $datos): void
    {
        $ci = trim($datos["ci"] ?? "");

        if ($ci === "") {
            RespuestaJson::error("Falta la cédula del usuario", 422);
        }
        if (!preg_match("/^[1-9][0-9]{7}$/", $ci)) {
            RespuestaJson::error("Cédula incorrecta", 422);
        }
        if ($ci === (string) Sesion::ci()) {
            RespuestaJson::error("No puede darse de baja a sí mismo", 403);
        }

        $conexion = Conexion::conectar();
        $dao = new UsuarioDAO($conexion);

        if (!$dao->existeUsuario($ci)) {
            Conexion::desconectar();
            RespuestaJson::error("El usuario no existe", 404);
        }

        $resultado = $dao->darDeBajaUsuario($ci);
        Conexion::desconectar();

        if (!$resultado) {
            RespuestaJson::error("No se pudo dar de baja al usuario", 400);
        }

        RespuestaJson::exito(["mensaje" => "Usuario dado de baja exitosamente"]);
    }

    private function modificar(): void
    {
        $datos = $this->leerJson();

        if (($datos["accion"] ?? "") === "baja") {
            $this->darDeBaja($datos);
            return;
        }

        $ci = trim($datos["ci"] ?? "");
        $nombre = trim($datos["nombre"] ?? "");
        $solicitante = (bool) ($datos["solicitante"] ?? false);
        $tecnico = (bool) ($datos["tecnico"] ?? false);
        $administrador = (bool) ($datos["administrador"] ?? false);

        if ($ci === "" || $nombre === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!preg_match("/^[1-9][0-9]{7}$/", $ci)) {
            RespuestaJson::error("Cédula incorrecta", 422);
        }
        if ($ci === (string) Sesion::ci() && !$administrador) {
            RespuestaJson::error("No puede quitarse a sí mismo el rol de administrador", 403);
        }

        $conexion = Conexion::conectar();
        $dao = new UsuarioDAO($conexion);

        if (!$dao->existeUsuario($ci)) {
            Conexion::desconectar();
            RespuestaJson::error("El usuario no existe", 404);
        }

        $resultado = $dao->modificarUsuario($ci, $nombre, $solicitante, $tecnico, $administrador);
        Conexion::desconectar();

        if (!$resultado) {
            RespuestaJson::error("No se pudo modificar el usuario", 400);
        }

        RespuestaJson::exito(["mensaje" => "Usuario modificado exitosamente"]);
    }

    private function leerJson(): array
    {
        $datos = json_decode(file_get_contents("php://input"), true);

        if (!is_array($datos)) {
            RespuestaJson::error("JSON inválido", 400);
        }

        return $datos;
    }

    
}