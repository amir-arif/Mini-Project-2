<?php
/**
 * models/StudentModel.php
 * All database access for the `student` table lives here.
 * Controllers never write raw SQL themselves - they call these methods.
 */

class StudentModel
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Used by login.php
    public function findByNric(string $nric): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT student_id, nric, password, name, program FROM student WHERE nric = ?"
        );
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $student ?: null;
    }

    // Used by password_update.php
    public function findById(int $studentId): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT student_id, nric, password, name, program FROM student WHERE student_id = ?"
        );
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $student ?: null;
    }

    // Used by manage_students.php (READ)
    public function getAll(): mysqli_result
    {
        return $this->conn->query(
            "SELECT student_id, nric, name, program FROM student ORDER BY student_id"
        );
    }

    // Used by manage_students.php (CREATE)
    public function create(string $nric, string $name, string $program, string $passwordHash)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO student (nric, password, name, program) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $nric, $passwordHash, $name, $program);
        $ok = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        return $ok ? true : $error;
    }

    // Used by manage_students.php (UPDATE)
    public function update(int $studentId, string $nric, string $name, string $program, ?string $passwordHash = null)
    {
        if ($passwordHash !== null) {
            $stmt = $this->conn->prepare(
                "UPDATE student SET nric = ?, name = ?, program = ?, password = ? WHERE student_id = ?"
            );
            $stmt->bind_param("ssssi", $nric, $name, $program, $passwordHash, $studentId);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE student SET nric = ?, name = ?, program = ? WHERE student_id = ?"
            );
            $stmt->bind_param("sssi", $nric, $name, $program, $studentId);
        }
        $ok = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        return $ok ? true : $error;
    }

    // Used by password_update.php
    public function updatePassword(int $studentId, string $newHash): bool
    {
        $stmt = $this->conn->prepare("UPDATE student SET password = ? WHERE student_id = ?");
        $stmt->bind_param("si", $newHash, $studentId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Used by manage_students.php (DELETE)
    public function delete(int $studentId): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM student WHERE student_id = ?");
        $stmt->bind_param("i", $studentId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
