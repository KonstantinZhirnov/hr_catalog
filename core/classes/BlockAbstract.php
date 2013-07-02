<?php

/**
 * Description of BlockAbstract
 *
 * @author Konstantin Zhirnov
 */
class BlockAbstract implements IBlock {
  
  protected $content = '';
  
  public function render(){}
  
  public function process() {
    return true;
  }
  
  public function show(){
    print $this->render();
  }
}

?>
