<?php require_once(dirname(__FILE__) . '/config.php'); 
if ( isset($_SESSION['Admin_ID']) && $_SESSION['Login_Type'] == 'admin' ) {
    header('location:' . BASE_URL . 'employees/');
}
if ( isset($_SESSION['Admin_ID']) && $_SESSION['Login_Type'] == 'emp' ) {
    header('location:' . BASE_URL . 'profile/');
} ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Login - Payroll</title>

    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/skins/_all-skins.min.css">

<style>
	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #f0f0f0;*/
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
		
		
	}
	#login-right{
		position: absolute;
    right: 0;
    width: 53.5%;
    height: calc(100%);
    background:white ;
	display: flex;
    align-items: center;
   
	}
	
	#login-left{
		position: absolute;
		left:0;
		width:100%;
		height: calc(100%);
		background:#f0f0f0;
		display: flex;
		align-items: center;
		background: url(dist/img/cover.jpg) no-repeat left;
		background-size: 46%;
	}
	#login-right .card{
		margin: auto;
		z-index: 1;
        background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
	}
	
	.logo {
    margin: auto;
    font-size: 8rem;
    background: #f0f0f0;
    padding: .5em 0.7em;
    border-radius: 50% 50%;
    color: #000000b3;
    z-index: 10;
}

div#login-right::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: calc(100%);
    height: calc(100%);
    background: #f0f0f0;
}
.header {
  background-color: #f0f0f0;
  padding: 1rem;
  color: black;
  display: flex; /* Enable flexbox layout for header elements */
  justify-content: center;
  position: absolute; /* Ensure header positioning */
  top: 0; /* Align the header to the top */
    left: 0; /* Align the header to the left */
    width: 100%;
    z-index: 2; 
}
.card {
    position: relative; /* Ensure the card is positioned relative to the header */
    z-index: 1; /* Set a lower z-index to ensure the card is behind the header */
}

.header h1 {
  font-size: 1.7rem; /* Adjust font size for headings */
}

.register-message {
  margin-top: 20px;
  margin-bottom: 10px;
  text-align: center;
  color: #333;
}

.btn-register {
  margin-top: 10px;
  background-color: blue;
  color: white;
}
</style>
</head>
<body>


  <main id="main" class=" bg-dark">
  
  		<div id="login-left">
  			
  		</div>

  		<div id="login-right">
		  <div class="header">
        <h2><b>Welcome to the Employee Payroll Management System</b></h2>
	

    </div> 
    
        <div class="card col-md-7">
            <h4 class="card-msg" align="center">Please login to start your session</h4>
            <form method="POST" role="form" data-toggle="validator" id="login-form">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="code" name="code" placeholder="Employee Code" required />
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <button type="submit" class="btn btn-success btn-block btn-flat">Login</button>

                
            </form>
        </div>


   

  </main>


    <script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/jquery-validator/validator.min.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>
    <script type="text/javascript">var baseurl = '<?php echo BASE_URL; ?>';</script>
    <script src="<?php echo BASE_URL; ?>dist/js/script.js?rand=<?php echo rand(); ?>"></script>
</body>
</html>