<?php
require_once 'AppInit.php';

if(isset($_REQUEST['block'])) {
  Block::show($_REQUEST['block']);
}
?>
