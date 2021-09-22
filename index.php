<?php
	include("include/dbConnection.php");
	include("include/function.php"); 
	
	if(isset($_SESSION['gbsadmin']))
	{
		header("location:dashboard/");
	}

	if(isset($_POST['save']))
	{
		$mobileNumber=$_POST['mobileNumber'];
		$admin=mysqli_query($connection,"select * from `admin` where `mobileNumber`='$mobileNumber' AND `delete` = '0'");
		$count=mysqli_num_rows($admin);
		$row=mysqli_fetch_array($admin);
		if($count>0)
		{
			$_SESSION['mobileNo']=$mobileNumber;
			$success=1;
		}
		else
		{
			$error="Invalid Mobile Number";
		} 
	}
	if(isset($_POST['login']))
	{
		$mobileNumber=$_POST['mobileNumber'];
		$password=md5($_POST['password']);
		$admin=mysqli_query($connection,"select * from `admin` where `mobileNumber`='{$mobileNumber}' AND `password`='$password'");
		$count=mysqli_num_rows($admin);
		$row=mysqli_fetch_array($admin);
		if($count>0)
		{
			$_SESSION['gbsadmin']=$row['adminId'];
			$date=date('Y-m-d h:i:s');
			
			// function for get ip address
			
			function get_client_ip() {
				$ipaddress = '';
				if (isset($_SERVER['HTTP_CLIENT_IP']))
					$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
				else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
					$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
				else if(isset($_SERVER['HTTP_X_FORWARDED']))
					$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
				else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
					$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
				else if(isset($_SERVER['HTTP_FORWARDED']))
					$ipaddress = $_SERVER['HTTP_FORWARDED'];
				else if(isset($_SERVER['REMOTE_ADDR']))
					$ipaddress = $_SERVER['REMOTE_ADDR'];
				else
					$ipaddress = 'UNKNOWN';
				return $ipaddress;
			}
			
			$address=get_client_ip();
			
			//Save login history
			
			$loginhistory=mysqli_query($connection,"INSERT INTO `loginhistory` (`adminId`,`ip`,`time`,`actionName`) VALUES ('{$row['adminId']}','$address','$date','login')") or die(mysqli_error($connection));
			
			header("Location:dashboard/");
		}
		else
		{
			$errors="Invalid Password";
		}
	}
	if(isset($_SESSION['mobileNo']))
	{
		$admin=mysqli_query($connection,"select * from `admin` where `mobileNumber`='{$_SESSION['mobileNo']}'");
		$count=mysqli_num_rows($admin);
		$row=mysqli_fetch_array($admin);
	}
?>
<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>GB Software-Login</title>
		<meta name="description" content="GB Softwares">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<meta property="og:title" content="GB Software" />
		<meta property="og:type" content="Software" />
		<meta property="og:url" content="https://hub.gbsoftwares.com/" />
		<meta property="og:image" content="https://hub.gbsoftwares.com/assets/app/media/img/logos/logo-1.png" />
		<!--begin::Web font -->
		<script src="assets/vendors/webfont.js" type="text/javascript"></script>
		<script>
			WebFont.load({
				google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
        </script>

		<!--end::Web font -->

		<!--begin::Global Theme Styles -->
		<link href="assets/vendors/vendors/fontawesome5/css/all.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->
		<link rel="icon" href="images/favicon.ico">
        <style>
		
		.error{
			color:red;
			font-weight:500;
			background-color:#b3c8cc;
			text-align:center;
			margin-top:5px;
			border-radius:50px;
			width:50%;
			margin-left:25%;
		}
		.errors{
			margin-left:105px;
			margin-top:10px;
			border-radius:100px;
			text-align:center;
		}
		.form-control{
			width:50%;
		}
		::placeholder {
		  text-align:center;
		}
		@media only screen and (max-width: 600px) {
			.error{
				
			}
			.errors{
						
			}
			.forgot{
				margin-left:100px;
				margin-top:20px;
			}
		}
		</style>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-3" id="m_login" style="background-image: url(assets/app/media/img//bg/bg-1.jpg);">
				<div class="m-grid__item m-grid__item--fluid m-login__wrapper">
					<div class="m-login__container">
						<div class="m-login__logo">
							<a href="#">
								<img src="assets/app/media/img/logos/logo-1.png" alt="" style="width:40%;">
							</a>
						</div>
						<div class="m-login__signin" id="signin" style="display:<?php if(isset($errors)) { echo "none";} ?>">
							<div class="m-login__head">
								<h1 class="m-login__title" style="color:#FFF">GB Software</h1>
							</div>
                            <?php
								if(isset($_GET['success']))
								{
									echo "<div class='alert alert-success' style='text-align:center;'>Succesfully Reset Your Password</div>";
								}
							?>
							<form class="m-login__form m-form" method="post" id="login">
							
								<div class="form-group m-form__group">
									
									<input class="form-control m-input" type="text" aria-label="Mobile Number" placeholder="Enter Mobile Number" id="mobileNumber" name="mobileNumber" style="text-align:center;background-color:#25182F;margin-left:25%;" autofocus autocomplete="off" value="<?php if(isset($mobileNumber)) { echo $mobileNumber;} ?>">
										
									<p class="error mobile" style="font-weight:500;" aria-hidden="true" ><?php if(isset($error)) { echo $error; } ?></p>
									<div id="mobileNumberError"></div>
								</div>
							
								<div class="m-login__form-action">
									<button type="submit" onClick="getmobile()" style="background-color:#43475A;border:1px solid #43475A;" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m_login_signin_submit" name="save">NEXT</button>
								</div>
								
								
							</form>
					
							<div class="m-login__account" style="padding-left:0px;padding-right:0px;">
								<span class="m-login__account-msg" style="color:#FFF">
									<?php echo date('Y')." © Design & Developed By";  ?>
								</span>
								<a href="https://goldenbrainsithub.com" title="GoldenBrainsITHub" target="_blank">
									<sup><img src="images/GoldenBrainsITHUBFullLogo.png" alt="" width="90px;"></sup>
								</a>
							</div>
                            
						</div>
                        <div class="m-login__signin" id="signuser" style="display:<?php if(isset($errors)) { echo "block";} else { echo "none";} ?>;">
							<div class="m-login__head">
								<h1 class="m-login__title" style="color:#FFF;">GB Software</h1>
							</div>
                            
							<form class="m-login__form m-form" method="post" id="loginuser">
							
								<div class="form-group m-form__group">
									<div class="row">
									<div class="col-lg-1" style="text-align:center;">
									<?php
										if($row['profilePicture']!="")
										{
									?>
											<img src="images/profile/<?php echo $row['profilePicture']; ?>" style="height:110px;width:110px;border-radius:50%;"><br>
                                    <?php
										}
										else
										{
									?>
											<img src="images/user.png" alt="" style="height:110px;width:110px;border-radius:50%;">
									<?php
										}
									?>
									</div>
									<div class="col-lg-11" style="text-align:center;">
										<h3 style="color:#FFF;">
											<?php
												
												// display only 15 latters of first name and last name if length of both is greater then 15
												
												if(strlen($row['firstName'].' '.$row['lastName'])>15)
												{
													if(strlen($row['firstName'])>5)
													{
														$fname=substr($row['firstName'], 0, 7).'..'; // get first 7 latters of first name
													}
													else
													{
														$fname=$row['firstName'];
													}
													if(strlen($row['lastName'])>5)
													{
														$lname=substr($row['lastName'], 0, 7).'..'; // get first 7 latters of last name
													}
													else
													{
														$lname=$row['lastName'];
													}
													$name=ucwords($fname.' '.$lname);
												}
												else
												{
													$name = ucwords($row['firstName'].' '.$row['lastName']);
												}
												
												echo $name;
											?>
										</h3>
										
										<div class="m-input-icon m-input-icon--right" >
											<input type="hidden" name="mobileNumber" value="<?php echo $row['mobileNumber']; ?>">
											<input class="form-control m-input" aria-label="Password" type="password" id="pass" placeholder="Enter Password" name="password"  style="background-color:#25182F;margin-left:25%;text-align:center;">
											<span class="m-input-icon__icon m-input-icon__icon--right" style="border-radius:50px;border:2px solid #25182F;margin-right:25%;" ><span><i class="fa fa-eye" style="color:#7668a4;cursor:pointer;" title="Show Password" onClick="show_password();"></i></span></span>
										</div>
										<p class="error password" aria-hidden="true" ><?php if(isset($errors)) { echo $errors;} ?></p>
										<div id="passwordError"></div>
									</div>
									
								</div>
								<div class="row">
									<div class="col-lg-1"></div>
									<div class="m-login__form-action col-lg-11" style="text-align:center;">
										<button type="submit" id="m_login_signin_submit" onClick="getpassword();" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn" name="login" style="background-color:#43475A;border:1px solid #43475A;">LOGIN</button>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-1"></div>
									<div class="m-login__head col-lg-11" style="text-align:center;">
										<a href="forgotpassword/" style="text-decoration:none;"><h5 class="m-login__title" style="color:#FFF;font-size:17px;">Forgot Password ?</h5></a>
									</div>
								</div>
							</form>
							
						</div>
						
						<div class="m-login__account">
							<span class="m-login__account-msg" style="color:#FFF">
								<?php echo date('Y')." © Design & Developed By";  ?>
							</span>
							<a href="https://goldenbrainsithub.com" title="GoldenBrainsITHub" target="_blank">
								<sup><img src="images/GoldenBrainsITHUBFullLogo.png" alt=""  width="90px;"></sup>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<h1 style="display:none;" >&nbsp; </h1>
		<h2 style="display:none;" >&nbsp;</h2>
		<h3 style="display:none;" >&nbsp;</h3>
		<!-- end:: Page -->

		<!--begin::Global Theme Bundle -->
        <script src="js/jquery.min.js" type="text/javascript"></script>
		<script src="assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts -->
		

		<!--end::Page Scripts -->
        <script>
		
		function getmobile()
		{
			var mobileNumber=$("#mobileNumber").val();
			if(mobileNumber=="")
			{
				$(".mobile").remove();
			}
		}
		
		function getpassword()
		{
			var password=$("#pass").val();
			if(password=="")
			{
				$(".password").remove();
			}
		}
		
		$(document).ready(function(){
		
			$("#login").validate({
					rules:{
						mobileNumber:{
							required:true
						},
					},
					messages:{
						mobileNumber:{
							required:"Mobile Number Required"
						},
					},
					errorElement:"div",
					errorClass:"error",
					errorPlacement: function(error, element) { // render error placement for each input type
						error.appendTo("#mobileNumberError");
					}
			});
			$("#loginuser").validate({
					rules:{
						password:{
							required:true,
						},
					},
					messages:{
						password:{
							required:"Password Required",
						},
					},
					errorElement:"div",
					errorClass:"error",
					errorPlacement: function(error, element) { // render error placement for each input type
						error.appendTo("#passwordError");
					}
			});
		});
		<?php
			// hide mobile number form and display password form when registered mobile number is enter.
		
			if(isset($success))
			{
		?>
				$("#signin").hide();
				$("#signuser").show();
		<?php 
			}
		?>
		
		// by default set focus on password textbox
		
		function formfocus() {
			document.getElementById('pass').focus();
		}
		window.onload = formfocus;
		</script>
	</body>

	<!-- end::Body -->
</html>
<script>

	toastr.options = {
	  "closeButton": false,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": false,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};

	// change border color of button when it got focus
	
	$("button").focus(function(){
		$(this).css("border", "solid 2px #25182F");
	}); 
		
	// remove border color of button when it lost focus	
	
	$("button").focusout(function(){
		$(this).css("border", "solid 2px #43475A");
	});
	
	<?php if(isset($_SESSION['resetPassword'])) { ?>
		
		toastr.success("Password Reset Successfully");
		
	<?php unset($_SESSION['resetPassword']); } ?>
	
	// show and hide password
	
	function show_password() {	
	  var x = $("input[name=\"password\"]").attr("type");
	  if (x === "password") {
		$("input[name=\"password\"]").attr("type","text");
		$("i").removeClass("fa fa-eye");
		$("i").addClass("fa fa-eye-slash");
		$("i").attr("title","Hide Password");
	  } else {
		$("input[name=\"password\"]").attr("type","password");
		$("i").removeClass("fa fa-eye-slash");
		$("i").addClass("fa fa-eye");
		$("i").attr("title","Show Password");
	  }
	}
	
</script>