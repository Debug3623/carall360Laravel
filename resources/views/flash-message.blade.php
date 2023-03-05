<div class="col-sm-12">
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<!--<button type="button" class="close" data-dismiss="alert">×</button>-->	
        <strong>{{ $message }}</strong>
</div>
<?php  session()->forget('success') ?>
@endif


@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
	<!--<button type="button" class="close" data-dismiss="alert">×</button>-->	
        <strong>{{ $message }}</strong>
</div>
<?php  session()->forget('error') ?>
@endif


@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
	<!--<button type="button" class="close" data-dismiss="alert">×</button>-->	
	<strong>{{ $message }}</strong>
</div>
<?php  session()->forget('warning') ?>
@endif


@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
	<!--<button type="button" class="close" data-dismiss="alert">×</button>-->	
	<strong>{{ $message }}</strong>
</div>
<?php  session()->forget('info') ?>
@endif


@if ($errors->any())
<div class="alert alert-danger">
	<!--<button type="button" class="close" data-dismiss="alert">×</button>-->	
	Please check the form below for errors
</div>
@endif
</div>