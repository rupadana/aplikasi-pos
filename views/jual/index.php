 <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <?php 
		  $id = $_SESSION['admin']['id_member'];
		  $hasil = Member::member_edit($id);
      ?>
      <section id="main-content">
          <section class="wrapper">
              <div class="row">
                  <div class="col-lg-12 main-chart">
						<h3>Keranjang Penjualan</h3>
						<br>
						<?php if(isset($_GET['success'])){?>
						<div class="alert alert-success">
							<p>Edit Data Berhasil !</p>
						</div>
						<?php }?>
						<?php if(isset($_GET['remove'])){?>
						<div class="alert alert-danger">
							<p>Hapus Data Berhasil !</p>
						</div>
						<?php }?>
						<div class="col-sm-4">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h4><i class="fa fa-search"></i> Cari Barang</h4>
								</div>
								<div class="panel-body">
									<input type="text" id="cari" class="form-control" name="cari" placeholder="Masukan : Kode / Nama Barang  [ENTER]">
								</div>
							</div>
						</div>
						<div class="col-sm-8">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h4><i class="fa fa-list"></i> Hasil Pencarian</h4>
								</div>
								<div class="panel-body">
									<div id="hasil_cari"></div>
									<div id="tunggu"></div>
									
								</div>
							</div>
						</div>
						

						<div class="col-sm-12">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h4><i class="fa fa-shopping-cart"></i> KASIR
									<a class="btn btn-danger pull-right" 
										onclick="javascript:return confirm('Apakah anda ingin reset keranjang ?');" 
										style="margin-top:-0.5pc;" href="controllers/penjualan.php?action=reset-keranjang">
										<b>RESET KERANJANG</b></a>
									</h4>
								</div>
								<div class="panel-body">
									<div id="keranjang">
										<table class="table table-bordered">
											<tr>
												<td><b>Tanggal</b></td>
												<td><input type="text" readonly="readonly" class="form-control" value="<?php echo date("j F Y, G:i");?>" name="tgl"></td>
											</tr>
										</table>
										<table class="table table-bordered" id="example1">
											<thead>
												<tr>
													<td> No</td>
													<td> Nama Barang</td>
													<td style="width:10%;"> Jumlah</td>
													<td style="width:20%;"> Total</td>
													<td> Kasir</td>
													<td> Aksi</td>
												</tr>
											</thead>
											<tbody>
												<?php $total_bayar=0; $no=1; $hasil_penjualan = Penjualan::penjualans();?>
												<?php foreach($hasil_penjualan  as $isi){;?>
												<tr>
													<td><?php echo $no;?></td>
													<td><?php echo $isi['nama_barang'];?></td>
													<td>
												<!-- aksi ke table penjualan -->
												<form method="POST" action="controllers/penjualan.php?action=edit">
														<input type="number" name="jumlah" value="<?php echo $isi['jumlah'];?>" class="form-control">
														<input type="hidden" name="id" value="<?php echo $isi['id_penjualan'];?>" class="form-control">
														<input type="hidden" name="id_barang" value="<?php echo $isi['id_barang'];?>" class="form-control">
													</td>
													<td>Rp.<?php echo number_format($isi['total']);?>,-</td>
													<td><?php echo $isi['nm_member'];?></td>
													<td>
														<button type="submit" class="btn btn-warning">Update</button>
												</form>
												<!-- aksi ke table penjualan -->
														<a href="controllers/penjualan.php?action=delete&id=<?php echo $isi['id_penjualan'];?>&brg=<?php echo $isi['id_barang'];?>
														&jml=<?php echo $isi['jumlah']; ?>"  class="btn btn-danger"><i class="fa fa-times"></i>
														</a>
													</td>
												</tr>
												<?php $no++; $total_bayar += $isi['total'];}?>
											</tbody>
									</table>
									<br/>
									<?php $hasil = Penjualan::jumlah(); ?>
									<div id="kasirnya">
										<table class="table table-stripped">
											<?php
											// proses bayar dan ke nota
											if(isset($_GET['nota']) && !empty($_GET['nota'] == 'yes')) {
												$total = $_POST['total'];
												$bayar = $_POST['bayar'];
												if(!empty($bayar))
												{
													$hitung = $bayar - $total;
													if($bayar >= $total)
													{
														$id_barang = $_POST['id_barang'];
														$id_member = $_POST['id_member'];
														$jumlah = $_POST['jumlah'];
														$total = $_POST['total1'];
														$tgl_input = $_POST['tgl_input'];
														$periode = $_POST['periode'];
														$jumlah_dipilih = count($id_barang);
														
														for($x=0;$x<$jumlah_dipilih;$x++){

															$d = array($id_barang[$x],$id_member[$x],$jumlah[$x],$total[$x],$tgl_input[$x],$periode[$x]);

															DB::table("nota")->insert([
																"id_barang" => $id_barang[$x],
																"id_member" => $id_member[$x],
																"jumlah" => $jumlah[$x],
																"total" => $total[$x],
																"tanggal_input" => $tgl_input[$x],
																"periode" => $periode[$x],
															]);

															$hsl = DB::table("barang")->where("id_barang", "=", $id_barang[$x])->first();
															
															$stok = $hsl['stok'];
															$idb  = $hsl['id_barang'];

															$total_stok = $stok - $jumlah[$x];
															DB::table("barang")->where("id_barang", "=", $idb)->update([
																"stok" => $total_stok
															]);

														}
														echo '<script>alert("Belanjaan Berhasil Di Bayar !");</script>';
													}else{
														echo '<script>alert("Uang Kurang ! Rp.'.$hitung.'");</script>';
													}
												}
											}
											?>
											<!-- aksi ke table nota -->
											<form method="POST" action="index.php?page=jual&nota=yes#kasirnya">
												<?php foreach($hasil_penjualan as $isi){;?>
													<input type="hidden" name="id_barang[]" value="<?php echo $isi['id_barang'];?>">
													<input type="hidden" name="id_member[]" value="<?php echo $isi['id_member'];?>">
													<input type="hidden" name="jumlah[]" value="<?php echo $isi['jumlah'];?>">
													<input type="hidden" name="total1[]" value="<?php echo $isi['total'];?>">
													<input type="hidden" name="tgl_input[]" value="<?php echo $isi['tanggal_input'];?>">
													<input type="hidden" name="periode[]" value="<?php echo date('m-Y');?>">
												<?php $no++; }?>
												<tr>
													<td>Total Semua  </td>
													<td><input type="text" class="form-control" name="total" value="<?php echo $total_bayar;?>"></td>
												
													<td>Bayar  </td>
													<td><input type="text" class="form-control" name="bayar" value="<?php echo isset($bayar) ? $bayar : '';?>"></td>
													<td><button class="btn btn-success"><i class="fa fa-shopping-cart"></i> Bayar</button>
													<?php  if(isset($_GET['nota']) && !empty($_GET['nota'] == 'yes')) {?>
													 <a class="btn btn-danger" href="controllers/penjualan.php?action=reset-keranjang">
														<b>RESET</b></a></td><?php }?></td>
												</tr>
											</form>
											<!-- aksi ke table nota -->
											<tr>
												<td>Kembali</td>
												<td><input type="text" class="form-control" value="<?php echo isset($hitung) ? $hitung : '';?>"></td>
												<td></td>
												<td>
													<a href="index.php?page=print/bukti-pembayaran&nm_member=<?php echo $_SESSION['admin']['nm_member'];?>
													&bayar=<?php echo $bayar;?>&kembali=<?php echo $hitung;?>" target="_blank">
													<button class="btn btn-default">
														<i class="fa fa-print"></i> Print Untuk Bukti Pembayaran
													</button></a>
												</td>

											</tr>
										</table>
										<br/>
										<br/>
									</div>
								</div>
							</div>
						</div>
				  </div>
              </div>
          </section>
      </section>
	

<script>
// AJAX call for autocomplete 
$(document).ready(function(){
	$("#cari").change(function(){
		$.ajax({
		type: "POST",
		url: "controllers/barang.php?action=cari-barang",
		data:'keyword='+$(this).val(),
		beforeSend: function(){
            $("#hasil_cari").hide();
			$("#tunggu").html('<p style="color:green"><blink>tunggu sebentar</blink></p>');
		},
          success: function(html){
			$("#tunggu").html('');
            $("#hasil_cari").show();
            $("#hasil_cari").html(html);
		}
	});
	});
});
//To select country name
</script>