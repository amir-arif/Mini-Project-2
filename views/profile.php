<?php
$pfp = !empty($_SESSION['profile_picture'])
    ? 'uploads/profile/' . $_SESSION['profile_picture']
    : 'images/default-avatar.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">PSP Student Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="password_update.php">Reset Password</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container border mt-5 p-5 rounded-4">
    <h2 class="text-center">Your Profile</h2>

    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($data['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($data['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($data['success']); ?></div>
    <?php endif; ?>

    <div class="text-center my-4">
      <?php if (!empty($_SESSION['profile_picture'])): ?>
          <img src="uploads/profile/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>"
              alt="Profile picture"
              class="rounded-circle border object-fit-cover" width="150" height="150">
      <?php else: ?>
          <i class="bi bi-person-circle text-secondary" style="font-size: 150px; line-height: 1;"></i>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="mt-3">
          <input type="file" name="profile_picture" class="form-control w-auto d-inline-block"
                accept=".jpg,.jpeg,.png" required>
          <button type="submit" class="btn btn-primary">Upload</button>
      </form>
    </div>

    <h3>Full Name: <?php echo htmlspecialchars($_SESSION['name']); ?></h3>
    <h3>NRIC: <?php echo htmlspecialchars($_SESSION['nric']); ?></h3>
    <h3>Program: <?php echo htmlspecialchars($_SESSION['program']); ?></h3>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
