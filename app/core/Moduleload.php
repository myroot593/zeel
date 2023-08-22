<?php
/**

 * Bagian ini digunakan untuk meregister atau meload modul - modul 
 * pada setiap parameter url yang diakses, Anda bisa memiliah dan memilih
 * modul mana saja yang akan digunakan untuk diakses pada suatu halaman,
 * setiap modul akan diakses pada setiap request seperti $this->app->get('page')
 * lalu kemudian Anda bisa memanggil nama module atau fungsi yang dibutuhkan
 * yang sudah anda tambahkan midal pada folder core, load atau public.
 * 
 


**/
class Moduleload
{
	
	protected $obj;
	protected $obj2;
	protected $app;
	protected $theme;

	public function __construct($obj, $obj2, $app, $theme)
	{
			$this->obj= $obj;
			$this->obj2 = $obj2;
			$this->app = $app;
			$this->theme = $theme;
			
	}
	public function test()
	{
		$this->theme->head();
		$this->theme->css();
		echo "Hello World !";
		$this->theme->footer();
	}
	
		
		
	
}
