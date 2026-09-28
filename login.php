<?php
require_once __DIR__ . '/controllers/StudentController.php';
extract(handleLogin($studentModel));
require __DIR__ . '/views/login.php';
