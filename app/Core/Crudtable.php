<?php 
namespace Core;
//use Core\Pagination;

class Crudtable extends Querybuilder
{
	public function selectTable($table, $where = null, $order = null)
	{
	    try
	    {
	        $sql = "SELECT * FROM $table";	        
	        if (!empty($where)) {
	            $sql .= " WHERE $where";
	        }	       
	        if (!empty($order)) {
	            $sql .= " $order";
	        }
	        return Apps::prepareStatement($sql);
	    }
	    catch(\PDOException $e)
	    {
	        echo $e->getMessage();
	        return false; 
	    }
	}
	public function lastTable()
	{
		return  $this->getConnection()->lastInsertId();	
	}
	public function insertTable($table, $data)
	{
	    
	    try
	    {
	       
	        $kolom = implode(', ', array_keys($data));
	        $placeholders = ':' . implode(', :', array_keys($data));
	        $sql = "INSERT INTO $table ($kolom) VALUES ($placeholders)";
	        $stmt = Apps::prepareStatement($sql);	      
	        $stmt->execute($data);
	        return true;
	    }
	    catch (\PDOException $e)
	    {
	        echo $e->getMessage();
	        return false;
	    }
	}
	public function replaceTable($table, array $data)
	{
		

		try
		{
			$kolom = implode(', ', array_keys($data));
	        $placeholders = ':' . implode(', :', array_keys($data));
			$sql="REPLACE INTO $table($kolom) VALUES($placeholders)";
			$stmt=Apps::prepareStatement($sql);
			$stmt->execute($array);
			return true;
		}
		catch(\PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}
	public function updateTable($table, $data, $whereParams)
	{
	    try
	    {
	        // Membuat string kolom dan placeholder untuk bagian SET
	        $setClause = implode(', ', array_map(
	            fn($key) => "$key = :$key", // Tidak ada prefix
	            array_keys($data)
	        ));

	        // Membuat kondisi WHERE dinamis tanpa prefix
	        $whereClause = implode(' AND ', array_map(
	            fn($key) => "$key = :$key", // Tidak ada prefix
	            array_keys($whereParams)
	        ));
	        //$whereParams = [
   			//'siswa_id' => 123,
    		//'status' => 'active'];

	        // Gabungkan SQL untuk SET dan WHERE
	        $sql = "UPDATE $table SET $setClause WHERE $whereClause";        
	        $stmt = $this->getConnection()->prepare($sql);
	        // Gabungkan data dan where parameters ke dalam satu array
	        $params = array_merge($data, $whereParams);
	        $stmt->execute($params);
	        
	        return true;
	    }
	    catch (\PDOException $e)
	    {
	        echo $e->getMessage();
	        return false;
	    }
	}
	public function getTable($table, $kolom, $data)
	{
	    try
	    {
	      
	        $sql = "SELECT * FROM $table WHERE $kolom=:$kolom";
			$stmt = $this->getConnection()->prepare($sql);

	       	if($stmt === false)
	       	{
           		throw new Exception('Gagal menyiapkan statement.');
        	}
	        $stmt->bindParam(':'.$kolom, $data);
	        $stmt->execute();        
	        $result = $stmt->fetch(\PDO::FETCH_ASSOC);	      
	        return $result !== false ? $result : null;
	    }
	    catch(\PDOException $e)
	    {
	        
	       echo $e->getMessage();
	       return null;
	    }
	    
	}
	public function deleteTable($table, $kolom, $data)
	{
		try
		{
			$sql="DELETE FROM $table WHERE $kolom=:$kolom";
			$stmt=Apps::prepareStatement($sql);
			$stmt->bindParam(':'.$kolom,$data);
			$stmt->execute();
			return $stmt;
		}
		catch(\PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function cekTable($table,$kolom, $getdata)
	{
		try
		{
			$sql = "SELECT $kolom FROM $table WHERE $kolom=:$kolom";
			$stmt = $this->getConnection()->prepare($sql);
			$stmt->bindParam(':'.$kolom,$getdata);
			$stmt->execute();
			return ($stmt->rowCount()==1)?true:false;
		}
		catch(\PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function allowTable()
	{
		//just for test
		$allowedTables = ['siswa', 'guru'];
		if (!in_array($table, $allowedTables)) {
		 throw new InvalidArgumentException('Invalid table name.');
		}

	}
	protected function save($table, $data){

		if($this->insertTable($table, $data)) {	        
	    	return true;	       
	    }else{
	       	return false;
	    }
	}
}
