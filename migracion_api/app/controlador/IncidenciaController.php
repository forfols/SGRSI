<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/IncidenciaDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_NUCLEO . "/Sesion.php";

class IncidenciaController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarSesion();

        //El token solo se exige en las operaciones que modifican datos
        if (in_array($metodo, ["POST", "PUT", "DELETE"], true)) {
            Token::verificarCSRF();
        }

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->modificar(),
            "DELETE" => $this->baja(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    private function listar(): void
    {
        //El usuario puede tener varios roles, por eso el rol llega por GET (?rol=solicitante|tecnico|administrador)
        $rol = $_GET["rol"] ?? "";

        if (!in_array($rol, ["solicitante", "tecnico", "administrador"], true)) {
            RespuestaJson::error("Rol inválido", 400);
        }

        Sesion::verificarRol($rol);

        $dao = new IncidenciaDAO($this->conectar());
        RespuestaJson::exito($dao->listarIncidencias($rol, (string) $_SESSION["ci"]));
    }

    private function alta(): void
    {
        Sesion::verificarRol("solicitante");
        $datos = $this->leerJson();

        $tipo = trim($datos["tipo"] ?? "");
        $idEquipo = $datos["nroPc"] ?? null;
        $alumno = trim($datos["nombreAlumno"] ?? "");
        $descripcion = trim($datos["descripcion"] ?? "");
        $idRegistroEspacio = (int) ($datos["idRegistroEspacio"] ?? 0);

        if ($tipo === "" || $descripcion === "" || $idRegistroEspacio === 0) {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!in_array($tipo, ["PC", "Otros"], true)) {
            RespuestaJson::error("Tipo de incidencia inválido", 422);
        }

        if ($tipo === "PC") {
            if (empty($idEquipo) || $alumno === "") {
                RespuestaJson::error("Se eligió PC pero falta el equipo o la persona", 422);
            }
            $idEquipo = (int) $idEquipo;
        } else {
            $idEquipo = null;
            $alumno = null;
        }

        $dao = new IncidenciaDAO($this->conectar());
        $resultado = $dao->registrarIncidencia($idRegistroEspacio, $tipo, $idEquipo, $alumno, $descripcion, (string) $_SESSION["ci"]);

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar la incidencia", 400);
        }

        RespuestaJson::exito(["mensaje" => "Se registró la incidencia"], 201);
    }

    private function modificarEstado(array $datos): void
{
    Sesion::verificarRol("tecnico");

    $idIncidencia = (int) ($datos["idIncidencia"] ?? 0);
    $estado = $datos["estado"] ?? "";
    $prioridad = $datos["prioridad"] ?? "";
    $diagnostico = trim($datos["diagnostico"] ?? "");
    $soluciones = trim($datos["soluciones"] ?? "");

    if ($idIncidencia === 0 || $diagnostico === ""
        || !in_array($estado, ["Pendiente", "En proceso", "Terminado"], true)
        || !in_array($prioridad, ["Alto", "Medio", "Bajo"], true)) {
        RespuestaJson::error("Datos inválidos o campos vacíos", 422);
    }
    if ($estado === "Terminado" && $soluciones === "") {
        RespuestaJson::error("Falta la solución", 422);
    }
    if ($soluciones === "") {
        $soluciones = "N/A";
    }

    $dao = new IncidenciaDAO($this->conectar());
    $incidencia = $dao->buscarIncidencia($idIncidencia);

    if ($incidencia === null) {
        RespuestaJson::error("No se encontró la incidencia", 404);
    }
    if ($incidencia["tipoEstado"] !== "Sin asignar" && (string) $incidencia["ciTecnico"] !== (string) $_SESSION["ci"]) {
        RespuestaJson::error("La incidencia la tiene otro técnico", 403);
    }

    if (!$dao->modificarEstado((string) $_SESSION["ci"], $idIncidencia, $estado, $prioridad, $diagnostico, $soluciones)) {
        RespuestaJson::error("No se pudo modificar el estado", 400);
    }

    RespuestaJson::exito(["mensaje" => "Estado actualizado"]);
}    

        private function modificar(): void
    {
        $datos = $this->leerJson();

         if (($datos["accion"] ?? "") === "estado") {
        $this->modificarEstado($datos);
        return;
    }

    Sesion::verificarRol("solicitante");    
        $datos = $this->leerJson();

        $idIncidencia = (int) ($datos["idIncidencia"] ?? 0);
        $tipo = trim($datos["tipoIncidencia"] ?? "");
        $idEquipo = $datos["nroPc"] ?? null;
        $alumno = trim($datos["nombreAlumno"] ?? "");
        $descripcion = trim($datos["descripcion"] ?? "");

        if ($idIncidencia === 0 || $tipo === "" || $descripcion === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!in_array($tipo, ["PC", "Otros"], true)) {
            RespuestaJson::error("Tipo de incidencia inválido", 422);
        }

        if ($tipo === "PC") {
            if (empty($idEquipo) || $alumno === "") {
                RespuestaJson::error("Se eligió PC pero falta el equipo o la persona", 422);
            }
            $idEquipo = (int) $idEquipo;
        } else {
            $idEquipo = null;
            $alumno = null;
        }

        $dao = new IncidenciaDAO($this->conectar());
        $incidencia = $dao->buscarIncidencia($idIncidencia);

        if ($incidencia === null) {
            RespuestaJson::error("No se encontró la incidencia", 404);
        }
        if ((string) $incidencia["ciSolicitante"] !== (string) $_SESSION["ci"]) {
            RespuestaJson::error("Acceso denegado: la incidencia pertenece a otro solicitante", 403);
        }
        if ($incidencia["tipoEstado"] !== "Sin asignar") {
            RespuestaJson::error("La incidencia está siendo procesada por un técnico", 409);
        }

        $resultado = $dao->modificarIncidencia($idIncidencia, $tipo, $idEquipo, $alumno, $descripcion);

        if (!$resultado) {
            RespuestaJson::error("No se pudo modificar la incidencia", 400);
        }

        RespuestaJson::exito(["mensaje" => "Incidencia modificada exitosamente"]);
    }

    private function baja(): void
    {
        Sesion::verificarRol("solicitante");
        $datos = $this->leerJson();

        $idIncidencia = (int) ($datos["idIncidencia"] ?? 0);

        if ($idIncidencia === 0) {
            RespuestaJson::error("Falta el id de la incidencia", 422);
        }

        $dao = new IncidenciaDAO($this->conectar());
        $incidencia = $dao->buscarIncidencia($idIncidencia);

        if ($incidencia === null) {
            RespuestaJson::error("No se encontró la incidencia", 404);
        }
        if ((string) $incidencia["ciSolicitante"] !== (string) $_SESSION["ci"]) {
            RespuestaJson::error("Acceso denegado: la incidencia pertenece a otro solicitante", 403);
        }
        if ($incidencia["tipoEstado"] !== "Sin asignar") {
            RespuestaJson::error("La incidencia está siendo procesada por un técnico", 409);
        }

        $resultado = $dao->eliminarIncidencia($idIncidencia);

        if (!$resultado) {
            RespuestaJson::error("No se pudo eliminar la incidencia", 400);
        }

        RespuestaJson::exito(["mensaje" => "Incidencia eliminada exitosamente"]);
    }

    private function leerJson(): array
    {
        $datos = json_decode(file_get_contents("php://input"), true);

        if (!is_array($datos)) {
            RespuestaJson::error("JSON inválido", 400);
        }

        return $datos;
    }

    private function conectar(): PDO
    {
        try {
            $conectorPDO = ConectorPDO::obtenerInstancia($_ENV['DB_HOST'], (int) $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
            return $conectorPDO->establecerConexion();
        } catch (PDOException $error) {
            RespuestaJson::error("Error de conexión con la base de datos", 500);
        }
    }
}