<?php

/**
 * Encapsula los parámetros de conexión y el ciclo de vida del objeto PDO, de
 * modo que el resto de las clases del sistema reciban una conexión ya
 * configurada.
 *
 * @class ConectorPDO
 */
class ConectorPDO
{
    /** Host (y puerto, si corresponde) del servidor de base de datos. */
    private string $servername;

    /** Nombre de usuario de la base de datos. */
    private string $username;

    /** Contraseña del usuario de la base de datos. */
    private string $password;

    /** Nombre de la base de datos a la que se conecta. */
    private string $dbname;

    /** Conexión PDO activa; NULL mientras no se haya establecido. */
    private ?PDO $conexion;

    /**
     * Constructor parametrizado. Solo almacena los datos de conexión: esta se
     * crea recién al invocar establecerConexion().
     *
     * @param string $servername Host del servidor de base de datos.
     * @param string $username Usuario de la base de datos.
     * @param string $password Contraseña del usuario.
     * @param string $dbname Nombre de la base de datos.
     */
    public function __construct (string $servername, string $username, string $password, string $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->conexion = null;
    }

    /**
     * Establece la conexión con la base de datos y configura el atributo
     * PDO::ATTR_ERRMODE en PDO::ERRMODE_EXCEPTION, para que los errores de SQL
     * se propaguen como excepciones. Si la conexión falla, se captura la
     * PDOException, se imprime el mensaje y se retorna NULL, lo que puede
     * provocar un TypeError por el tipo de retorno declarado.
     *
     * @return PDO La conexión establecida.
     */
    public function establecerConexion(): PDO {
        try {
            $this->conexion = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error al conectar..." . $e->getMessage();
        }
        return $this->conexion;
    }

    /**
     * Cierra la conexión con la base de datos asignando NULL al atributo.
     *
     * @return void
     */
    public function desconectar() {
        $this->conexion = null;
    }
};

//Código para depuración
//$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
//$ConectorPDO->establecerConexion();

?>