<?php
require_once __DIR__ . '/controllers/GradeController.php';
extract(handleGradeManagement($gradeModel, $studentModel));
require __DIR__ . '/views/manage_grades.php';
