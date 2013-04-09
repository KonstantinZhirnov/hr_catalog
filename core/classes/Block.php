<?php
/**
 * Description of Block
 *
 * @author Konstantin Zhirnov
 */
class Block {
  public static function show($name) {
    $blockName = $name . "Block";
    $block = new $blockName();
    $block->show();
  }
  public static function render($name) {
    $blockName = $name . "Block";
    $block = new $blockName();
    return $block->render();
  }
  
}

?>
