<?php

/**
 * Description of FooterBlock
 *
 * @author Konstantin Zhirnov
 */
class FooterBlock extends BlockAbstract {
  
  public function render() {
    ob_start();?>
    </div>
    <div class="footer">
      &copy; iLogos 2013 <?php print date("Y")!= "2013" ? ' - ' . date('Y') : '';?>
    </div>
    <div class="bottom_bg">&nbsp;</div>
    </div>
  </body>
</html>
<?php
    $this->content .= ob_get_clean();
    return $this->content;
  }
}

?>
