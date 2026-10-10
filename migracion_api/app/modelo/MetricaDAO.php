<?php

class MetricaDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function incidenciasPorEstado(): array
    {
        $sql = "
            SELECT es.tipo AS etiqueta, COUNT(*) AS total
            FROM REGISTROINCIDENCIA AS ri
            INNER JOIN ESTADO AS es ON ri.idEstado = es.id
            GROUP BY es.tipo
        ";

        return $this->consultar($sql);
    }

    public function incidenciasPorTipo(): array
    {
        $sql = "
            SELECT rti.tipo AS etiqueta, COUNT(*) AS total
            FROM REGISTROINCIDENCIA AS ri
            INNER JOIN REGISTROTIPOINCIDENCIA AS rti ON ri.idTipoIncidencia = rti.id
            GROUP BY rti.tipo
        ";

        return $this->consultar($sql);
    }

    public function incidenciasPorEspacio(): array
    {
        $sql = "
            SELECT e.tipo AS etiqueta, COUNT(*) AS total
            FROM REGISTROINCIDENCIA AS ri
            INNER JOIN REGISTROESPACIO AS re ON ri.idRegistroEspacio = re.id
            INNER JOIN ESPACIO AS e ON re.idEspacio = e.id
            GROUP BY e.tipo
        ";

        return $this->consultar($sql);
    }

    public function incidenciasPorTecnico(): array
    {
        $sql = "
            SELECT u.nombre AS etiqueta, COUNT(*) AS total
            FROM REGISTROINCIDENCIA AS ri
            INNER JOIN USUARIO AS u ON ri.ciTecnico = u.ci
            GROUP BY u.ci, u.nombre
            ORDER BY total DESC
        ";

        return $this->consultar($sql);
    }

    public function incidenciasPorMes(): array
    {
        $sql = "
            SELECT DATE_FORMAT(ri.fecha, '%Y-%m') AS etiqueta, COUNT(*) AS total
            FROM REGISTROINCIDENCIA AS ri
            GROUP BY etiqueta
            ORDER BY etiqueta
        ";

        return $this->consultar($sql);
    }

        public function salonesConMasIncidencias(): array
    {
        $sql = "
            SELECT CONCAT(e.tipo, ' ', e.numero) AS etiqueta, COUNT(*) AS total
            FROM REGISTROINCIDENCIA AS ri
            INNER JOIN REGISTROESPACIO AS re ON ri.idRegistroEspacio = re.id
            INNER JOIN ESPACIO AS e ON re.idEspacio = e.id
            GROUP BY e.id, e.tipo, e.numero
            ORDER BY total DESC
            LIMIT 10
        ";

        return $this->consultar($sql);
    }

    private function consultar(string $sql): array
    {
        $consulta = $this->conexion->query($sql);

        $filas = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $filas;
    }
}