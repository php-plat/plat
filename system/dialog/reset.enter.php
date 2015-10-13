<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="text-white">
			<span class="glyphicon pull-right glyphicon-lock"></span>
    		Enter new Password
    	</h2>
	</div>
  	<div class="panel-body">
    	<form class="form-horizontal" method="post">
    		<input type="hidden" id="auth_token" name="token" value="<?=$_SESSION['token'];?>">
    		<div class="form-group">
    			<label class="col-sm-12 control-label" for="password">Please enter your password</label>
    			<div class="col-sm-12">
    				<input class="form-control" name="password" id="auth_password" type="password" title="Password" placeholder="Secret" required>
    			</div>
    		</div>
			<br>
    		<div class="form-group">
    			<div class="col-sm-6 col-xs-4">
    			</div>
    			<div class="col-xs-8 col-sm-6 right">
    				<button type="button" class="dialog_cancel btn btn-default">Cancel</button>
    				<button type="button" class="auth_complete_reset btn btn-primary">Reset</button>
				</div>
    		</div>
    	</form>
	</div>
</div>