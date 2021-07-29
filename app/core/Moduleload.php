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
	protected $modulename



	public function __construct($modulename)
	{
			$this->modulename=$modulename;
			
	}
	private function getuser_data($user=null)
	{
		# Contoh untuk mengambil data user dari tabel user

		$data = $this->obj->selectTable('users', "uid='$user'");
		$data->execute();
		$this->user = $data->fetch(PDO::FETCH_ASSOC);
		return $this->user;
	}
	public function apprun_user($path=null, $user=null)
	{
		/**
		 * 
		 * Contoh untuk mengelompokan dan mengatur modul mana yang perlu diberikan
		 * berdasarkan role atau hak aksesnya
		 * jika role dijalankan berdasarkan administrator, maka module admin akan
		 * diberikan kepada user tersebut, dst.
		 * 
		**/
		if(!empty($this->getuser_data($user)))
		{
			if(($this->user['role']!='administrator')?$this->apprun_petugas($path, $user):$this->apprun_admin($path, $user));
		}
	}
	public function apprun_admin($path, $user)
	{
		/**
		 * Contoh pengaksesan file atau halaman module yang dibuat
		 * yang dikelompokan berdasarkan masing - masing fungsi
		 * module - module ini nantinya bisa didefinisikan didalam fungsi apprun_user
		 * yang misalnya dikelompokan atau dibedakan per-role/hak aksesnya
		 * 
		**/
		$this->app->getEmpty('page');
		$this->theme->head($this->app->get('page'));
		$this->theme->css(''.$path.'themes/zeiss/horizontal/');
		$this->theme->menu($user);
		$this->app->wrapper2();

		if($_GET['page']=='home'):
	
		$this->dashboard->dashboard_admin($user);

		
	
		elseif($this->app->get('page')=='login'):

			$this->login->loginpage();	
			
		else:

			$this->dashboard->dashboard_admin($user);

		endif;
		$this->theme->footer();
		$this->modal->modalCrud();
		$this->modal->modalLogout();
		$this->theme->js(''.$path.'themes/zeiss/horizontal/');
		$this->modaljs->jsmodal('../app/load/Ajax/Ajaxdetail.php');
	}
	public function apprun_petugas($path=null, $user=null)
	{
		
		$this->app->getEmpty('page');
		$this->theme->head($this->app->get('page'));
		$this->theme->css(''.$path.'themes/zeiss/horizontal/');
		$this->theme->menu($user);
		$this->app->wrapper2();

		if($_GET['page']=='home'):
		
		$this->dashboard->dashboard_petugas($user);	
					
		else:

			$this->dashboard->dashboard_petugas($user);	
		endif;
		$this->theme->footer();
		$this->modal->modalLogout();
		$this->theme->js(''.$path.'themes/zeiss/horizontal/');
		
	}
	public function apprun_public($path=null)
	{
		/**
		 * 
		 * Nilai parameter ini bisa saja langsung ditambahkan saat fungsi dijalankan seperti 
		 * pada fungsi apprun_admin().
		 * Namun dalam kasus tertentu, mungkin Anda perlu menjalankan atau membutuhkan
		 * variasi lain yang misal tidak membutuhkan sebuah menu, maka properti template
		 * bisa ditambakan pada setiap fungsi module masing - masing, dan menghilangkan
		 * yang tidak dibutuhkan
		 * 

		 * properti atas
		 * $this->app->getEmpty('page');
		 * $this->pbctheme->head($this->app->get('page'));
		 * $this->pbctheme->css(''.$path.'themes/zeiss/horizontal/');
		 * $this->pbctheme->menu();

		 * properti bawah 
		 * $this->pbctheme->footer();
		 * $this->pbctheme->js(''.$path.'themes/zeiss/horizontal/');
		 * 
		 * Properti proprti tersebut ditambahkan pada setiap masing - masing module
			
		**/
	
		$this->app->getEmpty('page');
		$this->app->getEmpty('mail');
		$this->app->getEmpty('hash');
		if($_GET['page']=='home'):
	
			$this->pbcdashboard->home();	
			
		else:
			$this->pbcdashboard->home();
		endif;
		
		
	}
}