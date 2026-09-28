<?php
/**
 * models/GradeModel.php
 * All database access for the `grades` table.
 * `grades.student_id` is a foreign key to `student.student_id`, so every
 * read joins the two tables - Name and IC come from the related student
 * row instead of being duplicated here.
 */

class GradeModel
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // READ - list all grade records with the student's Name and IC
    public function getAllWithStudent(): mysqli_result
    {
        $sql = "SELECT g.grade_id, g.marks,
                       s.student_id, s.nric, s.name
                FROM grades g
                INNER JOIN student s ON g.student_id = s.student_id
                ORDER BY g.grade_id DESC";
        return $this->conn->query($sql);
    }

    // CREATE
    public function create(int $studentId, float $marks)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO grades (student_id, marks) VALUES (?, ?)"
        );
        $stmt->bind_param("id", $studentId, $marks);
        $ok = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        return $ok ? true : $error;
    }

    // UPDATE
    public function update(int $gradeId, int $studentId, float $marks)
    {
        $stmt = $this->conn->prepare(
            "UPDATE grades SET student_id = ?, marks = ? WHERE grade_id = ?"
        );
        $stmt->bind_param("idi", $studentId, $marks, $gradeId);
        $ok = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        return $ok ? true : $error;
    }

    // DELETE
    public function delete(int $gradeId): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM grades WHERE grade_id = ?");
        $stmt->bind_param("i", $gradeId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
