<?php
require_once __DIR__ . '/controllers/StudentController.php';
extract(getProfileData($studentModel));
require __DIR__ . '/views/profile.php';