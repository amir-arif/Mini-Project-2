<?php
/**
 * controllers/GradeController.php
 * Part 2.2 CRUD operations: Create, Read, Update, Delete of student
 * grade records (Name, IC, Marks). Name and IC come from the `student`
 * table through the foreign key relationship; marks are stored here.
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/GradeModel.php';
require_once __DIR__ . '/../models/StudentModel.php';

$gradeModel = new GradeModel($conn);
$studentModel = new StudentModel($conn);

function isValidMarks($marks): bool
{
    return is_numeric($marks) && $marks >= 0 && $marks <= 100;
}

/**
 * manage_grades.php - handles create/update/delete for the current
 * request (if any), then always returns the current grade list +
 * student list (for the "select a student" dropdown) for the view.
 */
function handleGradeManagement(GradeModel $gradeModel, StudentModel $studentModel): array
{
    $error = "";
    $success = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === "create") {
            $student_id = (int) $_POST['student_id'];
            $marks = $_POST['marks'];

            if ($student_id <= 0) {
                $error = "Please select a student.";
            } elseif (!isValidMarks($marks)) {
                $error = "Marks must be a number between 0 and 100.";
            } else {
                $result = $gradeModel->create($student_id, (float) $marks);
                $error = $result === true ? "" : "Error: " . $result;
                $success = $result === true ? "Grade record added." : "";
            }
        }

        if ($action === "update") {
            $grade_id = (int) $_POST['grade_id'];
            $student_id = (int) $_POST['student_id'];
            $marks = $_POST['marks'];

            if ($student_id <= 0) {
                $error = "Please select a student.";
            } elseif (!isValidMarks($marks)) {
                $error = "Marks must be a number between 0 and 100.";
            } else {
                $result = $gradeModel->update($grade_id, $student_id, (float) $marks);
                $error = $result === true ? "" : "Error: " . $result;
                $success = $result === true ? "Grade record updated." : "";
            }
        }

        if ($action === "delete") {
            $grade_id = (int) $_POST['grade_id'];
            $gradeModel->delete($grade_id);
            $success = "Grade record deleted.";
        }
    }

    return [
        'error' => $error,
        'success' => $success,
        'grades' => $gradeModel->getAllWithStudent(),
        'students' => $studentModel->getAll(),
    ];
}
