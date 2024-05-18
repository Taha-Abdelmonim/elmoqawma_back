<div class="d-flex align-items-center">
  @php
    $local = app()->getLocale() == "ar" ? 'left' : 'right';
    foreach($links as $link => $text) {
        if($link == "start") {
          echo "<h4 class='fw-bold border-end pe-2 me-2'>$text</h4>";
          continue;
        }
        if($link == "end") {
          echo "<span class='text-muted fs-6 mb-2'>$text</span>";
        } else {
          echo "<a href='$link' class='fs-6 mb-2'>$text</a> <i class='ti ti-chevron-$local mb-2'></i>";
        }
    }
  @endphp
</div>
