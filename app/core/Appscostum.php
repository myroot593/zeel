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
	public function selectKelas($kelas, $status, $tahun, $order=null)
	{
		try
		{
			$sql = "SELECT * FROM field_siswa WHERE kelas=$kelas AND status_siswa=$status AND tahun_masuk=$tahun $order";
			$stmt = $this->link->prepare($sql);
			$stmt->execute();
			return $stmt;
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

	public function verifikasiMasal($data, $verifikasi, $verifikator)
	{
		try
		{
			$in  = str_repeat('?,', count($data) - 1) . '?';
			$sql="UPDATE field_siswa SET status_pendaftaran='$verifikasi', verifikator='$verifikator' WHERE id_siswa IN($in) ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute($data);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function verifikasiMasal2($data, $verifikasi, $kelas, $verifikator)
	{
		try
		{
			$in  = str_repeat('?,', count($data) - 1) . '?';
			$sql="UPDATE field_siswa SET status_pendaftaran='$verifikasi', kelas='$kelas', verifikator='$verifikator' WHERE id_siswa IN($in) ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute($data);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function verifikasiPembayaran($data, $bayar_status, $verifikator)
	{
		try
		{
			$in  = str_repeat('?,', count($data) - 1) . '?';
			$sql="UPDATE pembayaran SET bayar_verif='$bayar_status', bayar_pemeriksa='$verifikator' WHERE id_pembayaran IN($in) ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute($data);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	public function cekPembayaran($pendaftar)
	{
		try
		{
			$sql="SELECT * FROM pembayaran WHERE uid='$pendaftar' AND (bayar_verif='Terverifikasi' OR bayar_verif='Lunas' OR bayar_verif='Belum Lunas' OR bayar_verif='Belum Diverifikasi') ORDER by id_pembayaran LIMIT 1";
			$stmt = $this->link->prepare($sql);
			$stmt->execute();
			
			if($stmt->rowCount()>0)
			{
				if($data=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					if($data['bayar_verif']=='Belum Diverifikasi')
					{
						return 'Belum Diverifikasi';
					}
					elseif($data['bayar_verif']=='Terverifikasi')
					{
						return 'Terverifikasi';
					}
					elseif($data['bayar_verif']=='Lunas')
					{
						return 'Lunas';
					}
					else
					{
						return 'Belum Lunas';
					}
				}

			}
			else
			{
				return '';
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
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
	public function deleteField_guru_all($data)
	{
		try
		{
			$in  = str_repeat('?,', count($data) - 1) . '?';
			$sql="DELETE FROM field_guru WHERE id_guru IN($in) ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute($data);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function deleteField_siswa_all($data)
	{
		try
		{
			$in  = str_repeat('?,', count($data) - 1) . '?';
			$sql="DELETE FROM field_siswa WHERE id_siswa IN($in) ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute($data);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
	public function naikKelas($data, $kelas)
	{
		try
		{
			$in  = str_repeat('?,', count($data) - 1) . '?';
			$sql="UPDATE field_siswa SET kelas=$kelas WHERE id_siswa IN($in) ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute($data);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function leftJoin($uid)
	{
		try
		{
			$sql="SELECT *,field_jurusan_pertama.nama_jurusan AS jurusan_pertama,
					field_jurusan_kedua.nama_jurusan AS jurusan_kedua FROM field_siswa 		
			LEFT JOIN user_profile ON field_siswa.uid=user_profile.uid 
			LEFT JOIN field_jurusan_pertama ON field_jurusan_pertama.id_jurusan=field_siswa.jurusan_pertama
			LEFT JOIN field_jurusan_kedua  ON field_jurusan_kedua.id_jurusan=field_siswa.jurusan_kedua
			LEFT JOIN komponen_jalur ON komponen_jalur.id_jalur=field_siswa.jalur_pendaftaran
			WHERE field_siswa.uid='$uid' ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function leftJoin_printSiswa($uid)
	{
		/**$sql="SELECT * FROM field_siswa INNER JOIN user_profile ON field_siswa.uid=user_profile.uid INNER JOIN users ON field_siswa.uid=users.uid WHERE field_siswa.uid='$uid' ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute();
			return $stmt;**/
		try
		{
			$sql="SELECT *,field_jurusan.nama_jurusan AS jurusan,
				field_jurusan.id_jurusan AS id_jurusan, field_jurusan.nama_jurusan AS jurusan,field_jurusan_pertama.nama_jurusan AS jurusan_pertama,
					field_jurusan_kedua.nama_jurusan AS jurusan_kedua, field_kelas.nama_kelas AS nama_kelas FROM field_siswa 		
			LEFT JOIN user_profile ON field_siswa.uid=user_profile.uid 
			LEFT JOIN users ON field_siswa.uid=users.uid
			LEFT JOIN field_jurusan ON field_jurusan.id_jurusan=field_siswa.jurusan
			LEFT JOIN field_jurusan_pertama ON field_jurusan_pertama.id_jurusan=field_siswa.jurusan_pertama
			LEFT JOIN field_jurusan_kedua  ON field_jurusan_kedua.id_jurusan=field_siswa.jurusan_kedua
			LEFT JOIN field_kelas ON field_kelas.id_kelas=field_siswa.kelas
			LEFT JOIN komponen_jalur ON komponen_jalur.id_jalur=field_siswa.jalur_pendaftaran

			WHERE field_siswa.uid='$uid' ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function leftJoin_printSiswa2($uid)
	{
		
		try
		{
			$sql="SELECT id_siswa, nama_siswa, no_nik, no_kk, nisn, jl, CONCAT(tempat_lahir, ', ',tanggal_lahir) AS tempat_tgl_lahir, tempat_lahir, tanggal_lahir, YEAR(CURDATE())-YEAR(tanggal_lahir2)-IF(RIGHT(CURDATE(),5)<RIGHT(tanggal_lahir2,5),1,0) AS usia
			,nama_sekolah, tgl_pendaftaran, status_pendaftaran, status_pembayaran, kode_pendaftaran , photo, field_jurusan.nama_jurusan AS jurusan,
				field_jurusan.id_jurusan AS id_jurusan, field_jurusan.nama_jurusan AS jurusan,field_jurusan_pertama.nama_jurusan AS jurusan_pertama,
					field_jurusan_kedua.nama_jurusan AS jurusan_kedua, id_jalur, nama_jalur, ekstra FROM field_siswa 		
	
			LEFT JOIN users ON field_siswa.uid=users.uid
			LEFT JOIN field_jurusan ON field_jurusan.id_jurusan=field_siswa.jurusan
			LEFT JOIN field_jurusan_pertama ON field_jurusan_pertama.id_jurusan=field_siswa.jurusan_pertama
			LEFT JOIN field_jurusan_kedua  ON field_jurusan_kedua.id_jurusan=field_siswa.jurusan_kedua
			LEFT JOIN field_kelas ON field_kelas.id_kelas=field_siswa.kelas
			LEFT JOIN komponen_jalur ON komponen_jalur.id_jalur=field_siswa.jalur_pendaftaran

			WHERE field_siswa.uid='$uid' ";
			$stmt = $this->link->prepare($sql);		
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function leftJoin_verifikasiSiswa($uid)
	{

		try
		{
			$sql ="

				SELECT *,
				
				field_jurusan.nama_jurusan AS jurusan,
				field_jurusan.id_jurusan AS id_jurusan,
				field_jurusan_pertama.nama_jurusan AS jurusan_pertama,
				field_jurusan_kedua.nama_jurusan AS jurusan_kedua,
				field_kelas.nama_kelas AS nama_kelas,
				field_kelas.id_kelas AS id_kelas
				
				FROM field_siswa
				LEFT JOIN user_profile ON field_siswa.uid=user_profile.uid
				LEFT JOIN users ON field_siswa.uid=users.uid
				LEFT JOIN field_jurusan_pertama ON field_jurusan_pertama.id_jurusan=field_siswa.jurusan_pertama
				LEFT JOIN field_jurusan_kedua  ON field_jurusan_kedua.id_jurusan=field_siswa.jurusan_kedua
				LEFT JOIN field_jurusan ON field_jurusan.id_jurusan=field_siswa.jurusan
				LEFT JOIN field_kelas ON field_kelas.id_kelas=field_siswa.kelas
				WHERE field_siswa.uid='$uid'";
			$stmt = $this->link->prepare($sql);
			$stmt->execute();
			return $stmt;
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
	public function sumif_jenis_kelamin($table)
	{
		$sql = "SELECT SUM(IF(jl='Laki-Laki',1,0)) AS jml_pria, SUM(IF(jl='Perempuan',1,0)) AS jml_perempuan from $table";
		$stmt = $this->link->query($sql);
		$stmt->execute();
		return $stmt;
	}
	public function status_pendaftaran($user)
	{
		try
		{
			$sql ="SELECT uid, id_siswa, nisn, nama_siswa, jl, nama_sekolah, status_pendaftaran, field_jurusan.nama_jurusan AS jurusan_diterima FROM field_siswa LEFT JOIN field_jurusan ON field_jurusan.id_jurusan=field_siswa.jurusan WHERE field_siswa.uid='$user'";
			$stmt = $this->link->prepare($sql);
			$stmt->execute();
			return $stmt;

			//$sql="SELECT * FROM field_guru LEFT JOIN user_profile ON field_guru.uid=user_profile.uid WHERE field_guru.uid='$uid' ";
			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function cek_kelengkapan_data($total_kolom, $row)
	{
		$data1=array();
		for ($i=0; $i<$total_kolom ; $i++){ 
					$data1[]=$row[$i];
		}
																	
		$total_terisi = 0;
		array_walk($data1, function($value)use(&$total_terisi){
			if(!empty($value)){
				$total_terisi++;
			}
		});
		return $total_terisi;
	}
	public function daftar_ulang()
	{
		try
		{
			$sql = "SELECT id_settings, daftar_nama, daftar_tanggal_buka, daftar_tanggal_tutup,
			DAYNAME(daftar_tanggal_buka) AS hari_buka,
			DAYNAME(daftar_tanggal_buka) AS hari_tutup, 
			DATE_FORMAT(daftar_tanggal_buka,('%d-%m-%Y')) AS tgl_buka, 
			DATE_FORMAT(daftar_tanggal_tutup,('%d-%m-%Y')) AS tgl_tutup,
			daftar_pesan FROM  settings_daftar_ulang";
			$stmt = $this->link->prepare($sql);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function kartu_pendaftaran($user)
	{
		try
		{
			$sql = "SELECT nama_siswa, no_nik, no_kk, nisn, nama_siswa, jl, CONCAT(tempat_lahir, ',',tanggal_lahir) AS tempat_tgl_lahir, 
			jurusan_pertama, jurusan_kedua,nama_sekolah, tgl_pendaftaran, status_pendaftaran, status_pembayaran, kode_pendaftaran 
			FROM field_siswa WHERE uid='$user'";


			$stmt = $this->link->prepare($sql);
			$stmt->execute();
			return $stmt;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function nomor_pendaftaran()
	{
		$sql="SELECT DATE_FORMAT(pengumuman_tanggal_buka,('%Y%m')) AS format_nomor, pengumuman_nama, pengumuman_tanggal_buka, pengumuman_tanggal_tutup, pengumuman_pesan FROM settings_pengumuman";
		$stmt = $this->link->prepare($sql);
		$stmt->execute();
		return $stmt;	
	}
	public function cekNilai($mapel, $semester, $siswa)
	{
		try
		{
			$sql = "SELECT id_siswa FROM mapel_nilai WHERE id_mapel=:id_mapel AND id_semester=:id_semester AND id_siswa=:id_siswa";
			$stmt = $this->link->prepare($sql);
			$stmt->bindParam(":id_mapel",$mapel);
			$stmt->bindParam(":id_semester",$semester);
			$stmt->bindParam(":id_siswa",$siswa);						
			if($stmt->execute()){
			
				if($stmt->rowCount()>0)
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
	public function joinMapel($data)
	{
		try
		{
			$sql=
			"
			SELECT id_nilai, mapel_nilai.id_semester, mapel_nilai.id_semester,nilai, 
			nama_mapel, alias_mapel, nama_semester
			FROM mapel_nilai
			LEFT JOIN mapel ON mapel_nilai.id_mapel=mapel.id_mapel
			LEFT JOIN mapel_semester ON mapel_nilai.id_semester=mapel_semester.id_semester
			WHERE id_siswa=:id_siswa ORDER by mapel_nilai.id_semester;
			";
			$stmt=$this->link->prepare($sql);
			$stmt->bindParam(":id_siswa",$data);
			return $stmt;
			
		}
		catch(PDOException $e)
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
