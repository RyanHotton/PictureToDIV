<?php
	// private information
	include_once "/var/www/html/noGit/db_info.php";
	// Credentials class handles the connection to the SQL server
	class Credentials 
	{
		private $user; // username for the SQL login
		private $pass; // password for the SQL login
		private $dbh;   // connection variable
		private $host; // hosting address to the SQL server

		// constructor that contains default variables incase they were not provided -- old comment
		public function __construct() 
		{
			// assign variables
			$this->user = getUser();	
			$this->pass = getPass();
			$this->host = getHost();
			$this->dbh = null;	
		}

		// establish the connection
		public function connectDB()
		{
			try
			{
				$this->dbh = new PDO($this->host, $this->user, $this->pass);
				$this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			}
			catch (PDOException $e)	// incase of error
			{
				print "Error: " . $e->getMessage() . "<br/>";
			    die();
			}
		}

		// end the connection
		public function closeDB() {
			$this->dbh = null;
		}

		// prepare statement
		public function prepareSQL($query) {
			return $this->dbh->prepare($query);
		}

		// fetches all and places into the respected database
		public function fetchClass($sth, $cName) {
			return $sth->fetchAll(PDO::FETCH_CLASS, $cName);
		}

		public function lastId() {
			return $this->dbh->lastInsertId();
		}
	}
?>