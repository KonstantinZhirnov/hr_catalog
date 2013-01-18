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
    $this->content .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>iLogos HR catalog</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
  </head>
  <body>';
    return $this->content;
  }
}

?>
