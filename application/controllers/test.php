
<!-- SEARCH -->
<?php
//include '../Modules.php';
if(!CheckPrivilege("WEB_ONLINE_ALERT",$db)) exit;
session_start();
checkLogin();
error_reporting(1);
$cboProvider=$_POST['cboprovider'];
$sCodeOfPayor = "'".implode("','" ,explode("~",$_POST['cboPayor']))."'" ;
$cboPayor=$_POST['cboPayor'];
$cboUser=$_POST['cboUser'];
$cboCoverage=$_POST['cboCoverage'];
$cboRefreshTime=$_POST['cboRefreshTime'];
$cboDisplayRows=$_POST['cboDisplayRows'];

//26022019 cek log audit
$cekLog = GetFieldValue("SELECT COUNT(*) FROM USER_LOG WHERE PROGRAM_ID='WEB_ONLINE_ALERT' AND USER_CODE = '".trim($_SESSION['LOGIN']['USER_CODE'])."'",$db);

if($cekLog == 0){
	$sSql = "INSERT INTO USER_LOG (USER_CODE,PROGRAM_ID,LOGGED_IN)
										 VALUES ('".trim($_SESSION['LOGIN']['USER_CODE'])."','WEB_ONLINE_ALERT',SYSDATE)";
	$OK=$db->Execute($sSql);
}else{

	$cekLogSame = GetFieldValue("SELECT COUNT(*) FROM USER_LOG
										WHERE PROGRAM_ID='WEB_ONLINE_ALERT' AND USER_CODE = '".trim($_SESSION['LOGIN']['USER_CODE'])."'
										AND TO_CHAR(LOGGED_IN,'MM/DD/YYYY') = TO_CHAR(SYSDATE,'MM/DD/YYYY')",$db);

	if($cekLogSame == 0){
		$sSql = "INSERT INTO USER_LOG (USER_CODE,PROGRAM_ID,LOGGED_IN)
										 VALUES ('".trim($_SESSION['LOGIN']['USER_CODE'])."','WEB_ONLINE_ALERT',SYSDATE)";
		$OK=$db->Execute($cekLogSame);
	}else{
		$sSql="UPDATE USER_LOG SET LOGGED_IN = SYSDATE WHERE PROGRAM_ID='WEB_ONLINE_ALERT' AND USER_CODE = '".trim($_SESSION['LOGIN']['USER_CODE'])."'";

		$OK=$db->Execute($sSql);
	}


}


?>
<style type="text/css" >
	.modal-body {
    max-height: 900px !important;
    overflow-y: auto;
}
</style>
<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title">Online Alert </h3>
		</div>
	<div class="panel-body">

		<form method="POST">
		<div class="row">
			<div class="col-md-1">
				<div class="form-group">
					<button type="button" style="padding: 2px 4px 2px 4px;" class="btn btn-danger btn-sm btn-labeled waves-effect waves-light" data-toggle="modal" data-target=".legend-modal"><i class="glyphicon glyphicon-th-large"></i></button>
				</div>
				<div class="form-group">
					<div id="timer"><h4><small>Auto Refresh</small></h4></div>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<!--<label class="control-label" for="alerttype">ALERT TYPE</label>-->
					<select class="form-control input-xs" name="cboCoverage" id="cboCoverage">
						<option value="INPATIENT" <?php if($cboCoverage=='INPATIENT'){echo "selected"; }?>>INPATIENT CLAIMS</option>
						<option value="OUTPATIENT" <?php if($cboCoverage=='OUTPATIENT'){echo "selected";}?>>OUTPATIENT CLAIMS</option>
					</select>
				</div>

				<div class="form-group">
					<!--<label class="control-label" for="alerttype">REFRESH TIME</label>-->
					<select class="form-control input-xs" name="cboRefreshTime" id="cboRefreshTime">
						<option value="50000000000000000" <?php if($cboRefreshTime=='50000000000000000'){echo 'selected';} ?>>Choose For Refresh Automatic</option>

						<option value="60000" <?php if($cboRefreshTime=='60'){echo 'selected';} ?>>Every 60 Seconds</option>
						<option value="120000" <?php if($cboRefreshTime=='120'){echo 'selected';} ?>>Every 2 Minutes</option>
						<option value="300000" <?php if($cboRefreshTime=='300'){echo 'selected';} ?>>Every 5 Minutes</option>
						<option value="600000" <?php if($cboRefreshTime=='600'){echo 'selected';}  ?>>Every 10 Minutes</option>
						<option value="1200000" <?php if($cboRefreshTime=='1200'){echo 'selected';} ?>>Every 20 Minutes</option>
						<option value="1800000" <?php if($cboRefreshTime=='1800'){echo 'selected';} ?>>Every 30 Minutes</option>
						<option value="3600000" <?php if($cboRefreshTime=='3600'){echo 'selected';} ?>>Every 1 Hour</option>
					</select>
				</div>

			</div>


			<div class="col-md-2">
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<!--<label class="control-label" for="provider">DISPLAY</label>-->
							<select class="form-control input-xs" name="cboDisplayRows" id="cboDisplayRows">
								<option value="20" selected>Display 20 Rows</option>
								<option value="50" <?php if($cboDisplayRows=='50'){echo 'selected';}?>>Display 50 Rows</option>
								<option value="80" <?php if($cboDisplayRows=='80'){echo 'selected';}?>>Display 80 Rows</option>
								<option value="100" <?php if($cboDisplayRows=='100'){ echo 'selected';}?>>Display 100 Rows</option>
								<option value="200" <?php if($cboDisplayRows=='200'){ echo 'selected';}?>>Display 200 Rows</option>
								<option value="300" <?php if($cboDisplayRows=='300'){ echo 'selected';}?>>Display 300 Rows</option>
								<option value="400" <?php if($cboDisplayRows=='400'){ echo 'selected';}?>>Display 400 Rows</option>
								<option value="500" <?php if($cboDisplayRows=='500'){ echo 'selected';}?>>Display 500 Rows</option>
								<option value="4000" <?php if($cboDisplayRows=='4000') {echo 'selected';} ?>>Display 4000 Rows</option>
							</select>
						</div>
						<div class="btn-group">
							<button  type="button" id="setTime" class="btn btn-success btn-sm btn-labeled btn-block" ><span class="btn-label text-left"><i class="fa fa-clock-o fa-lg"></i></span>S E T</button>
						 </div>

					</div>
				</div>
			</div>


			<div class="col-md-6">
				<div class="row">
					<div class="form-group">
						<div  class="form-inline">
							<!-- LIST PAYOR-->
							<button   id="mdlPayor" class="btn btn-info btn-sm btn-labeled" align="right" ><span class="btn-label"><i class="fa fa-heartbeat fa-lg" aria-hidden="true"></i></span> PAYOR</button>
						    <!-- END LIST PAYOR -->

						    <!-- LIST USER-->
							<a href="" class="btn btn-info btn-sm btn-labeled" align="right" data-toggle="modal" data-target="#ModalUserOA"><span class="btn-label"><i class="fa fa-user-md fa-lg"></i></span> USER</a>
						    <!-- END LIST USER -->

						    <!-- LIST PROVIDER-->
							<a href="" id="mdlProvider" style="padding-right: 10px;" class="btn btn-info btn-sm btn-labeled" align="right" ><span class="btn-label"><i class="fa fa-hospital-o fa-lg"></i></span> PROVIDER</a>
							<!-- END LIST PROVIDER-->

							<button type="button" id="searchAlert" style="padding-right: 30px;" class="btn btn-danger btn-sm btn-labeled waves-effect waves-light pull-right"><span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>S E A R C H</button>

						</div>
			    	</div>



			    	<div class="form-inline" >
			    		<div class="panel panel-danger" style="width: 330px;">
  							<div class="panel-heading">
  							<!-- LIST USER ASSIGN-->
							<?php
							/*
								Modified By Syarif 09/04/2018
								Penutupan akses assign tiket untuk user call center selain supervisor
							*/
							if ( (($_SESSION['LOGIN']['ACCESS_LEVEL']=='SUPERVISOR')||($_SESSION['LOGIN']['ACCESS_LEVEL']=='ADMIN')) || ($_SESSION['LOGIN']['DEPARTMENT']=='IT') )
								{
							?>
  								<a id="mdlAssign" href="" class="btn btn-warning btn-sm btn-labeled" align="right" ><span class="btn-label"><i class="fa fa-list-alt fa-lg"></i></span> LIST USER ASSIGN</a>

  								<button  type="button" id="assignSend" class="btn btn-success btn-sm btn-labeled"><span class="btn-label"><i class="glyphicon glyphicon-send"></i></span> ASSIGN</button>
  							<?php 	}	?>
							<!-- END LIST USER ASSIGN-->
  							</div>
						</div>
			    	</div>
				</div>
			</div>
		</div> <!-- end row awal -->

		</form>

	</div>
</div>
<!-- END SEARCH -->

<!-- HTML to write -->



<!-- Small modal -->

<div class="modal fade legend-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">LEGEND</h4>
        </div>
        <div class="modal-body">

			<ul class="list-group no-bullets">
				<li class="list-group-item"><div class="legend0"></div> <a>MINOR</a></li>
				<li class="list-group-item"><div class="legend1"></div> <a>INTERMEDIATE</a></li>
				<li class="list-group-item"><div class="legend2"></div> <a>MAJOR</a></li>
				<li class="list-group-item"><div class="legend3"></div> <a>COMPLEX</a></li>
				<li class="list-group-item"><div class="legend4"></div> <a>NEW CLAIMS</a></li>
			</ul>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
<!-- Small modal -->


	<?php
	print_r($cboPayor=$_POST['cboPayor']);

	foreach($cboPayor as $p => $payorvalue) {
	    $lp .=trim($payorvalue)."','";

	}
	$listPayor=substr($lp ,0, -3);
	echo $listPayor ;
	echo "<br>";
	print_r($cboUser=$_POST['cboUser']);

	foreach($cboUser as $p => $uservalue) {
	    $lu .=trim($uservalue)."','";

	}
	$listUser=substr($lu ,0, -3);
	echo $listUser ;

	?>

<div id ="modals-Chat"></div>

<div id="OnlineAlert"></div>

<!-- SCROLL BAWAH -->
<div class="panel panel-danger" style="padding:0px;position: fixed;bottom: 0;width: 100%;cursor: pointer;">
	<div class="panel-body" style="padding:0px">
		<div class="table-responsive scrollHandler" style="padding:0px;margin-bottom: 10px">
			<div style="width:200% ;height: 2px;">
			</div>
		</div>
	</div>
</div>
<!-- AKHIR DARI SCROLL BAWAH -->

<!-- MODAL PAYOR -->
<div class="modal fade" id="ModalPayorOA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div id="mdlPayorContent"></div>
				<textarea cols="80" rows="4" name="cboPayorOA" id="cboPayorOA" style="border:1px #333 solid;color:#333;font:10px Verdana; font-weight:bold; width:100%;" readonly="readonly"></textarea>
				<button type="button" class="btn btn-info"  data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> Ok</button>
				<button type="button" class="btn btn-danger"  name="btnClearPayorOA" id="btnClearPayorOA"><span class="glyphicon glyphicon-remove"></span> Clear</button>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	$("#mdlPayor").on("click",function(e){
		e.preventDefault();
		var htmlPayor = $("#mdlPayorContent").html();
		if (htmlPayor == '') {
			$("#myPleaseWait").modal("show");

			$.ajax({
			    url:"online_alert/tb_list_payor.php",
			    method:"GET",
			    error: function(){
			    	$("#myPleaseWait").modal("hide");
				    $.prompt('Failed to load Payor List. Please check your network connection.	',{
						position: { width: 350 }
					});
			    },
			    success: function(data){
			        $("#myPleaseWait").modal("hide");
					$("#ModalPayorOA").modal("show");
					$("#mdlPayorContent").html(data);
			    },
			    //timeout: 10000000
			});


		}else{
			$("#ModalPayorOA").modal("show");
		}
  	})
});
</script>
<!-- END OF MODAL PAYOR -->

<!-- MODAL PROVIDER -->
<div class="modal fade" id="ModalProviderOA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div class="panel panel-danger">
					<div class="panel-heading">
			      <h3 class="panel-title">Provider Query</h3>
			    </div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="col-md-6">
									<label class="form-check-label">
		                 <input type="radio" class="form-check-input" value="1" name="gender" id="reqId"> Indonesia
		              </label>
								</div>
								<div class="col-md-6">
									<label class="form-check-label">
		                 <input type="radio" class="form-check-input" value="2" name="gender" id="reqNonId"> Non Indonesia
		              </label>
								</div>
							</div>
							<!-- add by eko for select state from provider on 29-09-2020 -->
							<div id="stateProvider">
								<div class="row">
									<div class="col-lg-12">
										<div class="col-md-6">
											Filter by state<br/>
											<select class="form-control input-xs" name="cmbStateProvider" id="cmbStateProvider" value="">
												<option value="">-- SELECT STATE --</option>
												<?php
													$sQueryState = "SELECT distinct(trim(STATE)) as STATE FROM PROVIDER WHERE COUNTRY = 'INDONESIA' order by STATE ASC";

													$_Rs = $db->execute($sQueryState);

													if($_Rs) {
														while(!$_Rs->EOF)
														{
																echo "<option value ='".$_Rs->fields["STATE"]."'>".trim($_Rs->fields["STATE"])."</option>";
																$_Rs->MoveNext();
														}
													} else {
														echo "<option value =''>NOT FOUND !</option>";
													}
												?>
											</select>
										</div>

									</div>
								</div>
							</div>
							<!-- end here -->
						</div>
					</div>
				</div>
				<div id="mdlProviderContent" style="min-height:"></div>
				<textarea cols="80" rows="4" name="cboProviderOA" id="cboProviderOA" style="border:1px #333 solid;color:#333;font:10px Verdana; font-weight:bold; width:100%;" readonly="readonly"></textarea>
				<div>
					<button type="button" class="btn btn-info"  data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> Ok</button>

			 		<button type="button" class="btn btn-danger" name="btnClearOA" id="btnClearOA"><span class="glyphicon glyphicon-remove"></span> Clear</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var globalValue = "";

	$("#cmbStateProvider").on('change', function() {
		var _valCmb = $(this).val();
		var reqId = $("#reqId:checked").val();
		var reqNonId = "";
		var sProv = document.getElementById("cboProviderOA");
		if(reqId) {
			$("#myPleaseWait").modal("show");
			$.ajax({
					url:"online_alert/tb_list_provider.php",
					data:"reqId="+reqId+"&reqNonId="+reqNonId+"&state="+_valCmb,
					method:"GET",
					error: function(){
						$("#myPleaseWait").modal("hide");
						$.prompt('Failed to load Provider List. Please check your network connection.	',{
							position: { width: 350 }
						});
					},
					success: function(data){
						$("#myPleaseWait").modal("hide");
						$("#ModalProviderOA").modal("show");
						$("#mdlProviderContent").html(data);
						sProv.value= "";
						globalValue = "1";
					},
					//timeout: 10000000
			});
		} else {
			console.log(globalValue);
		}

	});

	$("#reqId").on('click', function() {
		var reqNonId = "";
		var reqId = $("#reqId:checked").val();

		var sProv = document.getElementById("cboProviderOA");

		if(globalValue == "2" || globalValue == "") {

			$("#stateProvider").show();

			$("#myPleaseWait").modal("show");
			$.ajax({
					url:"online_alert/tb_list_provider.php",
					data:"reqId="+reqId+"&reqNonId="+reqNonId,
					method:"GET",
					error: function(){
						$("#myPleaseWait").modal("hide");
						$.prompt('Failed to load Provider List. Please check your network connection.	',{
							position: { width: 350 }
						});
					},
					success: function(data){
						$("#myPleaseWait").modal("hide");
						$("#ModalProviderOA").modal("show");
						$("#mdlProviderContent").html(data);
						sProv.value= "";
						globalValue = "1";
					},
					//timeout: 10000000
			});
		}

	});

	$("#reqNonId").on('click', function() {
		var reqNonId = $("#reqNonId:checked").val();
		var reqId = "";
		var sProv = document.getElementById("cboProviderOA");
		if(globalValue == "1" || globalValue == "") {
			$("#cmbStateProvider").val("");
			$("#stateProvider").hide();
			$("#myPleaseWait").modal("show");
			$.ajax({
					url:"online_alert/tb_list_provider.php",
					data:"reqId="+reqId+"&reqNonId="+reqNonId,
					method:"GET",
					error: function(){
						$("#myPleaseWait").modal("hide");
						$.prompt('Failed to load Provider List. Please check your network connection.	',{
							position: { width: 350 }
						});
					},
					success: function(data){
						$("#myPleaseWait").modal("hide");
						$("#ModalProviderOA").modal("show");
						$("#mdlProviderContent").html(data);
						sProv.value = "";
						globalValue = "2";
					},
					//timeout: 10000000
			});
		}

	});

	$("#mdlProvider").on("click",function(e){
		e.preventDefault();
		$("#reqId").prop('checked', true);
		var htmlProvider = $("#mdlProviderContent").html();
		var reqId = $("#reqId:checked").val();
		var reqNonId = $("#reqNonId:checked").val();
		if (typeof(reqId)  === "undefined") {
        reqId = "";
    }

    if(typeof(reqNonId) === "undefined") {
      reqNonId = "";
    }


		if (htmlProvider == '') {
			$("#myPleaseWait").modal("show");
			$.ajax({
			    url:"online_alert/tb_list_provider.php",
					data:"reqId="+reqId+"&reqNonId="+reqNonId,
			    method:"GET",
			    error: function(){
			    	$("#myPleaseWait").modal("hide");
				    $.prompt('Failed to load Provider List. Please check your network connection.	',{
							position: { width: 350 }
						});
			    },
			    success: function(data){
			      $("#myPleaseWait").modal("hide");
						$("#ModalProviderOA").modal("show");
						$("#mdlProviderContent").html(data);
			    },
			    //timeout: 10000000
			});
		}else{
			$("#ModalProviderOA").modal("show");
		}


  	})
});
</script>
<!-- END OF MODAL PROVIDER -->

<!-- MODAL ASSIGN -->
<div class="modal fade" id="ModalAssignOA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div id="mdlAssignContent"></div>
				<textarea cols="80" rows="4" name="cboAssignOA" id="cboAssignOA" style="border:1px #333 solid;color:#333;font:10px Verdana; font-weight:bold; width:100%;" readonly="readonly"></textarea>
				<button type="button" class="btn btn-info"  data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> Ok</button>

				<button type="button" class="btn btn-danger"  name="btnClearAssignOA" id="btnClearAssignOA"><span class="glyphicon glyphicon-remove"></span> Clear</button>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	$("#mdlAssign").on("click",function(e){
		e.preventDefault();
		var htmlAssign = $("#mdlAssignContent").html();
		if (htmlAssign == '') {
			$("#myPleaseWait").modal("show");

			$.ajax({
			    url: "online_alert/tb_list_assign.php",
			    method:"GET",
			    error: function(){
			    	$("#myPleaseWait").modal("hide");
				    $.prompt('Failed to load User List. Please check your network connection.	',{
						position: { width: 350 }
					});
			    },
			    success: function(data){
			        $("#myPleaseWait").modal("hide");
					$("#ModalAssignOA").modal("show");
					$("#mdlAssignContent").html(data);
			    },
			    //timeout: 10000000
			});
		}else{
			$("#ModalAssignOA").modal("show");
		}
  	})
});
</script>
<!-- END OF MODAL ASSIGN -->


<?php
include'claims_tracking/claims_detail.php';
//include'list_provider.php';
//include'list_payor.php';
include'list_user.php';
//include'list_assign.php';
include'chat.php';
include'remarks_tracking.php';
?>
<script type="text/javascript">
var payor_code = '',
provider_code = '',
count_finish = 0;
document.addEventListener('DOMContentLoaded', function() {

	$('#arrowHold').click(function(){
    		 $("#tbHold").fadeToggle();
    	});

	$('[data-toggle="popover"]').popover({html:true});


	//$('#OnlineAlert').load('online_alert/pg_onalert.php');
	// $('#listincomplete').load('online_alert/list_incomplete.php');
	// $('#listelective').load('online_alert/list_elective.php');
	// $('#claimstransaction').load('online_alert/claims_transaction.php');
	// $('#listdecline').load('online_alert/list_decline.php');
	// $('#dischargefinal').load('online_alert/discharge_final.php');
	// $('#claimshold').load('online_alert/claims_hold.php');
	//searchAjaxOnlineAlert();
	pg_onAlert();
	var payor_code_collection,
	cboprovider,
	cbopayor,
	cbouser,
	cboCoverage,
	cboRefreshTime,
	cboDisplayRows,
	cboDate,
	cboPayor,
	cboUser;


	function check_finish(){
		if (count_finish == 7) {
			$('#myPleaseWait').modal('hide');
			count_finish = 0;
		}
	}

	function pg_onAlert(){
		payor_code_collection = matches.join("~");

		cboprovider = $('#cboProviderOA').val();
	    cbopayor = $('#cboPayorOA').val();
	    cbouser = $('#cboUserOA').val();
	    cboCoverage=$('#cboCoverage').val();
		cboRefreshTime=$('#cboRefreshTime').val();
		cboDisplayRows=$('#cboDisplayRows').val();
		cboDate=$('#cboDate').val();
		$.ajax({
				type: "POST",
				url: "online_alert/pg_onalert.php",
				data: "cboprovider="+cboprovider+"&cboPayor="+payor_code_collection+"&cboUser="+cbouser+"&cboCoverage="+cboCoverage+"&cboRefreshTime="+cboRefreshTime+"&cboDisplayRows="+cboDisplayRows+"&cboDate="+cboDate,
				cache: false,
				beforeSend: function(html){
					check_connection();
					$('#myPleaseWait').modal('show');
				},
				success: function(html){

					$('#OnlineAlert').html(html);
					$('#myPleaseWait').modal('hide');
				},
				error: function () {
					$('#myPleaseWait').modal('hide');
				},
    			//timeout: 10000
			});
	}



	function searchAjaxOnlineAlert(){
		// tambah fitur keep filter
		payor_code_collection = matches.join("~");

		cboprovider = $('#cboProviderOA').val();
	    cbopayor = $('#cboPayorOA').val();
	    cbouser = $('#cboUserOA').val();
	    cboCoverage=$('#cboCoverage').val();
		cboRefreshTime=$('#cboRefreshTime').val();
		cboDisplayRows=$('#cboDisplayRows').val();
		cboDate=$('#cboDate').val();



	    //var cboProvider = cboprovider.join("','");
	    cboPayor = cbopayor;
	    cboUser = cbouser;
	    //claims_approval();

	}
	$('#searchAlert').click(function(){
	    //searchAjaxOnlineAlert();
	   pg_onAlert();

	})

	function renderTable(){
		<?php

		if (isset($_GET['mode']) && $_GET['mode'] == 'dev') {

		}
		?>
		//location.reload();
		pg_onAlert();
		//searchAjaxOnlineAlert();

	}
	var time_reload = 50000000000000000;
	var intervalUpdate = setInterval(function(){
		renderTable()
	}, time_reload);


	$("#setTime").on("click",function(){
			clearInterval(intervalUpdate);
			time_reload = parseInt($("#cboRefreshTime").val());
			intervalUpdate = setInterval(function(){
				renderTable()
			}, time_reload);

			// set status html
			$("#timer").html($('#cboRefreshTime').find(":selected").text());
	});

	// handle multiple scroll
	var scrollHandler = $(".scrollHandler");

	scrollHandler.scroll(function() {
	    scrollHandler.scrollLeft($(this).scrollLeft());
	});

	// check connection nefore processing request
	function check_connection(){
		$.getJSON({
			url: "../check_connection.php"
		}).done(function (result, status, xhr) {
			if (result.success == false) {
				window.location.href = "index.php";
			}
		}).fail(function (xhr, status, error) {
			//alert("Please check your connection " );
		});
	}





});
/*
	TABLLE EXPORT
*/
function exportClaims(table){

	var payor_code_collection = matches.join("~");

	var cboprovider = $('#cboProviderOA').val();
    var cbopayor = $('#cboPayorOA').val();
    var cbouser = $('#cboUserOA').val();
    var cboCoverage=$('#cboCoverage').val();
	var cboRefreshTime=$('#cboRefreshTime').val();
	var cboDisplayRows=$('#cboDisplayRows').val();
	var cboDate=$('#cboDate').val();



	var url = "online_alert/export/" + table + "_xls.php?cboprovider="+cboprovider+"&cboPayor="+payor_code_collection+"&cboUser="+cbouser+"&cboCoverage="+cboCoverage+"&cboRefreshTime="+cboRefreshTime+"&cboDisplayRows="+cboDisplayRows+"&cboDate="+cboDate;

	window.open(url, '_blank');

}
/*
	END OF TABLE EXPORT
*/
</script>
<?php $db->close(); ?>
