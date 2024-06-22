<?php

include_once("connect.php");

$errors = [];

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $statement = $conn->prepare("SELECT * FROM user WHERE User_ID = ?");
    $statement->execute([$user_id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errors[] = "User not found!";
    }
} else {
    $errors[] = "Invalid request!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $email = $_POST['Email'];
    $username = $_POST['Username'];
    $position = $_POST['Position'];
    $accountStatus = $_POST['AccountStatus'];

    if (strcasecmp($accountStatus, 'Active') === 0 || strcasecmp($accountStatus, 'Pending') === 0) {
        $statement = $conn->prepare("UPDATE user SET FirstName = ?, LastName = ?, Email = ?, Username = ?, Position = ?, AccountStatus = ? WHERE User_ID = ?");
        $statement->execute([$firstName, $lastName, $email, $username, $position, $accountStatus, $user_id]);

        header("Location: list.php");
        exit;
    } else {
        $errors[] = "Changes cannot be accepted for inactive accounts.";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>Edit Account</title>
</head>
<body>
<div class="container">
    <h3 class="text-center">Edit Accounts</h3>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
                <div><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="FirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="FirstName" name="FirstName" value="<?php echo $user['FirstName'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="LastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="LastName" name="LastName" value="<?php echo $user['LastName'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $user['Email'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="Username" class="form-label">Username</label>
            <input type="text" class="form-control" id="Username" name="Username" value="<?php echo $user['Username'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="Position" class="form-label">Position</label>
                <select class="form-select" id="Position" name="Position" required>
                    <option value="Manager" <?php if ($user['Position'] === 'Manager') echo 'selected'; ?>>Manager</option>
                    <option value="Supervisor" <?php if ($user['Position'] === 'Supervisor') echo 'selected'; ?>>Inventory-In-Charge</option>
                </select>
        </div>
        <div class="mb-3">
            <label for="AccountStatus" class="form-label">Account Status</label>
            <input type="text" class="form-control" id="AccountStatus" name="AccountStatus" value="<?php echo $user['AccountStatus'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
