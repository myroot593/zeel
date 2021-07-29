<?php
/**
    
	
	| Session handler ini bertugas untuk menangani session yang
	| disimpan kedalam database, proses penyimpanan ini menggunakan
	| konsep atau metode session handler yang terdapat pada halaman
	| dokumentasi php.net, ketika sesion_set_handler dipanggil, dan
	| sesion_start() didefinisikan, maka secara otomatis handler
	| akan menyimpannya kedalam database, tetapi ini tidak akan
	| menyimpan session data, jika $_SESSION['data'] belum didefinisikan
	| pada kode atau halaman, dan ini yang akan menjadi sebuah pembeda antara
	| user login dan belum login, user yang belum login akan dibuatkan sesi
	| tetapi data session mereka kosong.
	|
	| Metode yang saya tulis ini mungkin masih belum sempurna, dan saya
	| berharap, programmer berikutnya dapat menulis ulang, menyempurnakan, dan
	| mendokumentasikan, serta membagikannya secara lebih baik.
	
	
**/
class Auth implements SessionHandlerInterface
{
	private $databases;
	protected $koneksi;
	public function __construct($databases)
	{
		try
		{
			$this->databases = $databases;		
			$this->koneksi = new PDO("mysql:host=".$this->databases['default']['default']['host']."; dbname=".$this->databases['default']['default']['database']."",$this->databases['default']['default']['username'], $this->databases['default']['default']['password']);
			$this->koneksi->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		return $this->koneksi;
	}
	public function open($save_path, $session_name)
	{
		if($this->koneksi):
			return true;
		else:
			return false;
		endif;
	}
	public function read($sessionId)
	{
		try
		{
			$sql = "SELECT session_data FROM session WHERE sid=:sid";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam(":sid", $sessionId);
			$stmt->execute();
			$stmt->bindColumn("session_data",$sessionData);
			$stmt->fetch(PDO::FETCH_BOUND);
			return $sessionData ? $sessionData : '';
		}
		catch (PDOException $e)
		{
			return '';
		}
	}
	public function write($sessionId, $sessionData)
	{
		try
		{
			$time = time();
			$sql = "REPLACE INTO session(sid, created, session_data) VALUES(:sid, :created, :session_data)";
			$stmt=$this->koneksi->prepare($sql);
			$stmt->bindParam(":sid", $sessionId);
			$stmt->bindParam(":created", $time);
			$stmt->bindParam(":session_data",$sessionData);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			return false;
		}
	}
	public function destroy($sessionId)
	{
		try
		{
			$sql = "DELETE FROM session WHERE sid=:sid";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->execute(array(":sid"=>$sessionId));
			return true;
		}
		catch(PDOException $e)
		{
			return false;
		}
	}
	/*
		public function gc($maxliftime)
	{
		try
		{
			$pas = time() - $maxliftime;
			$sql = "DELETE FROM session WHERE created < ?";
			$stmt = $this->koneksi->prepare($sql);
			$stmt->bindParam("i",$pas);
			$stmt->execute();
			return true;

		}
		catch(PDOException $e)

		{
			
			return false;
		}
	}
	*/
	public function gc($maxliftime)
	{
		try
		{
			//$pas = time() - $maxliftime;
			//$sql = "DELETE FROM session WHERE created < '$pass'";
			$sql = "DELETE FROM session WHERE created  < DATE_SUB(NOW(), INTERVAL $maxliftime SECOND )";
			$stmt = $this->koneksi->query($sql);			
			$stmt->execute();
			return $stmt;


		}
		catch(PDOException $e)

		{
			
			echo $e->getMessage();
		}
	}
	public function close()
	{
		return true;
	}
}
