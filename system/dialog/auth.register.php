<div class="panel panel-primary card">
	<div class="panel-heading">
		<h2 class="text-white">
			<span class="glyphicon pull-right glyphicon-pencil"></span>
    		Register
    	</h2>
	</div>
  	<div class="panel-body">
    	<form class="form-horizontal" method="post">
    		<div class="form-group">
    			<label class="col-sm-12 control-label" for="email">Please enter your email address</label>
    			<div class="col-sm-12">
    				<input class="form-control" name="email" id="auth_username" type="email" title="Email Address" placeholder="you@some.com" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label class="col-sm-12 control-label" for="password">Please enter your password</label>
    			<div class="col-sm-12">
    				<input class="form-control" name="password" id="auth_password" type="password" title="Password" placeholder="Secret" required>
    			</div>
    		</div>
    		<div class="form-group">
    			<label class="col-sm-12 control-label" for="confirm">Please confirm your password</label>
    			<div class="col-sm-12">
    				<input class="form-control" name="confirm" id="auth_confirm" type="password" title="Confirm your Password" placeholder="Secret Again" required>
    			</div>
    		</div>
			<br>
    		<div class="form-group">
    			<div class="col-sm-6 col-xs-4">
    				<div class="text-muted">
    					<a href="javascript:void(0);" class="auth_signin" title="Click to sign in to an account">Have an account?</a>
    				</div>
    				<div class="text-muted">
    					<a href="javascript:void(0);" class="auth_forgot" title="Click to recover your password">Forgot Password?</a>
    				</div>
    			</div>
    			<div class="col-xs-8 col-sm-6 right">
    				<button type="button" class="dialog_cancel btn btn-default">Cancel</button>
    				<button type="button" class="auth_perform_register btn btn-primary">Register</button>
				</div>
    		</div>
    	</form>
	</div>
</div>