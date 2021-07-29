<?php
/**
 * 
 * Contoh penggunaan core template berdasarkan tabel user dan setting
 * 
**/

class Template extends Apps
{
	protected $obj;
	public $row;
	
	public function __construct($obj)
	{
		$this->obj = $obj;
		$this->row = $this->setting();
		
	}
	public function setting()
	{
		$data = $this->obj->selectTable('settings',"id_settings='1'");
		$data->execute();		
		$this->row=$data->fetch(PDO::FETCH_ASSOC);
		return $this->row;
	}
	public function getuser_data($user=null)
	{
		$data = $this->obj->selectTable('users', "uid='$user'");
		$data->execute();
		$this->user = $data->fetch(PDO::FETCH_ASSOC);
		return $this->user;
	}
	public function head($get=null)
	{
		
		$intitle = (!empty($get))?$get:$this->row['slogan'];
		echo
		'
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
			<meta name="description" content="'.$this->row['description'].'">
			<meta name="author" content="'.$this->row['author'].'">
			<link rel="icon" href="../content/public/img/'.$this->row['icon'].'">
			<title>'.$this->row['title'].' - '.$intitle.'</title>
		';
	}
	public function css($path=null)
	{

		echo '
		<!-- Main Styles -->
			
			
			<!-- Material Design Icon -->
			<link rel="stylesheet" href="'.$path.'assets/fonts/material-design/css/materialdesignicons.css">

			<!-- mCustomScrollbar -->
			<link rel="stylesheet" href="'.$path.'assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.min.css">

			<!-- Waves Effect -->
			<link rel="stylesheet" href="'.$path.'assets/plugin/waves/waves.min.css">
			<link rel="stylesheet" href="'.$path.'assets/plugin/flexdatalist/jquery.flexdatalist.min.css">
			<!-- Data Tables -->
			<link rel="stylesheet" href="'.$path.'assets/plugin/datatables/media/css/dataTables.bootstrap.min.css">
			<link rel="stylesheet" href="'.$path.'assets/plugin/datatables/extensions/Responsive/css/responsive.bootstrap.min.css">			
			<!-- Horizonatl Theme -->
			<link rel="stylesheet" href="'.$path.'assets/styles/style-horizontal.min.css">
			<link rel="stylesheet" href="'.$path.'assets/styles/custom.css">		
			<!-- Dropify -->
			<link rel="stylesheet" href="'.$path.'assets/plugin/dropify/css/dropify.min.css">

		
		
		</head>

		<body>
		';
	}
	public function footer($path=null)
	{
		
		echo'
					<footer class="footer">
						<ul class="list-inline">
							<li>2016 © <a href="'.$this->row['link'].'" title="url" target="_blank">'.$this->row['title'].'</a></li>
							<li><a href="#">Tentang kami</a></li>
							<li><a href="#">Faq</a></li>
							
						</ul>
					</footer>
				</div>
				<!-- /.main-content -->
			</div>
		
			

		';
	}
	public function js($path=null)
	{
		echo'

	
			<script src="'.$path.'assets/scripts/jquery.min.js"></script>
			<script src="'.$path.'assets/scripts/modernizr.min.js"></script>
			<script src="'.$path.'assets/plugin/bootstrap/js/bootstrap.min.js"></script>
			<script src="'.$path.'assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
			<script src="'.$path.'assets/plugin/nprogress/nprogress.js"></script>		
			<script src="'.$path.'assets/plugin/waves/waves.min.js"></script>
			<script src="'.$path.'assets/plugin/flexdatalist/jquery.flexdatalist.min.js"></script>
			<script src="'.$path.'assets/plugin/validator/validator.min.js"></script>
			<script src="'.$path.'assets/plugin/maxlength/bootstrap-maxlength.min.js"></script>
			<script src="'.$path.'assets/scripts/form.demo.min.js"></script>		
			<!-- Dropify -->
			<script src="'.$path.'assets/plugin/dropify/js/dropify.min.js"></script>
			<script src="'.$path.'assets/scripts/fileUpload.demo.min.js"></script>
			<!-- Data Tables -->
			<script src="'.$path.'assets/plugin/datatables/media/js/jquery.dataTables.min.js"></script>
			<script src="'.$path.'assets/plugin/datatables/media/js/dataTables.bootstrap.min.js"></script>
			<script src="'.$path.'assets/plugin/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
			<script src="'.$path.'assets/plugin/datatables/extensions/Responsive/js/responsive.bootstrap.min.js"></script>
			<script src="'.$path.'assets/scripts/datatables.demo.min.js"></script>
			<script src="'.$path.'assets/scripts/main.min.js"></script>
			<script src="'.$path.'assets/scripts/horizontal-menu.min.js"></script>
			<script src="'.$path.'assets/plugin/tinymce/tinymce.min.js"></script>
			<script src="'.$path.'assets/scripts/init-tinymce.js"></script>
			
			
		</body>
		</html>

		';
	}
	public function menu($user=null)
	{
		$this->getuser_data($user);
		if(($this->user['role']!='administrator')?$this->menu_petugas($user):$this->menu_admin($user));
			

	}
	public function menu_admin($user=null)
	{
		echo'
				<header class="fixed-header">
					<div class="header-top">
						<div class="container">
							<div class="pull-left">
								<img class="img-fluid" src="../content/public/img/'.$this->row['logo'].'" alt="image" width="40px" height="50px">
								<a href="?page=home" class="logo">'.$this->row['title'].'</a>
							</div>
							<!-- /.pull-left -->
							<div class="pull-right">
								
								
								<div class="mobile-menu ico-item toggle-hover js__drop_down ">
								<span class="fa fa-bars js__drop_down_button"></span>
								<div class="toggle-content">
									<ul>
										<li><a href="?page=zakat"><i class="ico mdi mdi-book-open-page-variant"></i><span class="txt">Zakat</span></a></li>

										<li><a href="?page=pengelola"><i class="ico mdi mdi-group mdi-hc-fw"></i><span class="txt">Pengelola</span></a></li>

										<li><a href="?page=penyaluran"><i class="ico mdi mdi-share-variant"></i><span class="txt">Penyaluran</span></a></li>

										<li><a href="?page=penerima"><i class="ico mdi mdi-account-multiple-outline"></i><span class="txt">Penyaluran</span></a></li>
										
									</ul>
									
								</div>
								<!-- /.toggle-content -->
							</div>
								
								
							
								<!-- /.ico-item -->
								<div class="ico-item">
									<a href="#" class="ico-item fa fa-user js__toggle_open" data-target="#user-status"></a>
									<div id="user-status" class="user-status js__toggle">
										<a href="#" class="avatar"><img src="../content/user/'.$this->user['photo'].'" width="80" height="80" alt="user_picture"><span class="status online"></span></a>
										<h5 class="name"><a href="?page=profile">'.$this->user['name'].'</a></h5>
										<h5 class="position">'.$this->user['role'].'</h5>
										<!-- /.name -->
										<div class="control-items">
											<div class="control-item"><a href="?page=profile" title="Settings"><i class="fa fa-gear"></i></a></div>
											<div class="control-item">
												<a href="#" data-toggle="modal" data-target="#logoutModal" title="Log out"><i class="fa fa-power-off"></i></a>
											</div>
										</div>
										<!-- /.control-items -->
									</div>
									<!-- /#user-status -->
								</div>
								<!-- /.ico-item -->
								<div class="ico-item">
									<a href="?page=settings" class="ico-item fa fa-gear"></a>
									
									<!-- /#user-status -->
								</div>
								<!-- /.ico-item -->
							</div>
							<!-- /.pull-right -->
						</div>
						<!-- /.container -->
					</div>
					<!-- /.header-top -->
					<nav class="nav-horizontal">
						<button type="button" class="menu-close hidden-on-desktop js__close_menu"><i class="fa fa-times"></i><span>CLOSE</span></button>
						<div class="container">
							
							<ul class="menu">
									<li>
										<a href="?page=home"><i class="ico mdi mdi-view-dashboard"></i><span>Dashboard</span></a>
									</li>
									<li class="has-sub">
										<a href="#"><i class="ico mdi mdi-database-plus"></i><span>Master</span></a>
										<ul class="sub-menu mega mega-3">
											<li class="has-sub">
												<h3 class="title">Zakat</h3>
												<!-- .title -->
												<ul class="child-list">
													<li><a href="?page=zakat">Zakat</a></li>
													<li><a href="?page=penerima">Penerima</a></li>
													<li><a href="?page=pengelola">Pengelola</a></li>
													<li><a href="?page=penyaluran">Penyaluran</a></li>
													<li><a href="?page=jenis zakat">Jenis Zakat</a></li>
													<li><a href="?page=jenis pembayaran">Jenis Pembayaran</a></li>
												</ul>
												<!-- /.child-list -->
											</li>
											<li class="has-sub">
												<h3 class="title">Qurban</h3>
												<!-- .title -->
												<ul class="child-list">
													<li><a href="#">Data Qurban</a></li>
													<li><a href="#">Penerima</a></li>
													<li><a href="#">Pemberi Qurban</a></li>
													
												</ul>
												<!-- /.child-list -->
											</li>
											<li class="has-sub">
												<h3 class="title">Others</h3>
												<!-- .title -->
												<ul class="child-list">
													<li><a href="?page=pengguna">Pengguna</a></li>
													<li><a href="?page=kecamatan">Kecamatan</a></li>
													<li><a href="?page=desa">Desa</a></li>
													
												</ul>
												<!-- /.child-list -->
											</li>
										</ul>
										<!-- /.sub-menu mega -->
									</li>
									<li>
									<!--
									<li class="has-sub">
										<a href="#"><i class="ico mdi mdi-database-plus"></i><span>Master</span></a>
										<ul class="sub-menu single">
											<li><a href="?page=zakat">Zakat</a></li>
											<li><a href="?page=penerima">Penerima</a></li>
											<li><a href="?page=pengelola">Pengelola</a></li>
											<li><a href="?page=penyaluran">Penyaluran</a></li>
											<li><a href="?page=jenis zakat">Jenis Zakat</a></li>
											<li><a href="?page=jenis pembayaran">Jenis Pembayaran</a></li>									
											<li><a href="?page=pengguna">Pengguna</a></li>
											

										
										</ul>
										<!-- /.sub-menu single -->
									</li>
									-->
								
									<li>
										<a href="?page=zakat"><i class="ico mdi mdi-book-open-page-variant"></i><span>Zakat</span></a>
									</li>
									<li>
										<a href="?page=pengelola"><i class="ico mdi mdi-group mdi-hc-fw"></i><span>Pengelola</span></a>
									</li>
									<li>
										<a href="?page=penyaluran"><i class="ico mdi mdi-share-variant"></i><span>Penyaluran</span></a>
									</li>
									<li>
										<a href="?page=pengguna"><i class="ico mdi mdi-account"></i><span>Pengguna</span></a>
									</li>
									<!--
									<li>
										<a href="#"><i class="ico mdi mdi-cloud-download"></i><span>Backup</span></a>
									</li>
									-->
									

									
							</ul>
							<!-- /.menu -->

						</div>
						<!-- /.container -->
					</nav>
					<!-- /.nav-horizontal -->
				</header>
				<!-- /.fixed-header -->

			';
	}
	public function menu_petugas($user=null)
	{
		$this->getuser_data($user);
		echo'
			<header class="fixed-header">
				<div class="header-top">
					<div class="container">
						<div class="pull-left">
							<img class="img-fluid" src="http://localhost/pssi/content/web/logo.png" alt="image" width="40px" height="50px">
							<a href="?page=home" class="logo">'.$this->row['title'].'</a>
						</div>
						<!-- /.pull-left -->
						<div class="pull-right">
							
							<!-- /.ico-item hidden-on-desktop -->
							
							<!-- /.ico-item -->
							<div class="mobile-menu ico-item toggle-hover js__drop_down ">
								<span class="fa fa-bars js__drop_down_button"></span>
								<div class="toggle-content">
									<ul>
										<li><a href="?page=zakat"><i class="ico mdi mdi-book-open-page-variant"></i><span class="txt">Zakat</span></a></li>

										<li><a href="?page=pengelola"><i class="ico mdi mdi-group mdi-hc-fw"></i><span class="txt">Pengelola</span></a></li>

										<li><a href="?page=penyaluran"><i class="ico mdi mdi-share-variant"></i><span class="txt">Penyaluran</span></a></li>

										<li><a href="?page=penerima"><i class="ico mdi mdi-account-multiple-outline"></i><span class="txt">Penyaluran</span></a></li>
										
									</ul>
									
								</div>
								<!-- /.toggle-content -->
							</div>
							
							
						
							<!-- /.ico-item -->
							<div class="ico-item">
								<a href="#" class="ico-item fa fa-user js__toggle_open" data-target="#user-status"></a>
								<div id="user-status" class="user-status js__toggle">
									<a href="#" class="avatar"><img src="../content/user/'.$this->user['photo'].'" width="80" height="80" alt="user_picture"><span class="status online"></span></a>
									<h5 class="name"><a href="?page=profile">'.$this->user['name'].'</a></h5>
									<h5 class="position">'.$this->user['role'].'</h5>
									<!-- /.name -->
									<div class="control-items">
										<div class="control-item"><a href="?page=profile" title="Settings"><i class="fa fa-gear"></i></a></div>
										<div class="control-item">
											<a href="#" data-toggle="modal" data-target="#logoutModal" title="Log out"><i class="fa fa-power-off"></i></a>
										</div>
									</div>
									<!-- /.control-items -->
								</div>
								<!-- /#user-status -->
							</div>
							<!-- /.ico-item -->
							
							<!-- /.ico-item -->
						</div>
						<!-- /.pull-right -->
					</div>
					<!-- /.container -->
				</div>
				<!-- /.header-top -->
				<nav class="nav-horizontal">
					<button type="button" class="menu-close hidden-on-desktop js__close_menu"><i class="fa fa-times"></i><span>CLOSE</span></button>
					<div class="container">
						
						<ul class="menu">
								<li>
									<a href="?page=home"><i class="ico mdi mdi-view-dashboard"></i><span>Dashboard</span></a>
								</li>
							
							
								<li>
									<a href="?page=zakat"><i class="ico mdi mdi-book-open-page-variant"></i><span>Zakat</span></a>
								</li>
								<li>
									<a href="?page=penerima"><i class="ico mdi mdi-account-multiple-outline"></i><span>Penerima</span></a>
								</li>
								<li>
									<a href="?page=penyaluran"><i class="ico mdi mdi-share-variant"></i><span>Penyaluran</span></a>
								</li>
								
							
								

								
						</ul>
						<!-- /.menu -->

					</div>
					<!-- /.container -->
				</nav>
				<!-- /.nav-horizontal -->
			</header>
			<!-- /.fixed-header -->

		';
	}
	
}



?>