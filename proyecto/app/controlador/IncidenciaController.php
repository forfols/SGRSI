<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/IncidenciaDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";
require_once RUTA_NUCLEO . "/Conexion.php";
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
        //Menú desplegable de incidencias generales: cualquier usuario con sesión
        if (isset($_GET["general"])) {
            $conexion = Conexion::conectar();
            $lista = (new IncidenciaDAO($conexion))->listarIncidenciasGenerales();
            Conexion::desconectar();

            RespuestaJson::exito($lista);
        }

        //El usuario puede tener varios roles, por eso el rol llega por GET (?rol=solicitante|tecnico|administrador)
        $rol = $_GET["rol"] ?? "";

        if (!in_array($rol, ["solicitante", "tecnico", "administrador"], true)) {
            RespuestaJson::error("Rol inválido", 400);
        }

        Sesion::verificarRol($rol);

        $dao = new IncidenciaDAO(Conexion::conectar());
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
        $idIncidenciaGeneral = (int) ($datos["idIncidenciaGeneral"] ?? 0);

        if ($tipo === "" || $idRegistroEspacio === 0) {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!in_array($tipo, ["PC", "Otros"], true)) {
            RespuestaJson::error("Tipo de incidencia inválido", 422);
        }

        $this->validarIncidenciaGeneral($tipo, $idIncidenciaGeneral, $descripcion);

        if ($tipo === "PC") {
            if (empty($idEquipo) || $alumno === "") {
                RespuestaJson::error("Se eligió PC pero falta el equipo o la persona", 422);
            }
            $idEquipo = (int) $idEquipo;
        } else {
            $idEquipo = null;
            $alumno = null;
            $idIncidenciaGeneral = null;
        }

        $dao = new IncidenciaDAO(Conexion::conectar());
               $resultado = $dao->registrarIncidencia($idRegistroEspacio, $tipo, $idEquipo, $alumno, $descripcion, $idIncidenciaGeneral, (string) $_SESSION["ci"]);

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

    $dao = new IncidenciaDAO(Conexion::conectar());
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


        $idIncidencia = (int) ($datos["idIncidencia"] ?? 0);
        $tipo = trim($datos["tipoIncidencia"] ?? "");
        $idEquipo = $datos["nroPc"] ?? null;
        $alumno = trim($datos["nombreAlumno"] ?? "");
        $descripcion = trim($datos["descripcion"] ?? "");
        $idIncidenciaGeneral = (int) ($datos["idIncidenciaGeneral"] ?? 0);

        if ($idIncidencia === 0 || $tipo === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!in_array($tipo, ["PC", "Otros"], true)) {
            RespuestaJson::error("Tipo de incidencia inválido", 422);
        }

        $this->validarIncidenciaGeneral($tipo, $idIncidenciaGeneral, $descripcion);

        if ($tipo === "PC") {
            if (empty($idEquipo) || $alumno === "") {
                RespuestaJson::error("Se eligió PC pero falta el equipo o la persona", 422);
            }
            $idEquipo = (int) $idEquipo;
        } else {
            $idEquipo = null;
            $alumno = null;
            $idIncidenciaGeneral = null;
        }

        $dao = new IncidenciaDAO(Conexion::conectar());
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

        $resultado = $dao->modificarIncidencia($idIncidencia, $tipo, $idEquipo, $alumno, $descripcion, $idIncidenciaGeneral);

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

        $dao = new IncidenciaDAO(Conexion::conectar());
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

    private function validarIncidenciaGeneral(string $tipo, int $idIncidenciaGeneral, string $descripcion): void
    {
        //Con "Otros" no hay categoría y la descripción es obligatoria
        if ($tipo === "Otros") {
            if ($descripcion === "") {
                RespuestaJson::error("La descripción es obligatoria", 422);
            }
            return;
        }

        if ($idIncidenciaGeneral === 0) {
            RespuestaJson::error("Debe seleccionar una incidencia general", 422);
        }

        $conexion = Conexion::conectar();
        $general = (new IncidenciaDAO($conexion))->buscarIncidenciaGeneral($idIncidenciaGeneral);
        Conexion::desconectar();

        if ($general === null) {
            RespuestaJson::error("La incidencia general no existe", 422);
        }
        if ($general["nombre"] === "Otro" && $descripcion === "") {
            RespuestaJson::error("La descripción es obligatoria cuando se elige Otro", 422);
        }
    }

}