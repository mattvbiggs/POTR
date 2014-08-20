<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login | POTR</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/cupertino/jquery-ui-1.10.3.custom.css" />
	
	<script src="scripts/jquery-ui-1.10.3/jquery-1.9.1.js"></script>
	<script src="scripts/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
	
	<script>
		$(document).ready(function() { 
			$("#input-error").hide();
		
			var querystring = location.search;
			
			if (querystring != "") {
				$("#input-error").show();
			} else {
				$("#input-error").hide();
			}
			
		});
	</script>
</head>
<body>

	<div class="login-header">Patient Outcome Tracking and Reporting</div>	
	<div>
		<form id="login_form" method="post" action="process_login.php">
			<table class="form-2">
				<tr>
					<td><label for="login"><i class="icon-user"></i>Username</label></td>
					<td>
						<input type="text" id="login" name="login" placeholder="Username or email">
						<div id="usererror" class="inputerror"></div>
					</td>
				</tr>
				<tr>
					<td><label for="password"><i class="icon-lock"></i>Password</label></td>
					<td><input type="password" id="password" name="password" placeholder="Password" class="showpassword"></td>
				</tr>
				<tr>
					<td colspan="2"><div id="input-error" class="input_error">Invalid Username and Password!</div></td>
				</tr>
				<tr class="clearfix">
					<td colspan="2" align="center"><input type="submit" id="submit" name="submit" value="Log In"><br/>
					<div id="clear" style="cursor: ponter;"><label for="reset">reset</label></div></td>
				</tr>
			</table>     
		</form>
	</div>

</body>
</html>