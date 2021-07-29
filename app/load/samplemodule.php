<?php


/**
 * Contoh struktur kode modul
 * 
	class Zakat
	{
		protected $obj;
		protected $app;
		protected $obj2;

		public function __construct($obj, $app, $obj2)
		{
			$this->obj = $obj;
			$this->app = $app;
			$this->obj2 = $obj2;


		}
		protected function cekakses($data)
		{
			//$status = $this->obj2->joinAccess($_SESSION['uid']);
				
			if($data->rowCount()>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public function datazakat()
		{
			
			if($this->cekakses($d=$this->obj2->joinAccess($_SESSION['uid'])))
			{
				$akses = $d->fetch(PDO::FETCH_ASSOC);
				

				
			}
			else
			{
				$row = array();
				$this->app->reload(3,'?page=profile');
				die($this->app->alert('alert-danger','Saat ini akses anda belum ditambahkan'));
				
				
			}
			
			?>	
			<?=$this->table_jeniszakat();?>
			<div class="col-lg-12 col-xs-12">
					<div class="box-content">
						<h4 class="box-title">Data Zakat</h4>
						<div class="dropdown js__drop_down">
							<a href="#" class="dropdown-icon glyphicon glyphicon-option-vertical js__drop_down_button"></a>
							<ul class="sub-menu">
								<li><a href="?page=zakat add">Tambah zakat</a></li>							
							</ul>
								
						</div>
						<p>	Saat ini Anda mengelola zakat untuk <b><?=$this->obj2->readId('nama_pengelola','field_pengelola_zakat','id_pengelola',$akses['id_pengelola']); ?></b>. Data zakat pada halaman ini akan ditampilkan berdasarkan data yang Anda kelola</p>		
						
							<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered display" style="width:100%">
							
								<thead>
									<tr>
										<th colspan="2">Total Zakat</th>
										<th><?=$this->obj2->countTable('field_zakat', "id_pengelola='".$akses['id_pengelola']."'"); ?></th>
										<th>Dana untuk disalurkan</th>
										<th><?=$this->app->rupiah($this->obj2->sumTable('field_zakat','nominal', "id_pengelola='".$akses['id_pengelola']."'")); ?></th>
									</tr>
									<tr>
										<th>No</th>
										<th>Tanggal</th>
										<th>Pemberi Zakat</th> 							
										<th>Jenis Pembayaran</th>
										<th>Aksi</th>						
								
										
									</tr> 
								</thead> 
								<tbody> 


									<?php

										$data=$this->obj->pagination('halaman', 'field_zakat', 15, "id_pengelola='".$akses['id_pengelola']."'");
										$no =1;
										if($data->rowCount()>0)
										{
											while($row=$data->fetch(PDO::FETCH_ASSOC)):
												echo
												'
													<tr>
														<td>'.$no.'</td>
														<td>'.$row['time'].'</td>
														<td style="text-transform:uppercase;">'.$row['nama_pemberi_zakat'].'</td>
														
														<td>
															'.
															$this->obj2->readId('jenis_pembayaran','field_jenis_pembayaran_zakat','id_jenis_pembayaran',$row['jenis_pembayaran'])
															.'
														</td>
														

														<td>

														

															<a href="?page=zakat delete&id_zakat='.base64_encode($row['id_zakat']).'" class="btn btn-danger btn-circle btn-xs waves-effect waves-light"><i class="ico fa fa-trash"></i>
															</a>

														</td>
														
													</tr>
												';
												$no+=1;
											endwhile;
										}else{
											echo '<tr>
													<td colspan="4" align="center"><em>Data tidak/belum ada</em></td>
												</tr>
													';
										}

										

									?>
									
								</tbody> 
							</table>
							</div>
				
					

					</div>	
					<div align="center"><?php $this->obj->paginationNumber('field_zakat',15,"page=zakat", 'halaman',"id_pengelola='".$akses['id_pengelola']."'");?></div>	
			</div>
		<?php
		}
		protected function table_jeniszakat()
		{
			
			?>	
			<div class="col-lg-12 col-xs-12">
					<div class="box-content">
						<h4 class="box-title">Data Jenis Zakat</h4>
						
							<p>Klik tambah zakat untuk menambah data pemberi zakat</p>
							<div class="table-responsive">
							<table class="table table-striped table-bordered display" style="width:100%">
							
								<thead>
									<tr>
									
										<th>Jenis Zakat</th> 
										<th>Biaya</th>
										<th>Aksi</th>

								
										
									</tr> 
								</thead> 
								<tbody> 

									<?php
										$data=$this->obj->selectTable('field_jenis_zakat');
										$no =1;
										if($data->rowCount()>0)
										{
											while($row=$data->fetch(PDO::FETCH_ASSOC)):
												echo
												'
													<tr>
														
														<td>'.$row['jenis_zakat'].'</td>
														<td>'.$this->app->rupiah($row['biaya']).'</td>								

														<td>

															<a class="btn btn-info btn-sm" href="?page=zakat add2&id='.$row['id_jenis_zakat'].'" class="btn btn-info btn-circle btn-xs waves-effect waves-light delete_data">
															Tambah zakat
															</a>
															


														</td>
														
													</tr>
												';
												
											endwhile;
										}else{
											echo '<tr>
													<td colspan="3" align="center"><em>Data tidak/belum ada</em></td>
												</tr>
													';
										}

										

									?>
									
								</tbody> 
							</table>

							</div>
					

					</div>	
					
			</div>
		<?php
		}
		public function add_zakat()
		{
			if($this->cekakses($d=$this->obj2->joinAccess($_SESSION['uid'])))
			{
				$row = $d->fetch(PDO::FETCH_ASSOC);

			}
			else
			{
				$this->app->alert('alert-danger','Saat ini akses anda belum ditambahkan');
				$this->app->reload(1,'?page=home');
			}
			$err = array();
			if(isset($_POST['add_zakat']))
			{
				

				if(empty($this->app->post('nama_pemberi'))){
					array_push($err, "Nama pemberi zakat harus diisi");
				}else{
					$nama_pemberi = $this->app->post('nama_pemberi');
				}
				
				$jenis_zakat = $this->app->post('jenis_zakat');
				$jenis_pembayaran = $this->app->post('jenis_pembayaran');
				$nominal = $this->app->post('nominal');

				if(count($err)==0)
				{
					if($this->obj->insertTable('field_zakat','uid, id_pengelola, jenis_zakat, jenis_pembayaran, nama_pemberi_zakat,nominal, tanggal_zakat, tahun_zakat, created',':uid, :id_pengelola, :jenis_zakat, :jenis_pembayaran, :nama_pemberi_zakat, :nominal, :tanggal_zakat, :tahun_zakat, :created',array
						(
							":uid"=>$row['uid'],
							":id_pengelola"=>$row['id_pengelola'],
							":jenis_zakat"=>$jenis_zakat,
							":jenis_pembayaran"=>$jenis_pembayaran,						
							":nama_pemberi_zakat"=>$nama_pemberi,
							":nominal"=>$nominal,
							":tanggal_zakat"=>$this->app->tampilTanggal(),
							":tahun_zakat"=>date('Y'),
							":created"=>time()
						)))
					{
						$this->app->alert('alert-success','Data zakat berhasil ditambahkan');
						
					}
					else
					{
						$this->app->alert('alert-danger','Gagal menambahkan zakat');
					}
				}
			}

			?>

			<?php 
			if(count($err)>0)
			{
				$this->app->alert('alert-danger',$this->app->getError($err));
			}
			?>
			<div class="row">
			<div class="col-lg-12 col-md-4 col-xs-12">
			<div class="box-content card white">
			<h4 class="box-title">Tambah Zakat</h4>				
			<div class="card-content">
				<p>Untuk kategori pembayaran menggunakan beras tetap akan dihitung dalam nominal uang</p>
			<form data-toggle="validator" novalidate="true" action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>" method="post">
				<div class="form-group has-feedback">
					<label for="inputNama" class="control-label">Nama pemberi zakat</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="ico fa fa-user"></i> </span>
							<input type="text" maxlength="65" minlength="3" class="form-control" id="inputNama" name="nama_pemberi" pattern="^[a-zA-Z ]*$" placeholder="nama pemberi zakat" data-error="Masukan nama dengan benar" required>
						</div>
					<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
					<div class="help-block with-errors">Nama pemberi zakat</div>
					
				</div>
				<div class="row">
					<div class="form-group col-sm-4 has-feedback">
						<label for="jenis_zakat" class="control-label">Jenis zakat</label>
						<select class="form-control" name="jenis_zakat" id="fitrah-biaya" onChange="getFitrah();" required="">
							<option value=""></option>
							<?php
									$jenis = $this->obj->selectTable('field_jenis_zakat');
								
								
									if($jenis->rowCount()>0)
									{
										while($row=$jenis->fetch(PDO::FETCH_ASSOC))

										{
											echo '<option value='.$row['id_jenis_zakat'].'>'.$row['jenis_zakat'].'</option>';
										}
									}

							?>
						</select>
						
					</div>
					<div class="form-group col-sm-4 has-feedback">
						<label for="jenis_pembayaran" class="control-label">Jenis pembayaran</label>
						<select class="form-control" name="jenis_pembayaran" id="jenis_pembayaran" required="">
							
							<?php
									$jenis = $this->obj->selectTable('field_jenis_pembayaran_zakat');
								
								
									if($jenis->rowCount()>0)
									{
										while($row=$jenis->fetch(PDO::FETCH_ASSOC))

										{
											echo '<option value='.$row['id_jenis_pembayaran'].'>'.$row['jenis_pembayaran'].'</option>';
										}
									}

							?>
						</select>
						
					</div>
					<div class="form-group col-sm-4 has-feedback" id="fitrah-list">
					
					
					</div>
				</div>
		
				
				<div class="form-group">
					<button type="submit" name="add_zakat" class="btn btn-primary btn-icon btn-icon-right waves-effect waves-light disabled"><i class="ico fa fa-save"></i> Simpan</button>
				</div>
			</form>

			</div>
			</div>
			</div>
			</div>
		<?php

		}
		public function add_zakat2()
		{
			if(!$this->obj->getTable('field_jenis_zakat', 'id_jenis_zakat=:id_jenis_zakat', $this->app->get('id'), 'id_jenis_zakat'))die($this->app->alert("alert-danger","Data tidak ditemukan"));
			
				
			
				if($this->cekakses($d=$this->obj2->joinAccess($_SESSION['uid'])))
				{
					$row = $d->fetch(PDO::FETCH_ASSOC);

				}
				else
				{
					$this->app->alert('alert-danger','Saat ini akses anda belum ditambahkan');
					$this->app->reload(1,'?page=home');
				}
			$err = array();
			if(isset($_POST['add_zakat']))
			{
				

				if(empty($this->app->post('nama_pemberi'))){
					array_push($err, "Nama pemberi zakat harus diisi");
				}else{
					$nama_pemberi = $this->app->post('nama_pemberi');
				}
				
				$jenis_pembayaran = $this->app->post('jenis_pembayaran');
				

				if(count($err)==0)
				{
					if($this->obj->insertTable('field_zakat','uid, id_pengelola, jenis_zakat, jenis_pembayaran, nama_pemberi_zakat,nominal, tanggal_zakat, tahun_zakat, created',':uid, :id_pengelola, :jenis_zakat, :jenis_pembayaran, :nama_pemberi_zakat, :nominal, :tanggal_zakat, :tahun_zakat, :created',array
						(
							":uid"=>$row['uid'],
							":id_pengelola"=>$row['id_pengelola'],
							":jenis_zakat"=>$this->obj->row['id_jenis_zakat'],
							":jenis_pembayaran"=>$jenis_pembayaran,						
							":nama_pemberi_zakat"=>$nama_pemberi,
							":nominal"=>$this->obj->row['biaya'],
							":tanggal_zakat"=>$this->app->tampilTanggal(),
							":tahun_zakat"=>date('Y'),
							":created"=>time()
						)))
					{
						$this->app->alert('alert-success','Data zakat berhasil ditambahkan');
						
					}
					else
					{
						$this->app->alert('alert-danger','Gagal menambahkan zakat');
					}
				}
			}

			?>

			<?php 
			if(count($err)>0)
			{
				$this->app->alert('alert-danger',$this->app->getError($err));
			}
			?>
			<div class="row">
			<div class="col-lg-12 col-md-4 col-xs-12">
			<div class="box-content card white">
			<h4 class="box-title">Tambah Zakat</h4>				
			<div class="card-content">
				<p>Untuk kategori pembayaran menggunakan beras tetap akan dihitung dalam nominal uang</p>
			<form data-toggle="validator" novalidate="true" action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>" method="post">
				<div class="form-group has-feedback">
					<label for="inputNama" class="control-label">Nama pemberi zakat</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="ico fa fa-user"></i> </span>
							<input type="text" maxlength="65" minlength="3" class="form-control" id="inputNama" name="nama_pemberi" pattern="^[a-zA-Z ]*$" placeholder="nama pemberi zakat" data-error="Masukan nama dengan benar" required>
						</div>
					<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
					<div class="help-block with-errors">Nama pemberi zakat</div>
					
				</div>
				<div class="row">
					<div class="form-group col-sm-4 has-feedback">
						<label for="inputjenis" class="control-label">Jenis zakat</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="ico fa fa-user"></i> </span>
								<input type="text" class="form-control" id="inputjenis" name="jenis_zakat" placeholder="Masukan nama/jenis zakat" value="<?=$this->obj->row['jenis_zakat'];?>"required="" disabled>
							</div>
						<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
					
					
					</div>
					<div class="form-group col-sm-4 has-feedback">
						<label for="jenis_pembayaran" class="control-label">Jenis pembayaran</label>
						<select class="form-control" name="jenis_pembayaran" id="jenis_pembayaran" required="">
							
							<?php
									$jenis = $this->obj->selectTable('field_jenis_pembayaran_zakat');
								
								
									if($jenis->rowCount()>0)
									{
										while($row=$jenis->fetch(PDO::FETCH_ASSOC))

										{
											echo '<option value='.$row['id_jenis_pembayaran'].'>'.$row['jenis_pembayaran'].'</option>';
										}
									}

							?>
						</select>
						
					</div>
					<div class="form-group col-sm-4 has-feedback">
						<label for="inputjenis" class="control-label">Nominal </label>
							<div class="input-group">
								<span class="input-group-addon"><i class="ico fa fa-user"></i> </span>
								<input type="text" class="form-control" value="<?=$this->obj->row['biaya'];?>"required="" disabled>
							</div>
						<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
					
					
					</div>
				</div>
		
				
				<div class="form-group">
					<button type="submit" name="add_zakat" class="btn btn-primary btn-icon btn-icon-right waves-effect waves-light disabled"><i class="ico fa fa-save"></i> Simpan</button>
				</div>
			</form>

			</div>
			</div>
			</div>
			</div>
		<?php

		}
		public function delete_zakat()
		{


			if(!$this->obj->getTable('field_zakat', 'id_zakat=:id_zakat', base64_decode($this->app->get('id_zakat')), 'id_zakat'))
			{
				$this->app->alert("alert-danger","Data tidak ditemukan");
			}
			if($this->obj->row['uid']!=$_SESSION['uid'])die($this->app->alert('alert-danger','Data ini hanya dapat dihapus oleh pembuatnya'));
			$err = array();

			if(isset($_POST['zakat_delete']))
			{
				if(empty($this->app->post('konfirmasi'))){
					array_push($err, "Anda harus mengkonfirmasi penghapusan data");
				}
				if(count($err)==0)
				{
					if($this->obj->delete('field_zakat','id_zakat=:id_zakat','id_zakat',$this->obj->row['id_zakat']))
					{
						
							$this->app->alert('alert-success','Data berhasil dihapus');
							$this->app->reload(1,'?page=zakat');
						
					}
					else
					{
						$this->app->alert('alert-danger','Data pengelola zakat gagal dihapus');
					}
				}
			}
			?>
			<?php 
				if(count($err)>0)
				{
					$this->app->alert('alert-danger',$this->app->getError($err));
				}
			?>
			<div class="row">
			<div class="col-lg-12 col-md-4 col-xs-12">
			<div class="box-content card white">
			<h4 class="box-title">Hapus Pengelola</h4>				
			<div class="card-content">
			<form data-toggle="validator" novalidate="true" action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>" method="post" enctype="multipart/form-data">
				<div class="form-group has-feedback">
					<div class="checkbox margin-bottom-20">
						<input type="checkbox" id="chk-1" name="konfirmasi" required=""><label for="chk-1">Tindakan ini dapat mengahpus data zakat yang sudah ditambahkan</label> 
					</div>
				</div>
				
				
				<div class="form-group">
					<button type="submit" name="zakat_delete" class="btn btn-danger btn-icon btn-icon-right waves-effect waves-light disabled"><i class="ico fa fa-trash"></i> Hapus</button>
				</div>
			</form>

			</div>
			</div>
			</div>
			</div>
		<?php	
		}
		public function __destruct()
		{
			return true;
		}
		
	}
**/


?>
