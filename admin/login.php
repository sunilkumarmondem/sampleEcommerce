<?php
include "../classes/Adminlogin.php";
?>

<?php
$adl=new Adminlogin();
if($_SERVER['REQUEST_METHOD']=='POST'){
	$adminUser=$_POST['adminUser'];
	$adminPass=md5($_POST['adminPass']);

	$loginck=$adl->adminlogin($adminUser,$adminPass);

}

?>

<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/stylelogin.css" media="screen" />
</head>
<body>
<div class="container">
	<section id="content">
		<form action="" method="post">
			<h1>Admin Login</h1>


<span style="color:red; font-size:18px;">
	<?php
	if(isset($loginck)){
		echo $loginck;
	}
	?>
</span>

			<div>
				<input type="text" placeholder="Username" name="adminUser"/>
			</div>
			<div>
				<input type="password" placeholder="Password"  name="adminPass"/>
			</div>
			<div>
				<input type="submit" value="Log in" />
			</div>
		</form><!-- form -->
		<div class="button">
			<a href="#">Training with live project</a>
		</div><!-- button -->
	</section><!-- content -->
</div><!-- container -->
</body>
</html>