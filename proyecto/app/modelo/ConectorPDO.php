<?php
<<<<<<< HEAD

/**
 * Encapsula los parámetros de conexión y el ciclo de vida del objeto PDO,
 * de modo que el resto de las clases del sistema reciban una conexión ya configurada.
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
     * Constructor parametrizado. Solo almacena los datos de conexión: esta se crea recién al invocar establecerConexion().
     *
     * @param string $servername Host del servidor de base de datos.
     * @param string $username Usuario de la base de datos.
     * @param string $password Contraseña del usuario.
     * @param string $dbname Nombre de la base de datos.
     */
=======
//Instalación del driver https://www.php.net/manual/en/pdo.installation.php
//LEER ATENTAMENTE CÓMO SE CONFIGURA TANTO EN LINUX COMO EN WINDOWS
//Especificar en php.ini el extension_dir (debe apuntar a ext) y la extension pdo_mysql para este caso
class ConectorPDO
{
    private string $servername;
    private string $username;
    private string $password;
    private string $dbname;
    private ?PDO $conexion;

>>>>>>> 9384d8451fc88a4e58eea6409ea3d7dae60e0d87
    public function __construct (string $servername, string $username, string $password, string $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->conexion = null;
    }

<<<<<<< HEAD
    /**
     * Establece la conexión con la base de datos y configura el atributo PDO::ATTR_ERRMODE en PDO::ERRMODE_EXCEPTION,
     * para que los errores de SQL se propaguen como excepciones. Si la conexión falla, se captura la
     * PDOException, se imprime el mensaje y se retorna NULL.
     *
     * @return PDO La conexión establecida.
     */
    public function establecerConexion(): PDO {
        try {
            $this->conexion = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
=======
    public function establecerConexion(): PDO {
        try {
            $this->conexion = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            // set the PDO error mode to exception
>>>>>>> 9384d8451fc88a4e58eea6409ea3d7dae60e0d87
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error al conectar..." . $e->getMessage();
        }
        return $this->conexion;
    }

<<<<<<< HEAD
    /**
     * Cierra la conexión con la base de datos asignando NULL al atributo.
     *
     * @return void
     */
=======
>>>>>>> 9384d8451fc88a4e58eea6409ea3d7dae60e0d87
    public function desconectar() {
        $this->conexion = null;
    }
};

//Código para depuración
<<<<<<< HEAD
//$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
//$ConectorPDO->establecerConexion();

?>
=======
//$ConectorPDO = new ConectorPDO ("localhost:3306", "leandro", "123", "test");
//$ConectorPDO->establecerConexion();

?>
>>>>>>> 9384d8451fc88a4e58eea6409ea3d7dae60e0d87
