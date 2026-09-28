<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="login.php">PSP Student Portal - Lecturer View!</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="manage_students.php">Manage Students</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manage_grades.php">Manage Grades</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container border mt-5 p-5 rounded-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Students</h2>
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#studentOffcanvas"
                data-bs-action="create" data-bs-title="Add New Student">
            + New Student
        </button>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover align-middle">
        <thead>
            <tr>
                <th>NRIC</th>
                <th>Name</th>
                <th>Program</th>
                <th style="width: 160px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nric']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['program']); ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary edit-btn"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#studentOffcanvas"
                        data-id="<?php echo htmlspecialchars($row['student_id']); ?>"
                        data-nric="<?php echo htmlspecialchars($row['nric']); ?>"
                        data-name="<?php echo htmlspecialchars($row['name']); ?>"
                        data-program="<?php echo htmlspecialchars($row['program']); ?>">
                        Edit
                    </button>
                    <form method="POST" action="manage_students.php" class="d-inline"
                          onsubmit="return confirm('Delete this student? This cannot be undone.');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['student_id']); ?>">
                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Offcanvas: shared by Create + Edit -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="studentOffcanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasTitle">Add New Student</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form method="POST" action="manage_students.php" id="studentForm">
        <input type="hidden" name="action" id="formAction" value="create">
        <input type="hidden" name="student_id" id="formStudentId" value="">

        <label for="nric" class="form-label mt-2">NRIC</label>
        <input type="text" name="nric" id="formNric" class="form-control" required>

        <label for="name" class="form-label mt-3">Name</label>
        <input type="text" name="name" id="formName" class="form-control" required>

        <label for="password" class="form-label mt-3">
            Password <span id="passwordHint" class="text-muted"></span>
        </label>
        <input type="password" name="password" id="formPassword" class="form-control">

        <label for="program" class="form-label mt-3">Program</label>
        <input type="text" name="program" id="formProgram" class="form-control" required>

        <div class="d-grid pt-4">
            <button class="btn btn-primary" type="submit" id="formSubmitBtn">Add Student</button>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
const offcanvasEl = document.getElementById('studentOffcanvas');
const offcanvasTitle = document.getElementById('offcanvasTitle');
const formAction = document.getElementById('formAction');
const formStudentId = document.getElementById('formStudentId');
const formNric = document.getElementById('formNric');
const formName = document.getElementById('formName');
const formPassword = document.getElementById('formPassword');
const formProgram = document.getElementById('formProgram');
const passwordHint = document.getElementById('passwordHint');
const formSubmitBtn = document.getElementById('formSubmitBtn');

// "New Student" button resets the form to create-mode
document.querySelector('[data-bs-action="create"]').addEventListener('click', () => {
    offcanvasTitle.textContent = "Add New Student";
    formAction.value = "create";
    formStudentId.value = "";
    formNric.value = "";
    formName.value = "";
    formPassword.value = "";
    formProgram.value = "";
    formPassword.required = true;
    passwordHint.textContent = "";
    formSubmitBtn.textContent = "Add Student";
});

// Each "Edit" button fills the form with that row's data
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        offcanvasTitle.textContent = "Edit Student";
        formAction.value = "update";
        formStudentId.value = btn.dataset.id;
        formNric.value = btn.dataset.nric;
        formName.value = btn.dataset.name;
        formProgram.value = btn.dataset.program;
        formPassword.value = "";
        formPassword.required = false;
        passwordHint.textContent = "(leave blank to keep current password)";
        formSubmitBtn.textContent = "Save Changes";
    });
});
</script>
</body>
</html>
