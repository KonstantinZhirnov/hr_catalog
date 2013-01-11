<?php
require_once 'AppInit.php';

Log::Show(System::CurrentUser());
Log::Show(System::CurrentUser()->isUserValid(), true);
Log::Show(User::getUserByKey(System::CurrentUser()->authKey));
?>