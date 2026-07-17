<?php

class ConsultaUsuario {

    public function buscarUsuario(string $ci): ?Usuario {
        $usuarios = [
            [
            "ci" => "11111111",
            "contra" => password_hash("123", PASSWORD_DEFAULT),
            "activo" => true,
            "rol" => "Solicitante"
        ],
        [
            "ci" => "22222222",
            "contra" => password_hash("123", PASSWORD_DEFAULT),
            "activo" => true,
            "rol" => "Tecnico"
        ],
        [
            "ci" => "33333333",
            "contra" => password_hash("123", PASSWORD_DEFAULT),
            "activo" => true,
            "rol" => "Administrador"
        ],
        ];

        foreach ($usuarios as $datos) {
            if ($datos["ci"] === $ci) {
                return new Usuario(
                    $datos["ci"],
                    $datos["contra"],
                    $datos["activo"],
                    $datos["rol"]
                );
            }
        }return null;

    }
}

?>