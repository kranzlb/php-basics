<?php
    
    class db {
        private mysqli $conn;
        private ?string $host=null;
        private ?string $user=null;
        private ?string $pwd=null;
        private ?string $name=null;
        protected object $daten;
        private bool $r_bool;

        function __construct(?string $host=null, ?string $user=null, ?string $pwd=null, ?string $name=null){
            if(!is_null($host)){
                $this->host = $host;
            }
            else{
                if(defined("DB") && isset(DB["host"])){
                    $this->host = DB["host"];
                }
            }
            if(!is_null($user)){
                $this->user = $user;
            }
            else{
                if(defined("DB") && isset(DB["user"])){
                    $this->user = DB["user"];
                }
            }
            if(!is_null($pwd)){
                $this->pwd = $pwd;
            }
            else{
                if(defined("DB") && isset(DB["pwd"])){
                    $this->pwd = DB["pwd"];
                }
            }
            if(!is_null($name)){
                $this->name = $name;
            }
            else{
                if(defined("DB") && isset(DB["name"])){
                    $this->name = DB["name"];
                }
            }
            if(!is_null($this->host) && !is_null($this->user) && !is_null($this->pwd) && !is_null($this->name)){
                $this->conn = $this->connect();
            }
        }
        //----------------- Verbindung herstellen ----------------------
        private function connect():MYSQLi{
            try{
                $conn_intern = new MySQLi($this->host, $this->user, $this->pwd, $this->name);
                if($conn_intern->connect_errno>0){
                    if(TESTMODUS){
                        die("Fehler im Verbindungsaufbau");
                    }
                    else{
                        //header("Location: ");
                    }
                }
                $conn_intern->set_charset("utf8mb4");
            }
            catch(Exception $e){
                ta("Fehler im Verbindungsaufbau: ".$conn_intern->connect_error);
                if(TESTMODUS){
                    die("Fehler im Verbindungsaufbau: Abbruch");
                }
                else{
                    //header("Location: ");
                }
            }
            return $conn_intern;
        }
        //------------------ Query Funktion -------------------
        public function query(string $sql){
            try{
                $daten= $this->conn->query($sql);
                if($daten===false){
                    die("Fehler im SQL-Statement. Abbruch: ".$this->conn->error);
                    ta($sql);
                }
                else{
                    //header("Location: ");
                }
            }
            catch(Exception $e){
                if(TESTMODUS){
                    die("Fehler im SQL-Statement. Abbruch: ".$this->conn->error);
                }
                else{
                    //header("Location: ");
                }
            }
            if(is_bool($daten)){
                return $daten;
            }
            else{
                $this->daten = $daten;
                return $daten;
            }
        }
    }

    class interact extends db {
        private array $r_arr;
        private bool $r_bool;
        function __construct(){

        }

        public function auslesen(string $sql):array{
            parent::__construct();
            parent::query($sql);
            if($this->daten->num_rows>0){
                //ta($this->daten->num_rows>0);
                while($info=$this->daten->fetch_object()){
                    //ta($info);
                    $this->r_arr[] = $info;
                }
                return $this->r_arr;
            }
            else{
                $this->r_arr[] = NULL;
                return $this->r_arr;
            }
        }   
        public function write(string $sql):bool{
            parent::__construct();
            $this->r_bool=parent::query($sql);
            return $this->r_bool;   
        }

    }






?>