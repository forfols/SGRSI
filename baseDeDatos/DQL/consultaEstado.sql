SELECT E.tipo
        FROM REGISTROINCIDENCIA RI
        INNER JOIN ESTADO E
            ON RI.idEstado = E.id
        WHERE RI.id = :idIncidencia;