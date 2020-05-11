<?php
class pagelist
{
  public function pagination($namepage,$total_records,$search,$show,$status)
  {
    echo '<nav aria-label="Page navigation example">';
    echo '<ul class="pagination">';
    if ($total_records>1) {
      $current_page = isset($_GET['page']) ? $_GET['page'] : 1; 
      if ($current_page > $total_records || $current_page < 1) {
        header('Location: '.domain.$namepage.'?page=1'.$search.$show.$status);
      }else{
        $ispage = true;
        for ($i=1; $i<=$total_records ; $i++) { 
          if ($current_page == (string)$i) {
            $ispage = false;
          }
        } 
        if ($ispage) {
          header('Location: '.domain.$namepage.'?page=1'.$search.$show.$status);
        }
      }
      $current_page = (int)$current_page;
      if ($current_page > 1 && $total_records > 1){
        echo '<li class="page-item"><a class="page-link" href="?page=1'.$search.$show.$status.'">First</a>  </li>';
      }else{
        echo '<li class="page-link text-dark">First </li> ';
      }
      if ($total_records>2) {
        if ($current_page==$total_records) {
          echo '<li class="page-item"><a class="page-link" href="?page='.($current_page-1).$search.$show.$status.'">'.($current_page-2).'</a>  </li>';
        }
        if ($current_page>1) {
          echo '<li class="page-item"><a class="page-link" href="?page='.($current_page-1).$search.$show.$status.'">'.($current_page-1).'</a>  </li>';
        } 
        if ($_GET['page'] = $current_page) {
          echo '<li class="page-link text-dark">'.$current_page.'</li>  ';
        }else{
          echo '<li class="page-item"><a class="page-link" href="?page='.$current_page.$search.$show.$status.'">'.$current_page.'</a>  </li>';
        }
        if ($current_page<$total_records) {
          echo '<li class="page-item"><a class="page-link" href="?page='.($current_page+1).$search.$show.$status.'">'.($current_page+1).'</a>  </li>';
        }
        if ($current_page == 1) {
          echo '<li class="page-item"><a class="page-link" href="?page='.($current_page+2).$search.$show.$status.'">'.($current_page+2).'</a>  </li>';
        }
      }else{
        for ($i=1; $i <=$total_records ; $i++) { 
          if ($i == $current_page) {
            echo '<li class="page-link text-dark">'.$i.'</li>  ';
          }else{
            echo '<li class="page-item"><a class="page-link" href="?page='.$i.$search.$show.$status.'">'.$i.'</a>  </li>';
          }
        }
      }
      if ($current_page < $total_records && $total_records > 1){
        echo '<li class="page-item"><a class="page-link" href="?page='.($total_records).$search.$show.$status.'">Last</a> </li> ';
      }else{
        echo '<li class="page-link text-dark">Last</li> ';
      }
    }
    echo '</ul>
    </nav>';
  }
}
?>