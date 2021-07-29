<?php

/*
 File ini lebih banyak digunakan untuk proses validasi seperti penulisan email
 ,url username, input, post, get, membuat token/hashing data dan fungsi kerangka html seperti wrapper
 dan fungsi html untuk menampilkan pesan sukses atau error, Anda bisa menambah
 -kannya pada bagian ini, bagian ini merupakan abstrak yang berisi beberapa property
 yang dicampur, baik untuk proses html atau validasi penulisan data


*/
class htmlProperty
{
	public function alert($type, $msg)
	{
		echo'
		<div role="alert" class="alert '.$type.' alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				'.$msg.'
		</div>
		';
	}
	public function wrapper()
	{
		echo'
		
			<div id="wrapper">
				<div class="main-content">
		
		
		';			
	}

	public function wrapper2()
	{
		echo'
		
			<div id="wrapper">
				<div class="main-content container">
		
		
		';			
	}

	public function reload($time, $url=null)
	{
		echo "<meta http-equiv=\"refresh\"content=\"$time;URL=$url\"/>";
	}
}
class hashingProperty extends htmlProperty
{
	public function createToken($length)
	{
		$token = array
		(

			range('a', 'z'),
			range('A', 'Z'),
			range(0, 9)
		);
		$char = array();
		foreach ($token as $key => $value) {
			foreach ($value as $k => $v) {
				$char[]=$v;
			}
		}
		$token = null;
		for($i=0; $i<=$length; $i++)
		{
			$token .=$char[rand($i, count($char)-1)];
		}
		return $token;
	}
	public function createHash($rand)
	{
		return md5(rand($rand));
	}
}
class Property extends hashingProperty
{
	public function filter($data)
	{
		$data = htmlspecialchars($data);
		$data = trim($data);
		$data = stripcslashes($data);
		return $data;
	}
	public function post($data)
	{
		$data = $_POST[$data];
		$data = $this->filter($data);
		return $data;
	}
	public function get($data)
	{
		$data = $_GET[$data];
		$data = $this->filter($data);
		return $data;
	}

	public function getEmpty($page)
	{
			
		if(!isset($_GET[$page])){$_GET[$page]='';}
	}

	public function vldEmail($data)
	{
		if(!filter_var($data, FILTER_VALIDATE_EMAIL)){
			return true;
		}else{
			return false;
		}
	}
	public function vldUsername($data)
	{
		if(!preg_match("/^[_A-z0-9]{1,}$/",$data)){
			return true;
		}else{
			return false;
		}
	}
	public function VldUrl($data)
	{
	  if(!preg_match("#^http://[_a-z0-9-]+\\.[_a-z0-9-]+#i",$data)){
	    return true;
	  }else{
	    return false;
	  }
	}
	public function vldName($data)
	{
		if(!preg_match("/^[a-zA-Z ]*$/",$data)){
			return true;
		}else{
			return false;
		}
	}
	public function vldNumber($data)
	{
		if(!preg_match("/^[0-9]*$/",$data)){
			return true;
		}else{
			return false;
		}
	}

	public function hari($data)
	{	

		switch($data)
		{
			
			case'Monday':$data="Senin";return $data;
			case'Tuesday':$data="Selasa";return $data;
			case'Wednesday':$data="Rabu";return $data;
			case'Thursday':$data="Kamis";return $data;
			case'Friday':$data="Jumat";return $data;
			case'Saturday':$data="Sabtu";return $data;
			case'Sunday':$data="Minggu";return $data;
		}
	}
	public function bulan($data)
	{
		switch($data)
		{
			case'1':$data="Januari";return $data;
			case'2':$data="Februari";return $data;
			case'3':$data="Maret";return $data;
			case'4':$data="April";return $data;
			case'5':$data="Mei";return $data;
			case'6':$data="Juni";return $data;
			case'7':$data="Juli";return $data;
			case'8':$data="Agustus";return $data;
			case'9':$data="September";return $data;
			case'10':$data="Oktober";return $data;
			case'11':$data="Nopember";return $data;
			case'12':$data="Desember";return $data;
		}
	}
	public function tampilTanggal()
	{
			$tanggal=$this->hari(date('l')).', ';
			$tanggal.=date('d').' ';
			$tanggal.=$this->bulan(date('m')).' ';
			$tanggal.=date('Y');
			return $tanggal;
	}
	public function vldTanggal($data){
		if(!preg_match("/^[0-9-\-]*$/",$data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function bacaTanggal($data)
	{
			$ar=explode("-", $data);
			//Anda bisa merubah posisi array sesuai format yang diinginkan
			//default Tgl-Bln-Thn 1 0 2
			//Format US Thn-Bln-Tgl 1 2 0
			if(checkdate($ar[1], $ar[0], $ar[2])){			
				return true;
			}else{
				return false;
			}
	}
	public function getError($data)
	{
		
			foreach ($data as $error) {
				return $error;
			}
	
	}
	public function rupiah($angka)
	{
	
		$hasil_rupiah = "Rp " . number_format($angka,0,',','.');
		return $hasil_rupiah;
 
	}
	

}
	

?>