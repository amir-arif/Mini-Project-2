<?php
require_once __DIR__ . '/controllers/StudentController.php';
extract(handlePasswordUpdate($studentModel));
require __DIR__ . '/views/password_update.php';
