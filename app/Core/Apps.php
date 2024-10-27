<?php
namespace Core;
use Core\Crudtable;

class Apps extends Crudtable
{
  
	public function loginAuth($username, $password, $table, $where, $salt=null)
	{
		try
		{
			$sql = "SELECT uid, name, pass FROM $table WHERE $where=:$where AND status=1";
			$stmt = $this->getConnection()->prepare($sql);
			$stmt->bindParam(":$where", $username);
			$stmt->execute();
			$stmt->bindColumn("uid",$this->uid);			
			$stmt->bindColumn("pass",$this->hash);
			$stmt->fetch(PDO::FETCH_BOUND);
			if($stmt->rowCount()==1)
			{
				if(password_verify($password.$salt, $this->hash))
				{
					return true;
				}
				else
				{
					return false;
				}
			}

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function loginAuth2($username, $password, $table, $where, $ormail, $salt=null)
	{
		try
		{
			$sql = "SELECT uid, name,pass,mail,status FROM $table WHERE $where=:$where OR $ormail=:$ormail AND status=1";
			$stmt = $this->getConnection()->prepare($sql);
			
			$stmt->bindParam(":$where", $username);
			$stmt->bindParam(":$ormail", $username);
			
			$stmt->execute();
			$stmt->bindColumn("uid",$this->uid);			
			$stmt->bindColumn("pass",$this->hash);
			$stmt->bindColumn("name",$this->name);
			$stmt->bindColumn("status",$this->status);
			$stmt->fetch(PDO::FETCH_BOUND);
			if($stmt->rowCount()==1)
			{
				if(password_verify($password.$salt, $this->hash))
				{
					return true;
				}
				else
				{
					return false;
				}
			}

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function limitSession($time, $session, $limit=null)
	{
		return (($time-$session)>$limit)?true:false;
	}
	
	public function __destruct()
	{
		return true;
	}



}


?>

