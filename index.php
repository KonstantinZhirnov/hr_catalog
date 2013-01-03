<?php
require_once 'AppInit.php';

$user = new User('keen', 'lad');

Log::Show($user);
