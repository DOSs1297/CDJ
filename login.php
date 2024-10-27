<?php
 
 require_once('classes/database.php');
 $con=new database();


// echo $_POST['username'];

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $result = $con->check($username, $password);  
  echo $result;
if($result) {
      $_SESSION['username'] = $result['username'];
      $_SESSION['User_Id'] = $result['User_Id'];
  if ($result['account_type']==1){
     header('location:user_account.php');
  }
  else if ($result['account_type']==0){
      header('location:index3.php');
  }
}
  else{
$error = "Incorrect username or password ";
  }
}
?>
 
 
 
<!DOCTYPE html>
 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-image: url('');
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-container {
      margin-top: 200px;
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 100%;
    }
  </style>
 
</head>
<body>
   
<div class="container-fluid login-container rounded shadow">
  <h2 class="text-center mb-4">Login</h2>
 
 
 
 
  <form method="post">
    <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" class="form-control" name="username" placeholder="Enter username">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" name="password" placeholder="Enter password">
    </div>
<div class="container">
  <div class="row gx-1">
  <?php if (!empty($error_message)) : ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
    <div class="col"><input type="submit" class="btn btn-info btn-block" value="Login" name="login"></div>
    <div class="col"><a class="btn btn-danger btn-block" href="register.php">Sign-Up</a></div>
  </div>
  </div>
</div>
   
  </form>
</div>
 
 
 
<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>
 
    