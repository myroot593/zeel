<?php 
namespace Helper;

class Helper extends Helperjs
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
	
	public function setimage64($string, $search="64.")
	{
		//untuk mendapatkan nama file yang telah disimpan base64 untuk unlink
		$position = strpos($string, $search);
		if ($position !== false) {
		    $result = substr($string, 0, $position); 
		    return $result; 
		}
	}
	public function getimage64($string, $search="64.")	{
		
		//row image format base64
		$position = strpos($string, $search);

		if ($position !== false) {		    
		    $result = substr($string, $position + strlen($search));
		    return '<img src="data:image/jpeg;base64,'.$result.'" alt="Gambar" />';
		}
	}

	public function filter($data)
	{
		$data = htmlspecialchars($data ?? '');
		$data = trim($data);
		$data = stripcslashes($data);
		return $data;
	}
	public function post($data)
	{
		$data = $_POST[$data];
		$data = $this->filter($data);
		return $data?$data :null;
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
	public function set_csrf(){
	  if( ! isset($_SESSION["csrf"]) ){ $_SESSION["csrf"] = bin2hex(random_bytes(50)); }
	  return '<input type="hidden" name="csrf" id="csrf" value="'.$_SESSION["csrf"].'">';
	}
	
	public function is_csrf_valid(){
	  if( ! isset($_SESSION['csrf']) || ! isset($_POST['csrf'])){ return false; }
	  if( $_SESSION['csrf'] != $_POST['csrf']){ return false; }
	  return true;
	}
	

	public function unset_session($session_name)
	{
		if (isset($_SESSION[$session_name])) {	unset($_SESSION[$session_name]); }
	}


	public function validate_email($data)
	{
		if(!filter_var($data, FILTER_VALIDATE_EMAIL)){
			return true;
		}else{
			return false;
		}
	}
	public function validate_username($data)
	{
		if(!preg_match("/^[_A-z0-9]{1,}$/",$data)){
			return true;
		}else{
			return false;
		}
	}
	public function validate_url($data)
	{
	  if(!preg_match("#^http://[_a-z0-9-]+\\.[_a-z0-9-]+#i",$data)){
	    return true;
	  }else{
	    return false;
	  }
	}
	public function validate_name($data)
	{
		if(!preg_match("/^[a-zA-Z ]*$/",$data)){
			return true;
		}else{
			return false;
		}
	}
	public function validate_number($data)
	{
	    // Ensure $data is a string
	    if (!is_string($data)) {
	        return false;
	    }

	    // Cast $data to string to ensure it's in the right format
	    $data = (string) $data;

	    // Validate if the string consists of only digits
	    if (!preg_match("/^[0-9]*$/", $data)) {
	        return true;
	    } else {
	        return false;
	    }
	}

	public function validate_format_tanggal($data) {
	    if (!preg_match('/^\d{4}[-\/]\d{2}[-\/]\d{2}$/', $data)) {
	        return true; 
	    } else {
	        return false;
	    }
	}

	public function validate_format_tanggal_dash($data) {
	    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
	        return true;
	    } else {
	        return false;
	    }
	}
	public function validate_format_tanggal_slash($data) {
	    if (!preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $data)) {
	        return true;
	    } else {
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
	public function hitungUmur($tanggalLahir)
	{
	    // Coba format dengan slash
	    $tanggalLahirObj = \DateTime::createFromFormat('Y/m/d', $tanggalLahir);

	    // Jika gagal, coba format dengan dash
	    if (!$tanggalLahirObj) {
	        $tanggalLahirObj = \DateTime::createFromFormat('Y-m-d', $tanggalLahir);
	    }

	    // Pastikan tanggal lahir valid
	    if (!$tanggalLahirObj) {
	        return null; // Atau bisa melempar exception sesuai kebutuhan
	    }

	    $hariIni = new \DateTime();
	    $umur = $hariIni->diff($tanggalLahirObj)->y;

	    return $umur;
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
	    $arr = explode("-", $data);
	  
	    if (count($arr) === 3) {
	        $tgl = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
	        return $tgl;
	    }
	    return null; 
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
	public function getError($data)	{
		
		foreach ($data as $error) {
			$this->alert('alert-danger',$error);
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
}
Class Helperjs
{
	
	   public function set_alamat($url='/getalamat')
    {
        ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    function fetchAlamat(target, id, dataType, placeholder) {
                        $.ajax({
                            type: 'POST',
                            url: "<?=$url?>",
                            data: {id: id, data: dataType},
                            success: function(hasil) {
                                 console.log("Response data:", hasil);
                                $(target).html(hasil);
                                $(target).show();
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching data: " + error);
                                $(target).html('<option value="">' + placeholder + ' tidak tersedia</option>');
                            }
                        });
                    }

                  
                    $('#prov').on("change", function(){
                        var id = $(this).val();
                        fetchAlamat("#kab", id, "kabupaten", "Kabupaten");
                    });

                   
                    $('#kab').on("change", function(){
                        var id = $(this).val();
                        console.log("Kabupaten ID:", id);
                        fetchAlamat("#kec", id, "kecamatan", "Kecamatan");
                    });

                  
                    $('#kec').on("change", function(){
                        var id = $(this).val();
                         console.log("Kecamatan ID: ", id); // Debugging
                        fetchAlamat("#desa", id, "desa", "Desa");
                    });
                });
            </script>

        <?php 
    }
    public function setToastrNotification($type, $messages) {
	   
	    if (!is_array($messages)) {
	        $messages = [$messages];
	    }
	    $_SESSION['toastr'] = ['type' => $type, 'messages' => $messages];
	}
    public function set_toastr()
    {
        ?>
            <script>
                <?php if (isset($_SESSION['toastr'])): ?>
                    var toastrType = "<?php echo $_SESSION['toastr']['type']; ?>";
                    var toastrMessages = <?php echo json_encode($_SESSION['toastr']['messages']); ?>;

                    if (Array.isArray(toastrMessages)) {
                        toastrMessages.forEach(function(message) {
                            if (toastrType === 'success') {
                                toastr.success(message);
                            } else if (toastrType === 'error') {
                                toastr.error(message);
                            }
                        });
                    } else {
                        
                        //console.error('toastrMessages bukan array:', toastrMessages);
                    }

                    <?php unset($_SESSION['toastr']); ?>
                <?php endif; ?> 
            </script>


        <?php 
    }
    public function delete($url='/delete_siswa/')
    {
        ?>
            <script type="text/javascript"> 
                $(document).ready(function() {
                    $(document).on('click', '.delete-button', function(e) {
                        e.preventDefault(); 
                        var Id = $(this).data('id');       
                      
                            $.ajax({
                                url: '<?=$url?>' + Id,
                                type: 'DELETE', 
                                success: function(response) {
                                    
                                    location.reload(); 
                                    
                                }                
                            });        
                    });
                });
            </script>
        <?php 
    }
}
