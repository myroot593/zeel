<?php
/**
	* File ini merupakan bagian fungsi untuk meload file - file / module aplikasi,
	* dan ini merupakan bagian penyederhanaan untuk penggunaan require once
	* yang terlihat agak sedikit kurang epektif, karena kita harus mengulang
	* - ngulang perintah tersebuh saat ingin memanggil suatu module.
	* Sehingga dengan menggunakan spl_auto_load_register, fungsi pemanggilan
	* file akan disederhanakan, namun setiap module perlu dikelompokan kedalam class
	* dimana setiap kelas perlu diberi nama yang sama dengan nama filenya.
	* Ketika class dicetak ke objek, maka secara otomatis file berdasarkan nama class
	* akan dipanggil.
	* Anda bisa bermain dengan banyak file pada direktori yang berbeda, Anda hanya cukup
	* membuat fungsinya, dan mengatur dimana letak/path module yang ingin Anda gunakan
	* 
	* Anda juga bisa membuat auto loader ini bekerja sebagai sebuah kelas, dalam auto loader
	* Anda hanya perlu menuliskannya seperti berikut 
	* spl_autoload_register(array('namaclass','nama_method'))
	* atau Anda membuat daftar path module didalam array dengan looping.
	* Contoh :
	
		spl_autoload_register(function($className)
		{

			$list_direkotori = array
			(
				'proja/',
				'projb/',
				'projc/',

			);
			foreach($list_direktori as $list)
			{
				if(file_exists($list.'file_class.'.strtolower($className).'.php'))
				{
		
					require_once($list.'file_class.'.strtolower($className).'.php');
				}
				
			}

		});


**/


function autoloadDatabase($className)
{
	
	$filename =__DIR__.'/../../database/'.$className.'.php';
	if(is_readable($filename))
	{
		require_once $filename;
	}

}
function autoloadCore($className)
{
	
	$filename =__DIR__.'/'.$className.'.php';
	if(is_readable($filename))
	{
		require_once $filename;
	}

}
function autoloadView($className)
{
	$filename =__DIR__.'/../load/'.$className.'.php';
	if(is_readable($filename))
	{
		require_once $filename;
	}
}
function autoloadAjax($className)
{
	$filename =__DIR__.'/../load/Ajax/'.$className.'.php';
	if(is_readable($filename))
	{
		require_once $filename;
	}
}
function handler($className)
{
	$filename =__DIR__.'/../auth/'.$className.'.php';
	if(is_readable($filename))
	{
		require_once $filename;
	}
}
function autoloadPublic($className)
{
	$filename =__DIR__.'/../public/'.$className.'.php';
	if(is_readable($filename))
	{
		require_once $filename;
	}
}

spl_autoload_register("autoloadDatabase");
spl_autoload_register("autoloadCore");
spl_autoload_register("autoloadView");
spl_autoload_register("autoloadAjax");
spl_autoload_register("handler");
spl_autoload_register("autoloadPublic");


$handler = new Auth($databases);
$db = new Database($databases);
$obj = new Apps($databases);
$obj2 = new Appscostum($databases);
$app = new Property;
$theme = new Template($obj);
$upload = new Uploadclass;
/**
 * Contoh - contoh yang pernah di implenebtasikan dalam aplikasi ezakat
 * 
	$dashboard = new Dashboard($obj2, $theme, $app);
	$pengguna = new Useradmin($obj, $app, $salt, $upload);
	$profile = new Userprofile($obj, $app, $salt, $upload);


	$pengelola = new Pengelola($obj, $app, $obj2);
	$zakat = new Zakat($obj, $app, $obj2);
	$penerima = new Penerima($obj, $app, $obj2);
	$penyaluran = new Penyaluran($obj, $obj2, $app);
	$jeniszakat = new Jeniszakat($obj, $app, $obj2);
	$jenisbayar = new Jenispembayaran($obj, $app, $obj2);


	$kec = new Alkec($obj, $app, $obj2);

	$setting = new Settingweb($obj, $app, $upload);


	$modal = new Modal;
	$modaljs = new Modaljs;
	$optionjs = new Ajaxoptionjs;

	
	$pbctheme = new Publictemplate($obj);
	$pbcpengelola = new Publicpengelola($obj, $obj2,  $app, $pbctheme);
	$pbczakat = new Publiczakat($obj, $obj2, $app, $pbctheme);
	$pbcpenyaluran = new Publicpenyaluran($obj, $obj2, $app, $pbctheme);
	$pbcpenerima = new Publicpenerima($obj, $obj2, $app, $pbctheme);
	$pbcdashboard = new Publicdashboard($obj2, $pbctheme, $app);
	$verify = new Verifyuser($obj, $obj2,  $app, $pbctheme);

	$login = new Loginuser($obj, $obj2, $app, $salt, $pbctheme);
**/


$modul = new Moduleload
(
	
	$obj, 
	$obj2,
	$login,
	$app, 
	$theme
	
);

