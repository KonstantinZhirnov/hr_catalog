<?php
/**
 *
 * @author Konstantin Zhirnov
 */
interface IBlock {
  /**
   * render block for display on page
   */
  public function render();
  
  /**
   * process block data retrieved from <b><code>$_REQUEST</code></b>
   */
  public function process();
}

?>
