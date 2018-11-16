<?php
// echo'lkjhgfghiljkhg';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// 	error_reporting( E_ALL );
	require_once('core/init.php');
	$general->logged_out_protect();
	require_once('core/connect/database.php');
	require_once('core/classes/dropdown-class.php');
	require_once('core/classes/activities-class.php');
	require_once('core/classes/rkap-class.php');
	require_once('core/connect/koneksi1.php');
	//session_start();
	
	$_SESSION['isiDivisi'] = "";
	$_SESSION['isiUnit'] = "";
	
	$page		= 'listActivity';
	$denied		= '';
	$success	= '';
	
	$database 	= new Database();
	$dropdown	= new Dropdown($database);
	$Activity	= new Activities($database);
	$rkapdb		= new Rkap($database);
	
	$database->connect();

	$sow		= $dropdown->allSow();
	$project    = $dropdown->allProject();
	$nama       = $Activity->UserByunit($id_unit);
	$unit       = $rkapdb->unit($id_unit);
	$Allunit    = $rkapdb->Allunit();
	$unit1      = $rkapdb->unit1();
	$divisi     = $rkapdb->divisi();
  
	$all_act 	= $Activity->allAct();
	// var_dump($all_act);
	// die();
	$Alluser 	= $Activity->Alluser();
	$Alldivisi 	= $rkapdb->Alldivisi();
	$act 		= $Activity->allActivities();

	if($_POST['submit']=='ok'){
	$_SESSION['isiDivisi'] = $_POST['divisi'];
	$_SESSION['isiUnit'] = $_POST['unit_id'];
	$_SESSION['isiUser'] = $_POST['user_id'];
	$ab = $_POST['user_id'];
	$az =$_POST['unit_id'];
	$queryString = "select * from tbl_activity atv JOIN tbl_jenis_activity b on atv.id_jenis_activity = b.id_jenis_activity, tbl_project pj,tbl_main_project mpj, tbl_str_divisi div, tbl_str_unit unit, tbl_user usr where atv.id_project = pj.id_project and atv.id_user = usr.id_user and usr.id_unit = unit.id_unit and unit.divisi= div.id_divisi and pj.id_main_project=mpj.id_main_project  ";
	if(!empty($_POST['divisi'])){
		$queryString .= " and div.id_divisi = '".$_POST['divisi']."'";
	}
	if(!empty($_POST['unit_id'])){
		$queryString .= " and unit.id_unit = '".$_POST['unit_id']."'";
	}
	if(!empty($_POST['user_id'])){
		$queryString .= " and atv.id_user = '".$_POST['user_id']."'";
	}
	$query = pg_query($dbconn, $queryString); 
	$resultArr = pg_fetch_all($query);
	}else if($_SESSION['special']=='99'){
		$resultArr = $Activity->allActivities();
	}else{
		$resultArr = $Activity->ActivitiesByIdDivisi();
		}
  ?>

<script>function myFunction() {
    if (confirm("Anda yakin?")) {
     location.href ='delete_act.php';
    } else {
      return false;
    }
}
</script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
	function  load_id_unit(val){
                                $.post("loadUnit.php", { 'divisi' : val },
                                    function(data){
                                        var strbody = '';
										//console.log(data);
										strbody += "<option value=''>Choose Unit</option>";
                                        for(var i in data){
                                            var nomor = i; nomor++;
											strbody += "<option value='"+data[i].id_unit+"'>";
                                            strbody += data[i].unit+"</option>";
                                        }
                                                       // strbody += "<option value='other'>Other Sub Province</option>";
                                        $('#unit_id').html(strbody);
                                    }, "json");
                            }
							
	function  load_id_unit_select(val,unit){
                                $.post("loadUnit.php", { 'divisi' : val },
                                    function(data){
                                        var strbody = '';
										//console.log(data);
										strbody += "<option value=''>Choose Unit</option>";
                                        for(var i in data){
                                            var nomor = i; nomor++;
											if(unit == data[i].id_unit){
												strbody += "<option value='"+data[i].id_unit+"' selected>";
												strbody += data[i].unit+"</option>";
											}else{
												strbody += "<option value='"+data[i].id_unit+"'>";
												strbody += data[i].unit+"</option>";
											}
                                        }
                                                       // strbody += "<option value='other'>Other Sub Province</option>";
                                        $('#unit_id').html(strbody);
                                    }, "json");
                            }

    function  load_id_user(val){
                                $.post("loadUser.php", { 'unit_id' : val },
                                    function(data){
                                        var strbody = '';
										strbody += "<option value=''>Choose User</option>";
                                        for(var i in data){
                                            var nomor = i; nomor++;
                                             strbody += "<option value='"+data[i].id_user+"'>";
                                             strbody += data[i].user+"</option>";
                                        }
                                                       // strbody += "<option value='other'>Other Sub Province</option>";
                                        $('#user_id').html(strbody);
                                    }, "json");
                            }
							
	function  load_id_user_select(val,user){
                                $.post("loadUser.php", { 'unit_id' : val },
                                    function(data){
                                        var strbody = '';
										strbody += "<option value=''>Choose User</option>";
                                        for(var i in data){
                                            var nomor = i; nomor++;
											if(user == data[i].id_user){
												strbody += "<option value='"+data[i].id_user+"' selected>";
                                             strbody += data[i].user+"</option>";
											}else{
												strbody += "<option value='"+data[i].id_user+"'>";
                                             strbody += data[i].user+"</option>";
											}
                                             
                                        }
                                                       // strbody += "<option value='other'>Other Sub Province</option>";
                                        $('#user_id').html(strbody);
                                    }, "json");
                            }

	$(document).ready(function(){
		$('#divisi').change(	
                                    function(){
										//console.log('masuk sini');
                                        load_id_unit($(this).val());
										});
		$('#unit_id').change(	
                                    function(){
										//console.log('masuk sini');
                                        load_id_user($(this).val());
										});
								
	});
</script>

<!DOCTYPE html>
<html>
	<?php include 'head.php'; //----------this is for <head>?>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
		<?php include 'header.php'; //----------this is for <header>?>
		
		<div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
			<?php include 'sidemenu.php';?>
            
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                
                <section class="content invoice">
					<!--<form role="form" action="" method="post" enctype="multipart/form-data">-->
						<div class="row">
							<div class="col-xs-12">
								<h2 class="page-header">
									<i class="fa fa-globe"></i> List Activities
									<small class="pull-right">Date: <?php echo date("j/n/Y")?></small>
								</h2>                             
									
							</div><!-- /.col -->
						</div>
							
						<div class="row">

							<div class="col-md-12">
							  
								<div class="box-body table-responsive">
										<form action="listActivities.php" method="POST">
														<select name="divisi" id="divisi" style="width: 150px;" <?php if($_SESSION['level']!='1'){echo "hidden";}?>>

													<?php
													if($_SESSION['special']=='99'){
														echo "<option value=''>Choose Divisi</option>";
														foreach($Alldivisi as $d){
															if($d['id_divisi'] == $_SESSION['isiDivisi']){
																echo "<option value='$d[id_divisi]' selected>$d[nama_divisi]</option>";
															}else{
																echo "<option value='$d[id_divisi]'>$d[nama_divisi]</option>";
															}
														}
													}else{
														echo "<option value=''>Choose Divisi</option>";
														foreach($divisi as $d){
															if($d['id_divisi'] == $_SESSION['isiDivisi']){
																echo "<option value='$d[id_divisi]' selected>$d[nama_divisi]</option>";
															}else{
																echo "<option value='$d[id_divisi]'>$d[nama_divisi]</option>";
															}
														}

													}
													?>
											</select>
											
											<select name="unit_id" id="unit_id" style="width: 150px;" <?php if($_SESSION['level']!='1'){echo "hidden";}?>>
												<option value="">Choose Unit</option>
												<?php
												if(!empty($_SESSION['isiDivisi'])){
													?>
													<script type="text/javascript">
														load_id_unit_select(<?php echo $_SESSION['isiDivisi'] ?>,<?php echo $_SESSION['isiUnit'] ?>);
													</script>
													<?php
												}
												?>
											</select><!-- <div id="ext_unit"></div> -->
											&nbsp;&nbsp;
											<select name="user_id" id="user_id" style="width: 150px;" <?php if($_SESSION['level']!='1'){echo "hidden";}?>>
												<option value="">Choose User</option>
												<?php
												if(!empty($_SESSION['isiUnit'])){
													?>
													<script type="text/javascript">
														load_id_user_select(<?php echo $_SESSION['isiUnit'] ?>,<?php echo $_SESSION['isiUser'] ?>);
													</script>
													<?php
												}
												?>
											</select>
											&nbsp;
											<input type="submit" name="submit" value="ok"  <?php if($_SESSION['level']!='1'){echo "hidden";}?>>
									
									
									</form>
								<div>
									<br>
								</div>
									<table id="example1" class="table table-bordered table-striped" >
											<thead>
													<tr>
														 
														<th>Tanggal</th>
														<th>Project</th>
														<th>Sub Project</th>
														<th>Aktivitas</th>
														<th>Nama User</th>
														<th>Cost</th>
														<th>File Lampiran</th>
														<th>Judul</th>
														<th>Aksi</th>
													</tr>
												
											</thead>
											<tbody>
										   		<?php
													foreach($resultArr as $a){
													echo"<tr>
																<td>$a[tgl_activity]</td>
																<td>$a[main_project_name]</td>
																<td>";
																if($a['main_project_name']==$a['project_name']){
																 echo "";
																}else{
																	echo "$a[project_name]";
																}
																echo "
																</td>
																<td>$a[nama_jenis_activity]</td>
															    <td>$a[nama]</td>
																<td style='text-align: right;'>Rp. ".number_format($a[cost],0,'','.').".-</td>
																<td>";
																if($a['file_name']!=NULL){
																	echo "
																<a href='$a[file_name]' target='_blank'>Lampiran</a>
																";
																}
																echo"
																</td>
																<td>";
																echo"<a href='view_act.php?id_activity=$a[id_activity]'>$a[judul]</a></td>
																<td>";
																if($_SESSION['id_user']==$a['id_user']){
																	echo "<a href='edit_act.php?id_activity=$a[id_activity]'>Edit</a> <br> 
																	<form method='POST' action='delete_act.php'>
																	<input type='hidden' name='id_activity' value='$a[id_activity]' />
																	<a href='delete_act.php?id_activity=$a[id_activity]' onclick='return myFunction()'>Hapus</a>
																	</form></td>
																
													                                             </tr>";}
																               }?>
											</tbody>
										 
								</table>

											

								</div><!-- /.box-body -->
							</div>
						</div>
				</section>
			</aside>
							</div>
 			  <?php include 'script.php'; //----------this is for script?>
		
    </body>
	
</html>