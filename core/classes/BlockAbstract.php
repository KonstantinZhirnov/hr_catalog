<?php

/**
 * Description of BlockAbstract
 *
 * @author Konstantin Zhirnov
 */
abstract class BlockAbstract implements IBlock {
  
  protected $content = '';
  
  public abstract function render();
  
  public function process() {
    return true;
  }
}

?>
