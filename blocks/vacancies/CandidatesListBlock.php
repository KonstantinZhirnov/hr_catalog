<?php

/**
 * Contains EmploeesListBlock implementation
 *
 * @author Konstantin Zhirnov
 */
class CandidatesListBlock extends BlockAbstract  {
  protected $emploees;
  
  public function render (){
    $this->process();
  }
  
  /**
   * Implementation  of IBlockprocess method
   */
  public function process() {
    $this->emploees = Candidate::getItems();
  }
}

?>
