<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>bcheck - Backup Checker</title>
		<link rel="shortcut icon" type="image/x-icon" href="layout/img/favicon.ico" />
		<link type="text/css" href="layout/css/style.css" rel="stylesheet" />
		<script type="text/javascript" src="layout/js/jquery-1.7.1.min.js"></script>
		<script>
			$(document).ready(function() {
				$('input[name=user]').focus();
			});
		</script>
	</head>
	<body>
		<div id="topHeader"></div>
		<div id="header">
		
			<div id="logo"></div>
					
		</div>
		<div id="container">
		
			<div id="loginForm">
				<form method="post" action="index.php">
				<table>
					<tr>
						<td colspan="2" id="invalidLogin">Username or password invalid</td>
					</tr>				
					<tr>
						<td>Gebruikersnaam:</td>
						<td><input type="text" name="user" size="20" /></td>
					</tr>
					<tr>
						<td>Wachtwoord:</td>
						<td><input type="password" name="pass" size="20" /></td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="hidden" name="login" value="1" />
							<input id="loginSubmit" type="submit" value="Inloggen"/>
						</td>
					</tr>
				</table>

				</form>
			</div>

		</div>
	</body>
</html>
