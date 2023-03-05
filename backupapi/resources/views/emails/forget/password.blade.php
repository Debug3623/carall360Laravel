
<!DOCTYPE html>
<html>
	<head>
		<title>Forget Password</title>
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap');
		</style>
	</head>
	<body style="background: #ececec;padding: 20px">
		<!-- Full hieght and Width of Template -->
		<div style="height: auto;width: 524px; background: white; margin: auto; ">
			<!-- header div -->
			<!--<div style="height: px;width: 100%; background: #b3d0f5ad;  line-height: 44px;">-->
			<!--	<span style="padding-left: 62px;padding-right: 62px;color: white;font-size: 16pt;font-family: 'Montserrat', sans-serif;">-->
			<!--		 <img src="https://clinicall-assets.s3.us-east-2.amazonaws.com/assets/logo.png" style="vertical-align: middle"> -->
			<!--		<img src="https://drive.google.com/file/d/1U_nTmMiisp0uBpl6eB9O5Kk53Kcocdzf/view" style="vertical-align: middle">-->
			<!--	</span>-->
			<!--</div>-->
			<!-- /header div -->
			<!---->
	         <br><br>
			<!---->
			<div style="margin: 20px 62px;">
				<div style="margin-top: 20px">
					<span style="font-size:12pt;color: #204593;font-family: 'Montserrat', sans-serif;"><strong>Forget Password?</strong></span><br>
				</div>
				<div style="margin-top: 34px">
					
				</div>
				
				<div style="margin-top: 10px">
					
					<div style="margin-top: 50px">
					<span style="font-size:09pt;">Hi, {{$name}} There was a request to change your email password If you did not make this request, just ignore This email. Otherwise, please
						Click the button below to change your password  </span><br>
					</div>
				</div>
				
				<div style="margin-top: 30px">	
					@component('mail::button', ['url' => config('app.forget_password_url')."/$identifier/$code"])
									Reset Password
					@endcomponent				
				
				<div>
					<br>
						This code will be expired after <b>{{$expiry_after}} minutes</b>. In case you did not generate forget password request, simply ignore this email.
					<br>
				</div>
				</div>
			<div style="margin-top: 40px">
					<div style="margin-top: 50px">
					<span style="color: grey; font-size: 8pt">CONFIDENTIALITY NOTICE!</span>
					<p style="color: grey;font-size: 6pt">This email is intended only for the person(s) named in the message header.Unless otherwise indicated, 
it contains informationThat is confidential, privilegedAnd/or exempt from disclosure under applicable law.
If you have received this message In error, please notify the sender of the error and delete the message.<br>
Thank you</p>
				</div>
				</div>
			</div>


			<div style="padding:18px 0px;width: 100%; background: #b3d0f5ad;text-align: center;color: white;margin-top: 40px;">
			  <div id="content" style="
				  flex: 0 0 120px;font-size: 10pt">
				<div style="font-size: 10pt">
					<a style="color: white; text-decoration: none;color: #204593" href="">Terms and Conditions | </a>  <a style="color: white;text-decoration: none;color: #204593" href="">Privacy</a>
				</div>
				<div style="color: #204593">Copyright &copy; 2021 CareAll360</div>
			  </div>
			</div>
		</div>
		<!-- /Full hieght and Width of Template -->
	</body>
</html>

