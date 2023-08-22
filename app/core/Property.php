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
		return'
		<div role="alert" class="alert '.$type.' alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				'.$msg.'
		</div>
		';
	}
	public function alert2($type, $msg)
	{
		return '
		<div class="alert '.$type.' alert-dismissible fade show" role="alert">
			'.$msg.'.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	}
	

	public function reload($time, $url=null)
	{
		echo "<meta http-equiv=\"refresh\"content=\"$time;URL=$url\"/>";
	}
	public function noresubmit()
	{
		?>

			<script>
					if ( window.history.replaceState ) {
						        window.history.replaceState( null, null, window.location.href );
						}
			</script>

		<?php 
	}
	public function bread($url=null, $page=null, $color='bg-blue')
	{
		echo'
		<!-- Title -->
		<div class="row heading-bg  '.$color.'">
				<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
					<h5 class="txt-light">'.$page.'</h5>
				</div>
					<!-- Breadcrumb -->
					<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
						<ol class="breadcrumb">
							<li><a href="'.$url.'">Dashboard</a></li>								
							<li class="active"><span>'.$page.'</span></li>
							</ol>
						</div>
						<!-- /Breadcrumb -->
					</div>
		<!-- /Title -->



		';
	}
	public function bootstrapPanel($htmlcontent=null, $title=null)
	{
		?>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default card-view">
									<div class="panel-heading">
										<div class="pull-left">
											<h6 class="panel-title txt-dark"><?=$title;?></h6>
										</div>
										<div class="clearfix"></div>
									</div>
						<div class="panel-wrapper collapse in">
							<div class="panel-body">										
								<div class="row">
									<div class="col-md-12">
										<div class="form-wrap">	
											<?=$content;?>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php
	}
	public function notfound($url='home')
	{
		?>
			<div class="container-fluid">
					<!-- Row -->
					<div class="table-struct full-width full-height">
						<div class="table-cell vertical-align-middle">
							<div class="auth-form  ml-auto mr-auto no-float">
								<div class="panel panel-default card-view mb-0">
									<div class="panel-wrapper collapse in">
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-12 col-xs-12 text-center">
													<h3 class="mb-20 txt-danger">Page Not Found</h3>
													<p class="font-18 txt-dark mb-15">We are sorry, the page you requested cannot be found.</p>
													<p>The URL may be misspelled or the page you're looking for is no longer available.	</p>
													<a class="btn btn-success btn-icon right-icon btn-rounded mt-30" href="?page=<?=$url?>"><span>back to home</span><i class="fa fa-space-shuttle"></i></a>
													<p class="font-12 mt-15">2022 &copy; Ispp</p>
												</div>	
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /Row -->	
				</div>
		<?php 
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
	public function my_encode($string, $salt)
	{
	  $string_with_salt = $string.$salt;
	  return base64_encode($string_with_salt);
	}

	
	public function my_decode($string, $salt)
	{
	  $string_with_salt = base64_decode($string);
	  $string = str_replace($salt, "", $string_with_salt);
	  return $string;
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
	public function getEmpty2(array $pages)
	{
		foreach ($pages as $page) 
		{
			if (!isset($_GET[$page])){$_GET[$page] = '';}
		}
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
	public function secretThn($tgl_lahir)
	{
		$ar=explode("-", $tgl_lahir);
		$this->m =$ar[0];
		$this->m .= " ";
		$this->m .=$this->bulan($ar['1']);
		return $this->m;

	}
	public function hitungUmur($data)
	{
		$explode = explode("-", $data);

		return date('Y') - $explode[2]; 
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
	public function rubah_format_tanggal($data)
	{
		$arr=explode("-", $data);
		$tgl=$arr[2].$arr[1].$arr[0];
		return $tgl;

	}
	public function gantiformat($nomorhp) 
	{
     
	     $nomorhp = trim($nomorhp);
	  
	     $nomorhp = strip_tags($nomorhp);
	   
	     $nomorhp= str_replace(" ","",$nomorhp);
	   
	     $nomorhp= str_replace("(","",$nomorhp);
	    
	     $nomorhp= str_replace(".","",$nomorhp); 

	     
	     if(!preg_match('/[^+0-9]/',trim($nomorhp))){
	         // cek apakah no hp karakter 1-3 adalah +62
	         if(substr(trim($nomorhp), 0, 3)=='+62'){
	             $nomorhp= trim($nomorhp);
	         }
	         // cek apakah no hp karakter 1 adalah 0
	        elseif(substr($nomorhp, 0, 1)=='0'){
	             $nomorhp= '+62'.substr($nomorhp, 1);
	         }
	     }
	     return $nomorhp;
	}
	public function formatexcel($data, $string='N-') 
	{
     
	     $data = trim($data);
	  
	     $data = strip_tags($data);
	   
	     $data= str_replace(" ","",$data);
	   
	     $data= str_replace("(","",$data);
	    
	     $data= str_replace(".","",$data); 

	     
	     if(!preg_match('/[^+0-9]/',trim($data))){
	       
	       
	       
	             $data= $string.substr($data, 0);
	         
	     }
	     return $data;
	}
	public function pisah_nama($data)
	{
		$this->nama = explode(" ", $data);
		return $this->nama;
	}
	public function nama_pertama($data)
	{
		if($this->pisah_nama($data))
		{
			return (!empty($this->nama[0])?$this->nama[0]:'');
		}
	}
	public function nama_kedua($data)
	{
		if($this->pisah_nama($data))
		{
			return (!empty($this->nama[1])?$this->nama[1]:'');
		}
	}
	public function nama_ketiga($data)
	{
		if($this->pisah_nama($data))
		{
			return (!empty($this->nama[2])?$this->nama[2]:'');
		}
	}
	public function nama_keempat($data)
	{
		if($this->pisah_nama($data))
		{
			return (!empty($this->nama[3])?$this->nama[3]:'');
		}
	}
	public function hitung_nama($data)
	{
		$nama = explode(" ", $data);
		$nama = count($nama);
		return $nama;
	}
	public function nama_table($data)
	{
		if(self::hitung_nama($data)==1)
		{
			$nama = self::nama_pertama($data);

		}elseif(self::hitung_nama($data)==2)
		{
			$nama = self::nama_pertama($data);
			$nama .=" ".substr(self::nama_kedua($data),0,1);
		}
		elseif(self::hitung_nama($data)==3)
		{
			$nama = self::nama_pertama($data);
			$nama .=" ".substr(self::nama_kedua($data),0,1);
			$nama .= " ". substr(self::nama_ketiga($data),0,1);
		}
		else
		{
			if(self::hitung_nama($data)>=4)
			{
				$nama = self::nama_pertama($data);
				$nama .=" ".substr(self::nama_kedua($data),0,1);
				$nama .= " ". substr(self::nama_ketiga($data),0,1);
				$nama .= " ". substr(self::nama_keempat($data),0,1);

			}
		}
		return $nama;

	}
	public function nama_pendek($data)
	{
		if(self::hitung_nama($data)==1)
		{
			$nama = self::nama_pertama($data);

		}elseif(self::hitung_nama($data)==2)
		{
			$nama = self::nama_pertama($data);
			$nama .=" ".self::nama_kedua($data);
		}
		elseif(self::hitung_nama($data)==3)
		{
			$nama = self::nama_pertama($data);
			$nama .=" ".self::nama_kedua($data);
			$nama .= " ". substr(self::nama_ketiga($data),0,1);
		}
		else
		{
			if(self::hitung_nama($data)>=4)
			{
				$nama = self::nama_pertama($data);
				$nama .=" ".self::nama_kedua($data);
				$nama .= " ". substr(self::nama_ketiga($data),0,1);
				$nama .= " ". substr(self::nama_keempat($data),0,1);

			}
		}
		return $nama;

	}
	public function terbilang($data)
	{
		
		$data = abs($data);
		$angka = array("","satu","dua","tiga","empat","lima","enam", "tujuh","delapan","sembilan","sepuluh","sebelas");
		if($data<12):

			$nil = " ".$angka[$data];
		elseif($data<20):
			$nil = self::terbilang($data-10)." belas";
		elseif($data<100):
			$nil = self::terbilang($data/10)." puluh".self::terbilang($data % 10);
		elseif($data<200):
			$nil = "seratus".self::terbilang($data - 100);
		elseif($data<1000):
			$nil = self::terbilang($data/100) ." ratus" .self::terbilang($data % 100);
		elseif($data<10000):
			$nil = "seribu ".self::terbilang($data - 1000);
		elseif($data < 100000):
			$nil = self::terbilang($data/1000)." ribu" . self::terbilang($data % 1000);
		elseif($data<1000000):
			$nil = self::terbilang($data/1000) ." ribu" . self::terbilang($data % 1000);
		endif;
		return $nil;
	}
	public function membilang ($data)
	{
		if($data<0)
		{
			$hasil = "minus " .self::terbilang($data);
		}
		else
		{
			$hasil = self::terbilang($data);
		}
		return $hasil;
	}
	public function urltitle($data)
	{
		$data = str_replace("_", " ", $data);
		$data = ucwords($data);
		return $data;
	}
	public function stringescape($data)
	{
		$data = trim($data);
		$data = str_replace("'","''",$data);
		return $data;
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
	public function warna_progress_bar($i=0)
	{
		$array = array('progress-bar-info','progress-bar-success','progress-bar-primary','progress-bar-warning','progress-bar-danger');

		$colors = $array[$i%count($array)];
		return $colors;
	}
	public function warna_chart_jurusan($i=0)
	{
		$array = array
		(

			"rgba(234,101,162,.8)",
			"rgba(241,91,38,.8)",
			"rgba(252,176,59,.8)"
		);
		$colors = $array[$i%count($array)];
		return $colors;
	}
	

}
	

?>
