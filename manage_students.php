<?php
require_once __DIR__ . '/controllers/StudentController.php';
extract(handleStudentManagement($studentModel));
require __DIR__ . '/views/manage_students.php';
