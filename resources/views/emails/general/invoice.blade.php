<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
</head>
    <body>
        <style>
            .invoice-title h2, .invoice-title h3 {
                display: inline-block;
            }
            .table > tbody > tr > .no-line {
                border-top: none;
            }
            .table > thead > tr > .no-line {
                border-bottom: none;
                border-right: 2px dotted;
                line-height: 10%;
            }
            .table > tbody > tr > .thick-line {
                border-top: 2px solid;
            }
            @media only screen and (max-width: 600px) {
                .inner-body {
                    width: 100% !important;
                }
                .footer {
                    width: 100% !important;
                }
            }
            @media only screen and (max-width: 500px) {
                .button {
                    width: 100% !important;
                }
            }
        </style>

        <div style="height: auto;width: 524px; background: white; margin: auto; ">
			<div style="height: px;width: 100%; background: #b3d0f5ad;  line-height: 44px;">
				<span style="padding-left: 62px;padding-right: 62px;color: white;font-size: 16pt;font-family: 'Montserrat', sans-serif;">
					<img src="{{config('app.local')}}assets/logo.png" style="vertical-align: middle">
				</span>
			</div>
			<div style="margin: 20px 62px;">
                <div class="row">
                    <div class="col-12">
                        <div class="invoice-title">
                            <h2 style="display: inline-block;">Invoice</h2>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div style="display: flex;">
                                    <span >
                                        <strong>Billed To:</strong><br>
                                        {{$msg['doctor_name']}}<br>
                                    </span>
                                    <span style="padding-left: 50%;" class="d-flex justify-content-end">
                                        <strong>Summary Date:</strong><br>
                                        {{$msg['date']}}<br>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="panel panel-default">
                            <div class="panel-heading invoice-title" style="margin-top: 10px">
                                <h3 class="panel-title"><strong>Payment Summary</strong></h3>
                            </div>
                            <hr>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <td class="text-center" style="padding-right: 7%;"><strong>Actual Amount</strong></td>
                                                <td class="text-center" style="padding-right: 7%;"><strong>Service Charges</strong></td>
                                                <td class="text-center"><strong>Net Amount</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">RS.{{$msg['actual_amount']}}</td>
                                                <td class="text-center">RS.{{$msg['service_charges']}}</td>
                                                <td class="text-right">RS.{{$msg['net_amount']}}</td>
                                            </tr>
                                            <tr>
                                                <td class="thick-line"></td>
                                                <td class="thick-line"></td>
                                                <td class="thick-line text-center"><strong>Total</strong></td>
                                                <td class="thick-line text-right">RS. {{$msg['total']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
    </body>
</html>