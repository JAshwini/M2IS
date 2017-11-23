<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<!-- Stylesheet -->
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/font-awesome.min.css">
  <link rel="stylesheet" href="./css/jquery.dataTables.min.css">
</head>
<body style="background-color:lightgray">
	<div class="container" style="margin-top: 10%"><center><h2>Movies Management Information System</h2></center></div>
	<div class="container col-lg-5 border p-5" style="margin-top: 5%;background-color: white">
		<form method="get" action="logic.php">
			<div class="form-group">
			<input type="hidden" name="action" value="login">
			</div>
			<div class="form-group row">
			  <div class="col-lg-3"><label for="usr">Username:</label></div>
			  <div class="col-lg-9"><input type="text" class="form-control" name="username" required></div>
			</div>
			<div class="form-group row">
			  <div class="col-lg-3"><label for="usr">Password:</label></div>
			  <div class="col-lg-9"><input type="password" class="form-control" name="password" required></div>
			</div>
			<div class="col-lg-12"><center><button type="submit" class="btn-primary">Login</button></center></div>
		</form>
		<?php
		if((isset($_SESSION["loginerror"])) && ($_SESSION["loginerror"])) {
			echo '<br/><div class="alert alert-danger">
  <strong>Error!</strong> Invalid Username or Password
</div>';
		}
		?>
	</div>

	<script type="text/javascript" src="./js/jquery.min.js.download"></script>
    <script type="text/javascript" src="./js/popper.min.js.download"></script>
    <script type="text/javascript" src="./js/bootstrap.min.js.download"></script>
    <script type="text/javascript" src="./js/jquery.dataTables.min.js.download"></script>
</body>

</html>
