<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php
     if (isset($_POST['submit'])){
         $FulName= $_POST["username"];
         $Email= $_POST["email"];
         $Password= $_POST["password"];
         $ConfirmPassword= $_POST["confirmPassword"];


         $passwordHash= password_hash($ConfirmPassword,PASSWORD_DEFAULT);
         $errors = array();
         if (empty($FulName) or empty($Email) or empty($Password) or empty($ConfirmPassword)){
             array_push($errors,"All fields are required");
         }
         if (!filter_var($Email,FILTER_VALIDATE_EMAIL)){
             array_push($errors,"Email is not valid..!!!!");
         }if(strlen($Password)<8){
             array_push($errors,"Password required 8 charters..!!!!");
         }if($Password!==$ConfirmPassword){
             array_push($errors,"Password dose not match..!!!!");
         }
         require_once "database.php";
         $sql="SELECT * FROM users WHERE email ='$Email'";
        $result= mysqli_query($conn,$sql);
        $rowCount =mysqli_num_rows($result);
        if ($rowCount>0){
            array_push($errors,"Email Allready exits");
        }
         if (count($errors)>0) {
             foreach ($errors as  $error) {
                 echo "<div class='alert alert-danger'>$error</div>";
             }
         }else{



             $sql = "INSERT INTO users (first_name, email, password) VALUES ( ?, ?, ? )";
             $stmt = mysqli_stmt_init($conn);
             $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
             if ($prepareStmt) {
                 mysqli_stmt_bind_param($stmt,"sss",$FulName, $Email, $passwordHash);
                 mysqli_stmt_execute($stmt);
                 echo "<div class='alert alert-success'>You are registered successfully.</div>";
             }else{
                 die("Something went wrong");
             }
         }
     }





            ?>
            <h2 class="text-center">Registration Form</h2>
            <form action="registration.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" value="Register" name="submit">Register</button>
            </form>
        </div>
    </div>
</div>

<!-- Include Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>