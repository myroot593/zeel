<?php
/**
 * Contoh struktur kode modul
 * 
	class Publicdashboard
	{
		
		private $obj2;
		private $pbctheme;
		protected $app;

		public function __construct($obj2, $pbctheme, $app)
		{
			
			$this->obj = $obj2;
			$this->pbctheme = $pbctheme;
			$this->app = $app;

		}

		public function homeImage()
		{
			?>
			<header class="py-5 bg-image-full bg-image-height" style="background-image: url(content/public/img/<?=$this->pbctheme->row['cover'];?>);"> 
			 

		    </header>
		   
			<?php
		}
		public function home ($path=null)
		{
			
			$this->pbctheme->head($this->app->get('page'));
			$this->pbctheme->css(''.$path.'themes/zeiss/horizontal/');
			$this->pbctheme->menu();
			$this->homeImage();
			
			?>
	       
			<div class="container">
			   <h3>Selamat Datang di situs <?=$this->pbctheme->row['title'];?></h3>
			       <p><?=$this->pbctheme->row['description'];?> </p>	           
			       <hr>
			  
		
				<div class="row small-spacing">
				<div class="col-lg-3 col-md-6 col-xs-12">
					<div class="box-content bg-success text-white">
						<div class="statistics-box with-icon">
							<i class="ico mdi mdi-book-open-page-variant"></i>
							<a class="text-white" href="?page=zakat">ZAKAT</a>
							<h2 class="counter"><?=$this->obj->countTable('field_zakat'); ?></h2>
						</div>
					</div>
					<!-- /.box-content -->
				</div>
				<!-- /.col-lg-3 col-md-6 col-xs-12 -->
				<div class="col-lg-3 col-md-6 col-xs-12">
					<div class="box-content bg-info text-white">
						<div class="statistics-box with-icon">
							<i class="ico mdi mdi-group mdi-hc-fw"></i>
							<a class="text-white" href="?page=pengelola">PENGELOLA</a>
							<h2 class="counter"><?=$this->obj->countTable('field_pengelola_zakat'); ?></h2>
						</div>
					</div>
					<!-- /.box-content -->
				</div>
				<!-- /.col-lg-3 col-md-6 col-xs-12 -->
				<div class="col-lg-3 col-md-6 col-xs-12">
					<div class="box-content bg-danger text-white">
						<div class="statistics-box with-icon">
							<i class="ico mdi mdi-share-variant"></i>
							<a class="text-white" href="?page=penyaluran">PENYALURAN</a>
							<h2 class="counter"><?=$this->obj->countTable('field_penyaluran_zakat'); ?></h2>
						</div>
					</div>
					<!-- /.box-content -->
				</div>
				<!-- /.col-lg-3 col-md-6 col-xs-12 -->
				<div class="col-lg-3 col-md-6 col-xs-12">
					<div class="box-content bg-warning text-white">
						<div class="statistics-box with-icon">
							<i class="ico mdi mdi-account-multiple-outline"></i>
							<a class="text-white" href="?page=penerima">DAFTAR PENERIMA</a>
							<h2 class="counter"><?=$this->obj->countTable('field_penerima_zakat'); ?></h2>
						</div>

					</div>
					<!-- /.box-content -->
				</div>
				<!-- /.col-lg-3 col-md-6 col-xs-12 -->
				</div>
			</div>
			<div class="container">
				<div class="main-content container">
		<?php

			$this->pbctheme->footer();
			$this->pbctheme->js('themes/zeiss/horizontal/');
		}
		public function __destruct()
		{
			return true;
		}
	}
**/
?>	

				
