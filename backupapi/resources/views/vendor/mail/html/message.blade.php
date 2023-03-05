@component('mail::layout')
        <div class="box-width" style="height: auto;  background: white; margin: auto; ">
			<div style="height: px;width: 100%; background: #b3d0f5ad;  line-height: 44px;">
				<span style="padding-left: 62px;padding-right: 62px;color: white;font-size: 16pt;font-family: 'Montserrat', sans-serif;">
					<img src="{{config('app.aws_s3_bucket')}}assets/logo.png" style="vertical-align: middle">
				</span>
			</div>
			<div style="margin: 20px 62px;">
				<!-- <div style="margin-top: 20px">
					<span style="font-size:12pt;color: #204593;font-family: 'Montserrat', sans-serif;"><strong>Forget Password?</strong></span><br>
				</div> -->
				<div style="margin-top: 10px">
					<div style="margin-top: 50px">
					    <span style="font-size:09pt;">  {{ $slot }} </span>
                        <br>
					</div>
				</div>
			    <div style="margin-top: 40px">
					<div style="margin-top: 50px">
					    <span style="color: grey; font-size: 8pt">CONFIDENTIALITY NOTICE!</span>
					    <p style="color: grey;font-size: 6pt">This email is intended only for the person(s) named in the message header.Unless otherwise indicated, 
                            it contains informationThat is confidential, privilegedAnd/or exempt from disclosure under applicable law.
                            If you have received this message In error, please notify the sender of the error and delete the message.<br>
                            Thank you
                        </p>
				    </div>
				</div>
			</div>
			<div style="padding:18px 0px;width: 100%; background: #b3d0f5ad;text-align: center;color: white;margin-top: 40px;">
			    <div id="content" style="flex: 0 0 120px;font-size: 10pt">
				    <div style="font-size: 10pt">
					    <a style="color: white; text-decoration: none;color: #204593" href="">Terms and Conditions | </a>  <a style="color: white;text-decoration: none;color: #204593" href="">Privacy</a>
				    </div>
				    <div style="color: #204593">Copyright &copy; {{ date('Y') }} {{ config('app.name') }}</div>
			  </div>
			</div>
		</div>
@endcomponent
