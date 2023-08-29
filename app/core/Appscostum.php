<?php
/**
 * 
 * Operasi database yang lebih spesifik dapat ditulis disini
 * lalu didefinisikan atau dicetak sebagai variabel 
 * 
**/


class Appscostum extends Database
{

	public function emailVerification($email, $hash)
	{
		try
		{
			$sql = "SELECT mail, hash, pending_status FROM users_pending WHERE mail=:mail AND hash=:hash AND pending_status=0";
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
	public function forgotPassword_verification($email, $hash)
	{
		try
		{
			$sql = "SELECT mail, hash, status FROM users_forgot WHERE mail=:mail AND hash=:hash AND status=0";
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


	public function readId($kolom=null, $table, $where, $data)
	{
		try
		{
			$sql = "SELECT $kolom FROM $table WHERE $where=:$where";
			$stmt = $this->link->prepare($sql);
			$stmt->bindParam(":$where", $data);
			$stmt->execute();
			$stmt->bindColumn("$kolom",$kolom);
			$stmt->fetch(PDO::FETCH_BOUND);
			//return $kolom? $kolom : '';
			if($stmt->rowCount()>0)
			{
				return $kolom;
			}
			else
			{
				return '';
			}
		}
		catch (PDOException $e)
		{
			return '';
		}
	}
	public function readId_concat($kolom, $return, $table, $where, $data)
	{
		try
		{
			$sql = "SELECT concat($kolom) AS $return FROM $table WHERE $where=:$where";
			$stmt = $this->link->prepare($sql);
			$stmt->bindParam(":$where", $data);
			$stmt->execute();
			$stmt->bindColumn("$return",$kolom);
			$stmt->fetch(PDO::FETCH_BOUND);
			return $kolom? $kolom : '';
		}
		catch (PDOException $e)
		{
			return '';
		}
	}

	
	public function deleteAll($data, $table, $where)
	{
		try
		{
			$in  = str_repeat('?,', count($data) - 1) . '?';
			$sql="DELETE FROM $table WHERE $where IN($in) ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute($data);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function countTable($table, $where=null, $and=null)
	{
		try
		{
			if(!empty($where)):

				if(!empty($and))
				{
					$sql = "SELECT COUNT(*) FROM $table WHERE $where AND $and";
					$stmt = $this->link->prepare($sql);
					$stmt->execute();
					$count = $stmt->fetchColumn();	
					return $count;
				}
				else
				{
					$sql = "SELECT COUNT(*) FROM $table WHERE $where";
					$stmt = $this->link->prepare($sql);
					$stmt->execute();
					$count = $stmt->fetchColumn();	
					return $count;
				}
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
	
	public function avgTable($table, $sum=null, $where=null)
	{

		
		try
		{
			if(!empty($where))
			{
				$sql = "SELECT AVG($sum) AS total FROM $table WHERE $where";
				$stmt = $this->link->prepare($sql);
				$stmt->execute();
				$sum = $stmt->fetchColumn();	
				return $sum;
			}
			else
			{
				$sql = "SELECT AVG($sum) AS total FROM $table";
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
			$sql = "SELECT uid, name, pass FROM $table WHERE $where=:$where AND status=1";
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
	
	public function loginAuth2($username, $password, $table, $where, $ormail, $salt=null)
	{
		try
		{
			$sql = "SELECT uid, name,pass,mail,status FROM $table WHERE $where=:$where OR $ormail=:$ormail AND status=1";
			$stmt = $this->link->prepare($sql);
			
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
		if(($time-$session) > $limit)
		{
			return true;

		}else{

			return false;
		}

	}
	public function returnStatus($status,$aktif='aktif', $tidak='tidak')
	{
		if($status=='1')
		{
			return '<span class="label label-success"> '.$aktif.'</span>';
		}
		else
		{
			return $tidak;
		}
	}
	public function truncateTable($table=NULL, $array=null)
	{
		$sql = "TRUNCATE $table";
		$data = $this->link->query($sql);
		return $data;

	}
	public function myAlterTable($table, $set='2')
	{
		try
		{
			$sql = "ALTER TABLE $table AUTO_INCREMENT=$set";
			$stmt=$this->link->prepare($sql);

			if($stmt->execute())
			{
				return true;
			}
			else
			{
				return false;
			}
			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function deleteTable($table='users')
	{
		$sql = "DELETE FROM users WHERE role!='administrator'";
		$data = $this->link->query($sql);
		return $data;
	}
	public function persentase($pemilih, $keseluruhan, $persen=100)
	{
		if(is_numeric($pemilih) && is_numeric($keseluruhan) && is_numeric($persen))
		{
			return @round($pemilih * $persen / $keseluruhan,2);
		}
		else
		{
			return '0';
		}
	}
	public function NilaiPersen($nilai_1, $nilai_2)
	{
        if(is_numeric($nilai_1) && is_numeric($nilai_2)):
             return @ceil(($nilai_1/$nilai_2)*100).'%';
         else:
            //
        endif;
    }
	

	public function __destruct()
	{
		return true;
	}
}

?>
