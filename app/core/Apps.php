<?php
/** 
														
	* Waktu yang telah dilewati layaknya sebuah mimpi 
	* yang takan bisa diulang kembali. Semua hanya terjadi sebentar 
	* lalu jadi kenangan.
	* Hidup seperti tidak ada masa depan, karena setiap
	* masa depan hanya akan menjadi kenangan.
	* Dan kenangan ini mungkin akan terus hidup dimasa depan - masa depan 
	* yang juga akan menjadi kenangan :(
	*
    *----------------------------------------*
    * Author          			 			 *
	* name 			  : ahmadza 			 * 
	* mail 			  : myroot593@gmail.com  *
	* blog 			  : root93.co.id         *  
	* framework name  : zeel php frame 1.0	 *
	*----------------------------------------*
	* Apps
	* ---------------------------------------------------------------
	* Apps.php adalah bagian bingkai kerja query yang digunakan untuk
	* operasi database seperti menambah, mengedit/update, melakukan
	* paginasi, pencarian data dan operasi standar database lainnya.
	* Idealnya sebuah kerangka maka nantinya bisa digunakan untuk 
	* operasi secara umum ke semua tabel, bukan ditujukan secara 
	* spesifik untuk pengangan hanya untuk database/data tertentu saja.
	* Dengan pembuatan kerangka kerja ini, diharapkan mempermudah
	* programmer dalam bekerja, karena tidak perlu menulis ulang query
	* untuk melakukan operasi yang berbeda - beda. 
	* ---------------------------------------------------------------
	* Contoh Penggunaa Objek	
	* ---------------------------------------------------------------
	* INSERT
	* 
	* Format : $obj->insertTable($table, $kolom, $value, $array)
	* 
	* contoh :
	* if($obj->insertTable('tb_mahasiswa','nim',':nim',
	* array(":nim"=>$nim, nama_mahasiswa"=>$nama))):
	*
	* ---------------------------------------------------------------
	* GET
	* 
	* Format : obj->getTable($table, $where, $data, $kolom)
	* 
	* contoh :
	* if(!$obj->getTable('tb_mahasiswa','id_mahasiswa=:id_mahasiswa',
	* $_GET['id_mahasiswa'],'id_mahasiswa')) 
	* die("Error : id mahasiswa tidak ada");
	*
	* ---------------------------------------------------------------
	* UPDATE
	* 
	* Format : updateTable($table, $kolom, $where, $array)
	* contoh :
	* if($obj->updateTable('tb_mahasiswa','nim=:nim, 
	* nama_mahasiswa=:nama_mahasiswa','id_mahasiswa=:id_mahasiswa', 
	* array(":nim"=>$nim,":nama_mahasiswa"=>$nama,":id_mahasiswa"=>
	* $obj->row['id_mahasiswa']))):
	*
	* ---------------------------------------------------------------
	* DELETE
	*
	* Format : $obj->delete($table, $where, $kolom, $data);
	* Contoh : if($obj->delete('tbl','id_mahasiswa=:id_mahasiswa',
	* 'id_mahasiswa',$obj->row['id_mahasiswa'])):
	* 
	* ---------------------------------------------------------------
	* PAGINATION - LIMIT
	*
	* Format Limit : $obj->($name, $table, $number);
	* Contoh : $data=$obj->pagination('halaman', 'tbl', 5); 
	*
	* Format Pagination : paginationNumber($table, $number);
	* Contoh : $obj->paginationNumber('tbl', 5);
	*  
	* Catatan : Beberapa fungsi penyederhanaan ini mungkin masih ada sedikit
	* yang kurang efisien, ada nilai yang sebenernya tidak perlu diulang
	* misal kolom data didalam prepared statment yang sebenernya tidak perlu
	* didefinisikan ulang misal > 1 x saat objek dicetak
	*  
	* Revisi 30 Juni 2021 : 
	* Pada nomor paginasi, saya tambahkan pencacah supaya nomor paginasi
	* tidak ditampilkan seluruhnya, namun ditampilkan secara bertahap.
	* Akan ada beberapa revisi kode yang akan saya lakukan secara bertahap
	* 
	* ------------------------------------------------------------------
	* LAST INSERT ID
	* Untuk menambhkan ke tabel lain berdasarkan nilai id yang terakhir
	* ditambahkan, ini akan digunakan dalam operasi banyak tabel
	* yang membutuhkan nilai tertentu sebagai parameter
	* parameter yang digunakan untuk menginsert id sebelumnya
	* adalah
	* misal : $obj->insertmyId()
	* ------------------------------------------------------------------
	* Format : $obj->cekData($kolom, $table, $getdata);
	* Contoh : if($obj->cekData('email','user',$_GET['email']):
**/


class Apps extends Database
{
	
	
	
	public function selectTable($table, $where=null)
	{
		try
		{	
			if(!empty($where)):
				$sql="SELECT * FROM $table WHERE $where";
				$stmt=$this->link->prepare($sql);
				return $stmt;
			else:
				$sql="SELECT * FROM $table";
				$stmt=$this->link->prepare($sql);
				$stmt->execute();
				return $stmt;
			endif;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function insertmyId()
	{
		$this->last = $this->link->lastInsertId();
		return $this->last;
	}
	public function insertTable($table, $kolom, $value, $array)
	{
		
		try
		{
			$sql="INSERT INTO $table($kolom) VALUES($value)";
			$stmt=$this->link->prepare($sql);
			$stmt->execute($array);

			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}
	public function getTable($table, $where, $data, $kolom)
	{
		try
		{
			$stmt=$this->selectTable($table, $where);
			$stmt->bindParam(":$kolom",$data);
			$stmt->execute();
			$this->row=$stmt->fetch(PDO::FETCH_ASSOC);
			return $this->row;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			
		}
	}
	public function updateTable($table, $kolom, $where, $array)
	{
		try
		{
			$sql="UPDATE $table SET $kolom WHERE $where";
			$stmt=$this->link->prepare($sql);
			$stmt->execute($array);
			return true;
		}
		catch(PDOException $e)
		{
			
			return false;
		}
	}
	public function delete($table, $where, $kolom, $data)
	{
		try
		{
			$sql="DELETE FROM $table WHERE $where";
			$stmt=$this->link->prepare($sql);
			$stmt->bindParam(":$kolom",$data);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function cekData($kolom, $table, $getdata)
	{
		try
		{
			$sql = "SELECT $kolom FROM $table WHERE $kolom=:$kolom";
			$stmt = $this->link->prepare($sql);
			$stmt->bindParam(":$kolom",$getdata);
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
	public function selectTable_limit($table, $start, $finish, $where=null)
	{
		try
		{
			if(!empty($where)):
				$sql="SELECT * FROM $table WHERE $where LIMIT $start, $finish";
				$stmt=$this->link->prepare($sql);
				return $stmt;
			else:
				$sql="SELECT * FROM $table LIMIT $start, $finish";
				$stmt=$this->link->prepare($sql);
				return $stmt;
			endif;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}

	}
	public function pagination($name, $table, $number, $where=null)
	{
		
		$finish=$number;
		$page=isset($_GET[$name])? (int)$_GET[$name]:1;
		$start=($page>1) ? ($page * $finish) - $finish:0;
		$stmt=(!empty($where))?$this->selectTable_limit($table,$start, $finish, $where):$this->selectTable_limit($table,$start,$finish);		
		$stmt->execute();
		return $stmt;
	}
	public function paginationNumber($table, $number, $url=null, $name=null, $where=null)
	{
		$nopage = isset($_GET[$name])? (int)$_GET[$name]:1;	
		$stmt=$this->selectTable($table, $where);
		(!empty($where))?$stmt->execute():'';
		$total=$stmt->rowCount();
		$jumpage=ceil($total/$number);
		
		echo'<nav aria-label="Page navigation"><ul class="pagination">';
		echo ($nopage>1)?'<li><a href="?'.$url.'&halaman='.($nopage-1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>':NULL;
			
		
		for($page=1; $page<=$jumpage; $page++)
		{
			
			

			if(($page>=$nopage-2)&&($page<=$nopage+2)||($page==1)||($page==$jumpage))
			{
				$tampilpage = $page;
				$r=($tampilpage==1) && ($tampilpage!=2)? '..':NULL;
				$x=($nopage==($jumpage-1)) || ($nopage==$jumpage)?'.':NULL;
				/**
				if(($nopage==($jumpage-1)) || ($nopage==$jumpage)){
					echo '.';
				}else{
					NULL;
				}	

				**/		
				
				$class=($page==$nopage)?'active':NULL;
				echo '<li class="'.$class.'"><a href="?'.$url.'&halaman='.$page.'">'.$page.'</a></li>';

				
			}
		}

		echo($nopage==$jumpage)?NULL:'<li><a href="?'.$url.'&halaman='.($nopage+1).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		echo '</ul></nav>';

	
	}
	public function searchData($tabel, $kolom1, $data, $kolom2=null)
	{
		try
		{
			

		
			if(!empty($kolom2)):
				$sql="SELECT * FROM $tabel WHERE $kolom1 LIKE '$data%' OR $kolom2 LIKE '$data%'";
				$stmt=$this->link->prepare($sql);
				return $stmt;
			else:
				$sql="SELECT * FROM $tabel WHERE $kolom1 LIKE '$data%'";
				$stmt=$this->link->prepare($sql);
				$stmt->execute();
				return $stmt;
			endif;
			
		}
		catch(PDOEXception $e)
		{
			echo $e->getMessage();
		}

	}
	
	public function __destruct()
	{
		return true;
	}

	
	
}


?>
