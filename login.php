<?php
session_start();
include_once("connect.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST['uname'])) {
        $errors[] = "Username is required.";
    }
    if (empty($_POST['pass'])) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        // If no errors, proceed with database check
        $uname = $_POST['uname'];
        $pass = $_POST['pass'];

        $statement = $conn->prepare("SELECT * FROM user WHERE Username = :uname AND Pass = :pass");
        $statement->bindValue(':uname', $uname);
        $statement->bindValue(':pass', $pass);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['AccountStatus'] === 'ACTIVE') {
                $_SESSION['user'] = $user;
                header("Location: list.php");
                exit;
            } else {
                $errors[] = "Account is not active.";
            }
        } else {
            $errors[] = "Invalid username or password.";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link rel="stylesheet" href="app.css">
  <title>Login</title>

  <style>
      body {
          font-family: Arial, sans-serif;
          background-color: #f8f9fa;
          margin: 0;
          padding: 0;
      }
      .center-form {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-position: center;
            padding: 10px;
      }

      .form-container {
          background-color: rgba(255, 255, 255, 0.9);
          padding: 30px;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
          width: 100%;
          max-width: 500px;
      }

      .form-container h3,
      .form-container p {
          margin-bottom: 20px;
      }

      .form-container .form-control {
          background-color: rgba(255, 255, 255, 0.8);
          border: 1px solid #ced4da;
          border-radius: 5px;
          margin-bottom: 15px;
          padding: 10px;
      }

      .form-container .btn {
          padding: 10px 15px;
          border-radius: 5px;
      }

      .form-container .btn-primary {
          background-color: #007bff;
          border-color: #007bff;
      }

      .form-container .btn-secondary {
          background-color: #6c757d;
          border-color: #6c757d;
      }

      .form-container .alert {
          margin-bottom: 20px;
      }
  </style>
</head>
<body>
  <div class="center-form">
    <div class="form-container">
      <h3 class="text-center">Login</h3>
      <p class="text-center">Don't have an account? <a href="create.php">Sign Up</a></p>
     
      <!-- Display Errors -->
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
                <div><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="uname" class="form-control" value="<?php echo isset($_POST['uname']) ? htmlspecialchars($_POST['uname']) : ''; ?>">
            <label class="form-label">Password</label>
            <input type="password" name="pass" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>      
        <a href="index.php" class="btn btn-secondary w-100 mt-2">Back</a>
      </form>
    </div>
  </div>

  <!-- Optional JavaScript; choose one of the two! -->
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

