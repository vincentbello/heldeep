<?php

  require '../init.php';
  use Parse\ParseQuery;

  // Function to check if the request is an AJAX request
  function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }

  if (is_ajax()) {

    $query = new ParseQuery("Episode");
    $query->descending("epId");
    $query->skip(FIRST_SHOWN_LIMIT);
    $heldeeps = $query->find();

    $count = count($heldeeps);

    $contents = [];

    for ($i = 0; $i < count($heldeeps); $i++) {

      $heldeep = $heldeeps[$i];

      $query = new ParseQuery("Track");
      $query->equalTo("episode", $heldeep);
      $tracks = $query->find();

      $content = "";

      for ($j = 0; $j < count($tracks); $j++) {
        $track = $tracks[$j];
        if ($special = $track->get("type")) {
          $content .= "<h4>{$special}</h4>";
        }
        $content .= "<li" . (($special) ? " class='special'" : "") . ">" . $track->get("title");
          $content .= "<a target='_blank' href='https://soundcloud.com/search?q=" . urlencode($track->get("title")) . "'><i class='fa fa-search'></i></a>";
        $content .= "</li>";
      }

      $contents['tracklist-' . sprintf('%03d', $heldeep->get("epId"))] = $content;

    }

    echo json_encode($contents);

  }

?>