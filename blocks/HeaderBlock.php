<?php

/**
 * Description of headerBlock
 *
 * @author Konstantin Zhirnov
 */
class HeaderBlock extends BlockAbstract {
  /**
   * Implementation of IBlock render method
   * @return string block content
   */
  public function render() {
    ob_start();
    ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>iLogos HR catalog</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/interface.js"></script>
  </head>
  <body>
    <div class="left">&nbsp;</div>
    <div class="right">&nbsp;</div>
    <div class="wrapper">
    <?php
    $this->content .= ob_get_clean();
    $this->content .= Block::render("Menu");
    $this->content .= '<div class="top_bg">&nbsp;</div>';
    $this->content .= '<div class="content">';
    return $this->content;
  }
}

?>
