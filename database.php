<?php
Class Database {
private static $_mysqlUser='patriziato';
	private static $_mysqlPass='OsteriaDelleAlpi';
	private static $_mysqlDB='bosco_gurin';
	private static $_hostName='localhost';
	
	private static $_connection=NULL;
	
	Private function _construct(){ }
	
	public static function getConnection(){
		if(!self::$_connection){
			self::$_connection=new mysqli(self::$_hostName,self::$_mysqlUser,self::$_mysqlPass,self::$_mysqlDB);
            mysqli_set_charset(self::$_connection, "utf8");
			if(self::$_connection->connect_error){
				die('Errore:'.self::$_connection->connect_error);
			}   
		} 
		Return self::$_connection;
	}	   
} 
$connection=Database::getConnection();
?>
