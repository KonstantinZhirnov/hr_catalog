<?php

/**
 * Description of FooterBlock
 *
 * @author Konstantin Zhirnov
 */
class FooterBlock extends BlockAbstract {
  
  public function render() {
    $this->content .= '  </body>
</html>';
    return $this->content;
  }
}

?>
