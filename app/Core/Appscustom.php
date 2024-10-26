<?php
namespace Core;
use Core\Constructor;
class Appscustom 
{
	protected $db;
	public function __construct(){
        $this->db = new Constructor;
    }
	public function emailVerification($email, $hash)
	{
		try
		{
			$sql = "SELECT mail, hash, pending_status FROM users_pending WHERE mail=:mail AND hash=:hash AND pending_status=0";
			$stmt = $this->db->prepareStatement($sql);
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
	public function forgotPassword__verification($email, $hash)
	{
		try
		{
			$sql = "SELECT mail, hash, status FROM users_forgot WHERE mail=:mail AND hash=:hash AND status=0";
			$stmt = $this->db->prepareStatement($sql);
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
	public function readID($kolom, $table, $where, $data)
	{
	    try
	    {
	        // Pastikan $kolom dan $table sudah terverifikasi
	        if (!preg_match('/^[a-zA-Z0-9_]+$/', $kolom) || !preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
	            throw new InvalidArgumentException('Invalid column or table name.');
	        }

	        $sql = "SELECT $kolom FROM $table WHERE $where = :where";
	        $stmt = $this->db->prepareStatement($sql);
	        $stmt->bindParam(":where", $data);
	        $stmt->execute();

	        if ($stmt->rowCount() > 0) {
	            return $stmt->fetchColumn();
	        }
	        return ''; 
	    }
	    catch (PDOException $e)
	    {
	        echo $e->getMessage();
	        return '';
	    }
	    catch (InvalidArgumentException $e)
	    {
	       echo $e->getMessage();
	       return '';
	    }
	}

	public function readIDConcat($kolom, $return, $table, $where, $data)
	{
		try
		{
			$sql = "SELECT concat($kolom) AS $return FROM $table WHERE $where=:$where";
			$stmt = $this->db->prepareStatement($sql);
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
			$stmt = $this->db->prepareStatement($sql);		
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
					$stmt = $this->db->prepareStatement($sql);
					$stmt->execute();
					$count = $stmt->fetchColumn();	
					return $count;
				}
				else
				{
					$sql = "SELECT COUNT(*) FROM $table WHERE $where";
					$stmt = $this->db->prepareStatement($sql);
					$stmt->execute();
					$count = $stmt->fetchColumn();	
					return $count;
				}
			else:
				$sql = "SELECT COUNT(*) FROM $table";
				$stmt = $this->db->prepareStatement($sql);
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

		try
		{
			if(!empty($where))
			{
				$sql = "SELECT SUM($sum) AS total FROM $table WHERE $where";
				$stmt = $this->db->prepareStatement($sql);
				$stmt->execute();
				$sum = $stmt->fetchColumn();	
				return $sum;
			}
			else
			{
				$sql = "SELECT SUM($sum) AS total FROM $table";
				$stmt = $this->db->prepareStatement($sql);
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
				$stmt = $this->db->prepareStatement($sql);
				$stmt->execute();
				$sum = $stmt->fetchColumn();	
				return $sum;
			}
			else
			{
				$sql = "SELECT AVG($sum) AS total FROM $table";
				$stmt = $this->db->prepareStatement($sql);
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
	public function truncateTable($table=NULL, $array=null)
	{
		$sql = "TRUNCATE $table";
		$data = $this->db->query($sql);
		return $data;
	}
	public function myAlterTable($table, $set='2')
	{
		try
		{
			$sql = "ALTER TABLE $table AUTO_INCREMENT=$set";
			$stmt=$this->db->prepareStatement($sql);

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
	public function deleteTable($table='users', $role='role')
	{
		$sql = "DELETE FROM $table WHERE role!='$role'";
		$data = $this->db->query($sql);
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
	public function nilaiPersen($nilai_1, $nilai_2)
	{
        if(is_numeric($nilai_1) && is_numeric($nilai_2)):
             return @ceil(($nilai_1/$nilai_2)*100).'%';
        endif;
    }
    public function Statistik()
    {
    	$sql="SELECT YEAR(tahun_pendaftaran) AS tahun_daftar, COUNT(*) AS jumlah_siswa
		FROM `siswa`
		GROUP BY YEAR(tahun_pendaftaran)";
    }
	public function __destruct()
	{
		return true;
	}
}

?>
