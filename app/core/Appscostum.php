<?php
/**
 * 
 * Operasi database yang lebih spesifik dapat ditulis disini
 * lalu didefinisikan atau dicetak sebagai variabel $obj2
 * 
**/


class Appscostum extends Database
{

	public function emailVerification($email, $hash)
	{
		try
		{
			$sql = "SELECT mail, hash, pending_status FROM field_pending_users WHERE mail=:mail AND hash=:hash AND pending_status=0";
			$stmt = $this->link->prepare($sql);
			$stmt->bindParam(":mail",$email);
			$stmt->bindParam(":hash", $hash);
			if($stmt->execute()):
				
				if($stmt->rowCount()==1):
					return true;
				else:
					return false;
				endif;
			endif;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function readId($kolom, $table, $where, $data)
	{
		try
		{
			$sql = "SELECT $kolom FROM $table WHERE $where=:$where";
			$stmt = $this->link->prepare($sql);
			$stmt->bindParam(":$where", $data);
			$stmt->execute();
			$stmt->bindColumn("$kolom",$kolom);
			$stmt->fetch(PDO::FETCH_BOUND);
			return $kolom? $kolom : '';
		}
		catch (PDOException $e)
		{
			return '';
		}
	}
	public function getList_penerima($pengelola=null)
	{
		try
		{
			//$sql="SELECT users.* FROM users LEFT JOIN field_pengelola_zakat_users ON users.uid=field_pengelola_zakat_users.uid WHERE field_pengelola_zakat_users.uid IS NULL AND status=1 AND role='petugas'";
			$sql="SELECT field_penerima_zakat.* FROM field_penerima_zakat LEFT JOIN field_penyaluran_zakat ON field_penerima_zakat.id_penerima=field_penyaluran_zakat.id_penerima WHERE field_penyaluran_zakat.id_penerima IS NULL AND field_penerima_zakat.id_pengelola='$pengelola' ";
			$stmt = $this->link->prepare($sql);
		
			$stmt->execute();
			return $stmt;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function giveAccess()
	{
		try
		{
			//$sql="SELECT users.* FROM users LEFT JOIN field_pengelola_zakat_users ON users.uid=field_pengelola_zakat_users.uid WHERE field_pengelola_zakat_users.uid IS NULL AND status=1 AND role='petugas'";
			$sql="SELECT users.* FROM users LEFT JOIN field_pengelola_zakat_users ON users.uid=field_pengelola_zakat_users.uid WHERE field_pengelola_zakat_users.uid IS NULL AND status=1";
			$stmt = $this->link->prepare($sql);
		
			$stmt->execute();
			return $stmt;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function joinAccess($session)
	{
		try
		{
			$sql = "SELECT * FROM users lEFT JOIN field_pengelola_zakat_users ON users.uid=field_pengelola_zakat_users.uid WHERE field_pengelola_zakat_users.uid IS NOT NULL AND users.uid='$session'";
			$stmt = $this->link->prepare($sql);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function countTable($table, $where=null)
	{
		try
		{
			if(!empty($where)):

				$sql = "SELECT COUNT(*) FROM $table WHERE $where";
				$stmt = $this->link->prepare($sql);
				$stmt->execute();
				$count = $stmt->fetchColumn();	
				return $count;
			else:
				$sql = "SELECT COUNT(*) FROM $table";
				$stmt = $this->link->prepare($sql);
				$stmt->execute();
				$count = $stmt->fetchColumn();	
				return $count;
			endif;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function sumTable($table, $sum=null, $where=null)
	{

		//SELECT * FROM field_zakat LEFT JOIN field_penyaluran_zakat ON field_zakat.id_pengelola=field_penyaluran_zakat.id_pengelola WHERE field_penyaluran_zakat.id_pengelola IS NULL 
		try
		{
			if(!empty($where))
			{
				$sql = "SELECT SUM($sum) AS total FROM $table WHERE $where";
				$stmt = $this->link->prepare($sql);
				$stmt->execute();
				$sum = $stmt->fetchColumn();	
				return $sum;
			}
			else
			{
				$sql = "SELECT SUM($sum) AS total FROM $table";
				$stmt = $this->link->prepare($sql);
				$stmt->execute();
				$sum = $stmt->fetchColumn();	
				return $sum;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function loginAuth($username, $password, $table, $where, $salt=null)
	{
		try
		{
			$sql = "SELECT uid, name, pass FROM $table WHERE $where=:$where";
			$stmt = $this->link->prepare($sql);
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

	public function limitSession($time, $session, $limit=null)
	{
		if(($time-$session) > $limit)
		{
			return true;

		}else{

			return false;
		}

	}
	public function __destruct()
	{
		return true;
	}
}

?>