<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="profile.php">PSP Student Portal</a>
  </div>
</nav>

<div class="container border mt-5 p-5 rounded-4">
    <h2 class="text-center">Update Password</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success mt-3"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="password_update.php">
        <label for="old_password" class="form-label mt-3">Old Password</label>
        <input type="password" name="old_password" id="old_password" class="form-control" required>

        <label for="new_password" class="form-label mt-3">New Password</label>
        <input type="password" name="new_password" id="new_password" class="form-control" required>

        <label for="confirm_password" class="form-label mt-3">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>

        <div class="d-grid pt-4">
          <button class="btn btn-primary" type="submit">Confirm</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
