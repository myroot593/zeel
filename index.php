<?php
/**
 * Contoh pemanggilan aplikasi 
 * 
	require "../database/Settings.php";
	require "../app/core/Splautoload.php";
	session_set_save_handler($handler, true);
	session_start();

	# Mengecek apakah session sebelumnya berhasil dibuat
	# jika belum, arahkan ke login.
	# Jika login sebelumnya ada, maka batasi waktu sesi aktip
	# dan jika sessi tidak dalam waktu misal 800, maka lakukan
	# penghapusan session data di database

	if(empty($handler->read(session_id())))
	{

		header("location:../?page=login");
	}
	else
	{

		if($obj2->limitSession(time(),$_SESSION['created'], $limit=800))
		{
			if(session_destroy())header("location:../?page=login");			
		}
		$_SESSION['created']=time();		
	}

	# Mengecek jenis role login, jika role merupakan administrator
	# jika sebagai administartor jalankan modul admin, jika sebagai petugas
	# jalan modul metugas

	$modul->apprun_user('../',$_SESSION['uid']);
**/

require "database/Settings.php";
require "app/core/Splautoload.php";
require "app/core/PHPMailerpublic.php";
require "app/core/MailSender.php";

$app->getEmpty('page');
$current=$app->get('page');
$array = array('login','login_maintenance');

if(!in_array($current, $array))
{
	session_start();
	if(!empty($handler->read(session_id())))header("location:user/?page=home");
	
}else{

	session_set_save_handler($handler, true);
	session_start();
	if(!empty($handler->read(session_id())))header("location:user/?page=home");
}
$handler->gc(200000);
//$modul->apprun_public_bootstraplander($path=null, $file='captcha.php');
$modul->apprun_public($path=null, $file='captcha.php');



?>

