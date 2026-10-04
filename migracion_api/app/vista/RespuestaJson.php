<?php
class RespuestaJson {
    public static function exito($datos, int $status = 200): void {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode(["datos" => $datos], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error(string $mensaje, int $status): void {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode(["mensaje" => $mensaje], JSON_UNESCAPED_UNICODE);
        exit;
    }
}