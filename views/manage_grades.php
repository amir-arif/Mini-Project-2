<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Grades</title>
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
        <h2>Manage Grades</h2>
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#gradeOffcanvas"
                data-bs-action="create">
            + New Grade Record
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
                <th>Name</th>
                <th>IC (NRIC)</th>
                <th>Marks</th>
                <th style="width: 160px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $grades->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['nric']); ?></td>
                <td><?php echo htmlspecialchars($row['marks']); ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary edit-grade-btn"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#gradeOffcanvas"
                        data-grade-id="<?php echo htmlspecialchars($row['grade_id']); ?>"
                        data-student-id="<?php echo htmlspecialchars($row['student_id']); ?>"
                        data-marks="<?php echo htmlspecialchars($row['marks']); ?>">
                        Edit
                    </button>
                    <form method="POST" action="manage_grades.php" class="d-inline"
                          onsubmit="return confirm('Delete this grade record? This cannot be undone.');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="grade_id" value="<?php echo htmlspecialchars($row['grade_id']); ?>">
                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Offcanvas: shared by Create + Edit -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="gradeOffcanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="gradeOffcanvasTitle">Add New Grade Record</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form method="POST" action="manage_grades.php" id="gradeForm">
        <input type="hidden" name="action" id="gradeFormAction" value="create">
        <input type="hidden" name="grade_id" id="gradeFormGradeId" value="">

        <label for="formStudentId" class="form-label mt-2">Student (Name - IC)</label>
        <select name="student_id" id="formStudentId" class="form-select" required>
            <option value="">-- Select student --</option>
            <?php while ($s = $students->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($s['student_id']); ?>">
                    <?php echo htmlspecialchars($s['name'] . ' - ' . $s['nric']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="formMarks" class="form-label mt-3">Marks (0-100)</label>
        <input type="number" step="0.01" min="0" max="100" name="marks" id="formMarks" class="form-control" required>

        <div class="d-grid pt-4">
            <button class="btn btn-primary" type="submit" id="gradeFormSubmitBtn">Add Record</button>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
const gradeOffcanvasTitle = document.getElementById('gradeOffcanvasTitle');
const gradeFormAction = document.getElementById('gradeFormAction');
const gradeFormGradeId = document.getElementById('gradeFormGradeId');
const formStudentId = document.getElementById('formStudentId');
const formMarks = document.getElementById('formMarks');
const gradeFormSubmitBtn = document.getElementById('gradeFormSubmitBtn');

// "New Grade Record" button resets the form to create-mode
document.querySelector('[data-bs-action="create"]').addEventListener('click', () => {
    gradeOffcanvasTitle.textContent = "Add New Grade Record";
    gradeFormAction.value = "create";
    gradeFormGradeId.value = "";
    formStudentId.value = "";
    formMarks.value = "";
    gradeFormSubmitBtn.textContent = "Add Record";
});

// Each "Edit" button fills the form with that row's data
document.querySelectorAll('.edit-grade-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        gradeOffcanvasTitle.textContent = "Edit Grade Record";
        gradeFormAction.value = "update";
        gradeFormGradeId.value = btn.dataset.gradeId;
        formStudentId.value = btn.dataset.studentId;
        formMarks.value = btn.dataset.marks;
        gradeFormSubmitBtn.textContent = "Save Changes";
    });
});
</script>
</body>
</html>
