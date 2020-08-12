<?php
/**
 * Creates a game view
 */
function showGame($username, $game) {
    $board = $game->getBoard();
    $gameID = $game->getId();
    $startTime = date("Y/m/d h:i A", $game->getStartTime());
    $endTime = date("Y/m/d h:i A", $game->getEndTime());
    $player1 = $game->getPlayer1();
    $player2 = $game->getPlayer2();
    $player1Username = $game->getPlayer1Username();
    $player2Username = $game->getPlayer2Username();
    $result = $game->getResult();
    $currentPlayer = $game->getCurrentPlayer();
    $toPlay = $currentPlayer === $player1 ? "X" : "O";
    $userHasMove = false;
    if ($username === $player1Username && $currentPlayer === $player1 ||
        $username === $player2Username && $currentPlayer === $player2) {
        $userHasMove = true;
    }
    include VIEWS . 'ttt/ttt_board.php';
} // end showGame
?>
