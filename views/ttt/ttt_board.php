<div class="ttt-board<?= $userHasMove && $result === "" ? 
     " ttt-board-toplay" : "" ?>" id="ttt-game-<?= $gameID ?>">
  <table>

    <?php
    foreach ($board as $i => $square) {
        if ($i % 3 === 0) {
            echo "<tr>\n";
        }

        echo "<td" . ($square === " " && $userHasMove && $result === "" ? 
             ' class="movable movable-' . strtolower($toPlay) . '"' : "") . 
             " id=\"ttt-square-$gameID-$i\">$square</td>\n";

        if ($i % 3 === 2) {
            echo "</tr>\n";
        }
    }
    ?>

  </table>

  <div>
  <?php
    if ($result === "") {
      echo "started: $startTime";
    }
    else {
      echo "ended: $endTime";
    }
  ?>
  </div>
  <div>X: <?= $player1Username ?></div>
  <div>O: <?= $player2Username ?></div>
  <div id="ttt-toplay-<?= $gameID ?>">
  <?php
    if ($result === "") {
      echo "to play: $toPlay";
    }
    else {
      echo "result: $result";
    }
  ?>
  </div>

</div>
