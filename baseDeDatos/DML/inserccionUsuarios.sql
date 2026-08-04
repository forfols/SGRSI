/*
    Espacio donde se deberán colocar todas las insercciones utilizadas en la primer ejecución del programa para el testeo.
*/

INSERT INTO USUARIO (ci, contra, nombre, rol) VALUES ('11111111', '$2y$10$c07dRbEiBRclnxA8S0RYReofIIAi7OZ50VvEo7X9z2LyaNksKsoDm', 'Alexander Bogorodskiy', 'Solicitante');
INSERT INTO USUARIO (ci, contra, nombre, rol) VALUES ('22222222', '$2y$10$c07dRbEiBRclnxA8S0RYReofIIAi7OZ50VvEo7X9z2LyaNksKsoDm', 'Lautaro Ocampo', 'Tecnico');
INSERT INTO USUARIO (ci, contra, nombre, rol) VALUES ('33333333', '$2y$10$c07dRbEiBRclnxA8S0RYReofIIAi7OZ50VvEo7X9z2LyaNksKsoDm', 'Salvador Medina', 'Administrador');
INSERT INTO USUARIO (ci, contra, nombre, rol) VALUES ('44444444', '$2y$10$c07dRbEiBRclnxA8S0RYReofIIAi7OZ50VvEo7X9z2LyaNksKsoDm', 'Franco Pereira', 'Solicitante');
/*
    "clave1234567" ~ "$2y$12$ki0bVkt8cnZuR4v6aJvhhelaeQc1/4fec2txUcuG1Ybr4cvnhg2sS"

    El hash se recuperó con el siguiente script para crear el primer usuario en el sistema
    Nota: En el futuro se deberían cargar por un usuario administrador

    <?php
        $return = password_hash('clave1234567', PASSWORD_DEFAULT);
        echo($return);
    ?>
*/

