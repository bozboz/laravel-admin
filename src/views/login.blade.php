<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
	<meta name="robots" content="nofollow" />

	<style>
	body		{background:#F3F3F3}
	.panel		{position:absolute; top:50%; left:50%; margin:-200px 0 0 -200px; width:450px}
	.fa			{font-size:20px; margin-top:9px}
	.row		{margin-bottom:5px}
	.mySubFoot	{background:#E1E1E1; width:100%; margin:0; padding:9px 2% 5px 2%; font-size:10px; color:#666}
	.mySubFoot a{color:#666}
	.text-danger{position:absolute; margin-top:8px}
	</style>
</head>

<body>

	<div class="panel panel-default">
		<div class="panel-body">
			<h2 class="PT-font">login</h2>
		</div><!-- /panel-body -->

		<div class="panel-footer">
			{{ Form::open(array('url' => 'admin/login')) }}
			<div class="row">
				<div class="col-xs-1">
					<i class="fa fa-user"></i>
				</div><!-- /col -->

				<div class="col-xs-11">
					{{Form::email('email', '', array('class' => 'form-control', 'placeholder'=>'Enter username')) }}                  		
				</div><!-- /col -->
			</div><!-- /row -->

			<div class="row">
				<div class="col-xs-1">
					<i class="fa fa-lock"></i>
				</div><!-- /col -->  

				<div class="col-xs-11">
					{{Form::password('password',  array('class' => 'form-control', 'placeholder'=>'Enter password')) }}                  		
				</div><!-- /col -->
			</div><!-- /row -->

			<span class='text-danger'>{{Session::get('message')}}</span>

			{{Form::submit('Login', array('class' => 'btn btn-default pull-right'))}}

			<div class="pull-right" style="margin:9px 10px 0 0; font-size:13px">
				{{link_to('admin/password/remind', 'Reset password');}}
			</div>	

			<div class="clearfix"></div>
			{{Form::close()}}
		</div><!-- /panel-footer -->
	</div><!-- /panel -->
	
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>        
</body>
</html>