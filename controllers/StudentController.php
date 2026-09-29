<?php
/**
 * controllers/StudentController.php
 * One function per page. Each root file calls the function it needs
 * and passes the result straight to its view.
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/StudentModel.php';

$studentModel = new StudentModel($conn);

/**
 * login.php - same check as the original: verify NRIC + hashed password,
 * then store the session variables the rest of the app relies on.
 */
function handleLogin(StudentModel $studentModel): array
{
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nric = trim($_POST['nric']);
        $password = $_POST['password'];

        $student = $studentModel->findByNric($nric);

        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['nric']       = $student['nric'];
            $_SESSION['name']       = $student['name'];
            $_SESSION['program']    = $student['program'];
            $_SESSION['profile_picture'] = $student['profile_picture'] ?? null;

            header("Location: profile.php");
            exit();
        } else {
            $error = "Invalid NRIC or password.";
        }
    }

    return ['error' => $error];
}

/**
 * profile.php - blocks access unless logged in, then reads straight
 * from $_SESSION (never queries another student's row).
 */
function getProfileData(StudentModel $studentModel): array
{
    requireStudentLogin();

    $error = "";
    $success = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['profile_picture'])) {
        $file = $_FILES['profile_picture'];

        $maxSize = 2 * 1024 * 1024; // 2MB
        $allowedExt  = ['jpg', 'jpeg', 'png'];
        $allowedMime = ['image/jpeg', 'image/png'];

        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            $error = "Please choose a file to upload.";
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $error = "Upload failed. Please try again.";
        } elseif ($file['size'] > $maxSize) {
            $error = "File is too large. Maximum size is 2MB.";
        } else {
            $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($file['tmp_name']);

            if (!in_array($ext, $allowedExt, true) || !in_array($mime, $allowedMime, true)) {
                $error = "Only .jpg, .jpeg and .png files are allowed.";
            } else {
                $uploadDir = __DIR__ . '/../uploads/profile/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $newName = uniqid('pfp_', true) . '.' . $ext;

                if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {

                    $old = $_SESSION['profile_picture'] ?? null;
                    if ($old && file_exists($uploadDir . $old)) {
                        unlink($uploadDir . $old);
                    }

                    $studentModel->updateProfilePicture((int) $_SESSION['student_id'], $newName);
                    $_SESSION['profile_picture'] = $newName;
                    $success = "Profile picture updated.";
                } else {
                    $error = "Could not save the uploaded file.";
                }
            }
        }
    }

    $student = $studentModel->findById((int) $_SESSION['student_id']);
    $_SESSION['profile_picture'] = $student['profile_picture'] ?? null;

    return ['error' => $error, 'success' => $success];
}

/**
 * password_update.php - same flow as the original: verify old password,
 * validate the new one, hash it before saving.
 */
function handlePasswordUpdate(StudentModel $studentModel): array
{
    requireStudentLogin();

    $error = "";
    $success = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $student = $studentModel->findById((int) $_SESSION['student_id']);

        if (!password_verify($old_password, $student['password'])) {
            $error = "Old password is incorrect.";
        } elseif (strlen($new_password) < 8) {
            $error = "New password must be at least 8 characters.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New password and confirm password do not match.";
        } else {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $studentModel->updatePassword((int) $_SESSION['student_id'], $new_hash);
            $success = "Password updated successfully.";
        }
    }

    return ['error' => $error, 'success' => $success];
}

/**
 * manage_students.php - same CREATE / UPDATE / DELETE / READ logic as
 * the original file, just moved out of the HTML.
 */
function handleStudentManagement(StudentModel $studentModel): array
{
    $error = "";
    $success = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === "create") {
            $nric = trim($_POST['nric']);
            $name = trim($_POST['name']);
            $password = $_POST['password'];
            $program = trim($_POST['program']);

            if ($nric === "" || $name === "" || $password === "" || $program === "") {
                $error = "All fields are required.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $result = $studentModel->create($nric, $name, $program, $hash);
                $error = $result === true ? "" : "Error: " . $result;
                $success = $result === true ? "Student added." : "";
            }
        }

        if ($action === "update") {
            $student_id = (int) $_POST['student_id'];
            $nric = trim($_POST['nric']);
            $name = trim($_POST['name']);
            $program = trim($_POST['program']);
            $password = $_POST['password'];

            if ($nric === "" || $name === "" || $program === "") {
                $error = "NRIC, Name and Program are required.";
            } else {
                $hash = $password !== "" ? password_hash($password, PASSWORD_DEFAULT) : null;
                $result = $studentModel->update($student_id, $nric, $name, $program, $hash);
                $error = $result === true ? "" : "Error: " . $result;
                $success = $result === true ? "Student updated." : "";
            }
        }

        if ($action === "delete") {
            $student_id = (int) $_POST['student_id'];
            $studentModel->delete($student_id);
            $success = "Student deleted.";
        }
    }

    return [
        'error' => $error,
        'success' => $success,
        'students' => $studentModel->getAll(),
    ];
}
