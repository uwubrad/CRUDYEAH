<?php

include_once("connect.php");

$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST['fname'])) {
        $errors[] = "First Name is required.";
    }
    if (empty($_POST['lname'])) {
        $errors[] = "Last Name is required.";
    }
    if (empty($_POST['email'])) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($_POST['uname'])) {
        $errors[] = "Username is required.";
    }
    if (empty($_POST['posi']) || $_POST['posi'] == "Choose...") {
        $errors[] = "Position is required.";
    }

    if (empty($errors)) {
        // Check for duplicate email
        $email = $_POST['email'];
        $statement = $conn->prepare("SELECT COUNT(*) FROM user WHERE Email = :email");
        $statement->bindValue(':email', $email);
        $statement->execute();
        $emailCount = $statement->fetchColumn();

        if ($emailCount > 0) {
            $errors[] = "Email is already in use.";
        }

        // Check for duplicate username
        $uname = $_POST['uname'];
        $statement = $conn->prepare("SELECT COUNT(*) FROM user WHERE Username = :uname");
        $statement->bindValue(':uname', $uname);
        $statement->execute();
        $usernameCount = $statement->fetchColumn();

        if ($usernameCount > 0) {
            $errors[] = "Username is already in use.";
        }

        if (empty($errors)) {
            // If no errors, proceed with database insertion
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $pass = $_POST['pass'];
            $posi = $_POST['posi'];
            $stat = 'PENDING';

            $statement = $conn->prepare("INSERT INTO user (FirstName, LastName, Email, Pass, Username, Position, AccountStatus) 
                                        VALUES (:fname, :lname, :email, :pass, :uname, :posi, :stat)");

            $statement->bindValue(':fname', $fname);
            $statement->bindValue(':lname', $lname);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':pass', $pass);
            $statement->bindValue(':uname', $uname);
            $statement->bindValue(':posi', $posi);
            $statement->bindValue(':stat', $stat);

            if ($statement->execute()) {
                $successMessage = "Account Created successfully!";
                header("refresh:1;url=login.php");
            } else {
                $errors[] = "Error: " . $statement->errorInfo()[2];
            }
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
  <title>INVENTORY</title>

  <style>
      body {
          font-family: Arial, sans-serif;
          background-color: #f8f9fa;
          margin: 0;
          padding: 0;
      }
      .center-form{
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
      <h3 class="text-center">Create New Account</h3>
        <div class="text-center ">
            <a href="login.php">Already have an Account?</a>
        </div>

      <!-- Display Errors -->
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
                <div><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Display Success Message -->
      <?php if ($successMessage): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="fname" class="form-control" value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>">
            <label class="form-label">Last Name</label>
            <input type="text" name="lname" class="form-control" value="<?php echo isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : ''; ?>">
            <label class="form-label">Email address</label>
            <input type="text" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <label class="form-label">Password</label>
            <input type="text" name="pass" class="form-control" value="<?php echo isset($_POST['pass']) ? htmlspecialchars($_POST['pass']) : ''; ?>">
            <label class="form-label">Username</label>
            <input type="text" name="uname" class="form-control" value="<?php echo isset($_POST['uname']) ? htmlspecialchars($_POST['uname']) : ''; ?>">
            <label class="form-label">Position</label>
            <select class="form-select" id="autoSizingSelect" name="posi">
                <option value="Choose..." <?php echo (isset($_POST['posi']) && $_POST['posi'] == "Choose...") ? 'selected' : ''; ?>>Choose...</option>
                <option value="Manager" <?php echo (isset($_POST['posi']) && $_POST['posi'] == "Manager") ? 'selected' : ''; ?>>Manager</option>
                <option value="Inventory-In-Charge" <?php echo (isset($_POST['posi']) && $_POST['posi'] == "Inventory-In-Charge") ? 'selected' : ''; ?>>Inventory-In-Charge</option>
            </select>
            <label class="form-label">Account Status</label>
            <input type="text" name="status" class="form-control" value="PENDING" readonly>
        </div>
        <button type="submit" class="btn btn-primary w-100">SIGN UP</button>      
        <a href="index.php" class="btn btn-secondary w-100 mt-2">BACK</a>
      </form>
    </div>
  </div>

  <!-- Optional JavaScript; choose one of the two! -->
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
