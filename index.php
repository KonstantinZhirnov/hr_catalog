<?php
require_once 'AppInit.php';

Log::Show(Helper::getGUID());
Log::Show(Helper::getGUID(true));

Log::Show(System::CurrentUser());
Log::Show(System::CurrentUser()->isUserValid(), true);
?>