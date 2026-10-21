<?php

class Presencia
{
    private const ARCHIVO = __DIR__ . "/../../storage/presencia.json";

    private static function leer(): array
    {
        if (!file_exists(self::ARCHIVO)) {
            return [];
        }

        return json_decode(file_get_contents(self::ARCHIVO), true) ?? [];
    }

    private static function guardar(array $datos): void
    {
        file_put_contents(self::ARCHIVO, json_encode($datos), LOCK_EX);
    }

    public static function estaActivo(string $ci, int $segundos = 90): bool
    {
        $datos = self::leer();

        return isset($datos[$ci]) && (time() - $datos[$ci]) < $segundos;
    }

    public static function registrar(string $ci): void
    {
        if ($ci === "") {
            return;
        }

        $datos = self::leer();
        $datos[$ci] = time();
        self::guardar($datos);
    }

    public static function limpiar(string $ci): void
    {
        $datos = self::leer();
        unset($datos[$ci]);
        self::guardar($datos);
    }
}