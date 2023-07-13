<?php
/**
 * The main system database class
 * https://github.com/indieteq/indieteq-php-my-sql-pdo-database-class
 */
class DB {

    /**
     * PDO Object
     */
    private $pdo;

    /**
     * PDO Statement Object 
     */
    private $query;

    /**
     * Connected to the Database
     */
    private $connected = false;

    /**
     * Parameters of the SQL Query
     */
    private $parameters;

    /**
     * Database User 
     */
    protected $dbuser;

    /**
     * Database Password 
     */
    protected $dbpassword;

    /**
     * Database Name 
     */
    protected $dbname;

    /**
     * Database Host 
     */
    protected $dbhost;
        
    /**
     * Class constructor
     */
    public function __construct( $dbuser, $dbpassword, $dbname, $dbhost ) {
        register_shutdown_function( array( $this, '__destruct' ) );

        $this->dbuser = $dbuser;
        $this->dbpassword = $dbpassword;
        $this->dbname = $dbname;
        $this->dbhost = $dbhost;

        $this->db_connect();

        $this->parameters = array();
    }

    /**
     * Class destructor
     */
    public function __destruct() {
        return true;
    }
    
    /**
     * Makes connection to the database.
     */
    private function db_connect() {
        $dsn = 'mysql:dbname='.$this->dbname.';host='.$this->dbhost.';charset=utf8;';
        // print_r( $dsn );die();
        try {

            // Initialize PDO
            $this->pdo = new PDO( $dsn, $this->dbuser, $this->dbpassword );
            
            // Set Error reporting just exceptions
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            
            // Disable emulation of prepared statements, use REAL prepared statements instead.
            $this->pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
            
            // Connection succeeded, set the boolean to true.
            $this->connected = true;

        } catch ( PDOException $e ) {
            echo $e->getMessage();
            die();
        }
    }

    /**
     * You can use this little method if you want to close the PDO connection
     */
    public function close_connection() {
        $this->pdo = null;
    }
        
    /**
     * Every method which needs to execute a SQL query uses this method.
     */  
    private function init( $query, $parameters = '' ) {

        if ( ! $this->connected ) {
            $this->db_connect();
        }

        try {

            $this->query = $this->pdo->prepare( $query );
            
            // Add parameters to the parameter array 
            $this->bind_more( $parameters );

            // Bind parameters
            if ( ! empty( $this->parameters ) ) {
                foreach ( $this->parameters as $param ) {
                    $parameters = explode( "\x7F", $param );
                    $this->query->bindParam( $parameters[0], $parameters[1] );
                }       
            }

            $this->succes = $this->query->execute();

        } catch( PDOException $e ) {
            echo $e->getMessage();
            die();
        }

        // Reset the parameters
        $this->parameters = array();
    }
        
    /**
     * Add the parameter to the parameter array
     */  
    public function bind( $para, $value ) {
        $this->parameters[ sizeof( $this->parameters ) ] = ':' . $para . "\x7F" . $value;
    }

    /**
     * Add more parameters to the parameter array
     */  
    public function bind_more( $parray ) {
        if ( empty( $this->parameters ) && is_array( $parray ) ) {

            $columns = array_keys( $parray );
            foreach ( $columns as $i => &$column ) {
                $this->bind( $column, $parray[ $column ] );
            }

        }
    }

    /**
     * Run de SQL Query
     */          
    public function query( $query, $params = null, $fetchmode = PDO::FETCH_ASSOC ) {

        $query = trim( $query );

        $this->init( $query, $params );

        $raw_statement = explode( ' ', $query );
        
        $statement = strtolower( $raw_statement[0] );
        
        if ( $statement === 'select' || $statement === 'show' ) {
            return $this->query->fetchAll( $fetchmode );
        } elseif ( $statement === 'insert' || $statement === 'update' || $statement === 'delete' ) {
            return $this->query->rowCount();    
        } else {
            return NULL;
        }
    }
    
    /**
     *  Returns the last inserted id.
     */   
    public function last_insert_id() {
        return $this->pdo->lastInsertId();
    }   
        
    /**
     * Returns an array which represents a column from the result set 
     */  
    public function get_column( $query, $params = null ) {
        $this->init( $query,$params );
        $columns = $this->query->fetchAll( PDO::FETCH_NUM );
        
        $column = null;

        foreach ( $columns as $cells ) {
            $column[] = $cells[0];
        }

        return $column;
        
    }

    /**
     * Returns an array which represents a row from the result set 
     */  
    public function get_row( $query, $params = null, $fetchmode = PDO::FETCH_ASSOC ) {
        $this->init( $query, $params );
        return $this->query->fetch( $fetchmode );         
    }

    /**
     * Returns the value of one single field/column
     */  
    public function get_var( $query, $params = null ) {
        $this->init( $query, $params );
        return $this->query->fetchColumn();
    }
}