<?php
require_once 'AppInit.php';

$headers = new HeaderBlock();
print $headers->render();

$loginBlock = new LoginBlock();
print $loginBlock->render();

Log::Show(System::CurrentUser());


$footer = new FooterBlock();
$footer->render()


?>