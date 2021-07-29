<?php


class database 
{
	

	protected $link;
	protected $databases;
	public function __construct($databases)
	{
		
		
		try
		{
			$this->databases = $databases;			
			$this->link = new PDO("mysql:host=".$this->databases['default']['default']['host']."; dbname=".$this->databases['default']['default']['database']."",$this->databases['default']['default']['username'], $this->databases['default']['default']['password']);
			$this->link->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		return $this->link;
		
	}

}
?>
