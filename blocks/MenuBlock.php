<?php
/**
 * Contains MenuBlock implementation
 *
 * @author Konstantin Zhirnov
 */
class MenuBlock extends BlockAbstract {
  public function render() {
    ob_start();?>
<div class="menu">
<a href="vacancy.php">вакансии</a> |
<a href="candidates.php">кандидаты</a> |
<a href="candidate.php?id=1">даные о кандидате</a>
</div>
<?php
    $this->content .= ob_get_clean();
    return $this->content;
  }
}

?>
