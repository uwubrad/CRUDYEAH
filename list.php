<?php

include_once("connect.php");
$statement = $conn->prepare("SELECT * FROM user");
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>ACCOUNTS</title>
  </head>
  <body>
    <h3 class="text-center">List of Accounts</h3>

    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">User ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Email</th>
          <th scope="col">Username</th>
          <th scope="col">Position</th>
          <th scope="col">Account Status</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $i => $user): ?>
          <tr>
            <th scope="row"><?php echo $i+1 ?></th>
            <td><?php echo htmlspecialchars($user['User_ID']) ?> </td>
            <td><?php echo htmlspecialchars($user['FirstName']) ?> </td>            
            <td><?php echo htmlspecialchars($user['LastName']) ?> </td>
            <td><?php echo htmlspecialchars($user['Email']) ?> </td>
            <td><?php echo htmlspecialchars($user['Username']) ?> </td>
            <td><?php echo htmlspecialchars($user['Position']) ?> </td>
            <td><?php echo htmlspecialchars($user['AccountStatus']) ?> </td>
            <td>
              <a href="edit.php?id=<?php echo $user['User_ID'] ?>" class="btn btn-outline-success">Edit</a>
              <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(<?php echo $user['User_ID'] ?>)">Delete</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
      <div class="col text-center">
        <a href="index.php" class="btn btn-secondary">Home</a>
      </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
      function confirmDelete(userId) {
        if (confirm("Are you sure you want to delete this account?")) {
          window.location.href = "delete.php?id=" + userId;
        }
      }
    </script>
  </body>
</html>
