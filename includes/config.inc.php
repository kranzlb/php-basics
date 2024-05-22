<?php
    //-------------- Datenbank festlegen ---------------
    define("TESTMODUS",true);
    define ("DB",[
        "host"=>"localhost",
        "user"=>"root",
        "pwd"=>"",
        "name"=>"db_name"
    ]);
    //------------- Testmodus festlegen -----------------
    if(TESTMODUS){
        error_reporting(E_ALL);
	    ini_set("display_errors",1);
    }
    else{
        error_reporting(0);
	    ini_set("display_errors",0);
    }
?>