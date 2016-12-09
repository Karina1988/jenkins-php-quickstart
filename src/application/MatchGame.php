<?php

abstract class PlayerType {
    const Human = 0;
    const Computer = 1;
}

$minMatches = 1;
$maxMatches = 100;
$minMatchPick = 1;
$maxMatchPick = 3;

while(true) {
    $matches = startGame($minMatches, $maxMatches);
    $roundFinished = false;
    $currentPlayer = whoStarts();
    while(!$roundFinished) {
        printTurnInformation($currentPlayer);
        $matchesTaken = getPickInput($currentPlayer, $matches, $minMatchPick, $maxMatchPick);

        $matches -= $matchesTaken;

        if($matches > 0) {
            printRemainingMatches($matches);
        } else {
            printWinningNotification($currentPlayer);
            $roundFinished = true;
            continue;
        }
        $currentPlayer = getNextPlayer($currentPlayer);
    }
    newGame();
}

// to start the player has to decide the number of matches between 1 and 100
function startGame($minMatches, $maxMatches) {
    echo sprintf("Wie viele Streichhölzer sollen auf dem Tisch liegen? Gib eine Zahl zwischen %d und %d ein. \n", $minMatches, $maxMatches);
    $matches = readline();
    while(($matches < $minMatches) || ($matches > $maxMatches)) {
        echo sprintf("%s ist eine ungültige Eingabe, bitte gib eine Zahl zwischen %d und %d ein. \n", $matches, $minMatches, $maxMatches);
        $matches = readline();
    }
    return $matches;
}

function whoStarts() {
    echo "Wer soll starten? (Ich(I)/Computer(C))";
    $inputStartPlayer = readline();
    while (($inputStartPlayer != 'I') && ($inputStartPlayer != 'i') && ($inputStartPlayer != 'C') && ($inputStartPlayer != 'c')) {
        echo "Bitte entweder I oder C eingeben.";
        $inputStartPlayer = readline();
    }
    if(($inputStartPlayer === 'I') || ($inputStartPlayer === 'i')) {
        $currentPlayer = PlayerType::Human;
    } elseif(($inputStartPlayer === 'C') || ($inputStartPlayer === 'c')) {
        $currentPlayer = PlayerType::Computer;
    }
    return $currentPlayer;
}

function newGame() {
    echo "Möchtest du noch eine Runde spielen? (J/N)";
    $newGame = readline();
    while (($newGame != 'J') && ($newGame != 'N')) {
        echo "Bitte entweder J oder N eingeben.";
        $newGame = readline();
    }
    if($newGame === 'N') {
        exit;
    }
}

function getPickInput($player, $matches, $minMatchPick, $maxMatchPick) {
    if($player === PlayerType::Human) {
        echo sprintf("Bitte ziehe zwischen %d und %d Streichhölzer.\n", $minMatchPick, $maxMatchPick);
        $input = readline();
        while(($input < $minMatchPick) || ($input > $maxMatchPick)) {
            echo sprintf("%s ist eine ungültige Eingabe, bitte gib eine Zahl zwischen %d und %d ein. \n", $input, $minMatchPick, $maxMatchPick);
            $input = readline();
        }
        echo sprintf("Du hast %s gezogen. \n", getMatchString($input));
        return $input;
    }

    if($player === PlayerType::Computer) {
        $matchesTaken = calculateComputerPick($matches, $minMatchPick, $maxMatchPick);
        echo sprintf("Der Computer hat %s gezogen. \n", getMatchString($matchesTaken));
        return $matchesTaken;
    }
}

// returns number of matches and the word "Streichholz" in plural or singular
function getMatchString($numMatches) {
    if(abs($numMatches) > 1) {
        return $numMatches . " Streichhölzer";
    }
    return $numMatches . " Streichholz";
}

function printRemainingMatches($matches) {
    echo sprintf("Es verbleiben %s.\n", getMatchString($matches));
}

function printTurnInformation($player) {
    if($player === PlayerType::Human) {
        echo "------- Der Spieler ist am Zug -------\n";
        return;
    }
    echo "------- Der Computer ist am Zug -------\n";
    return;
}

function printWinningNotification($player) {
    if($player === PlayerType::Human) {
        echo "Du hast gewonnen! \n";
        return;
    }
    echo "Der Computer hat gewonnen! \n";
    return;
}

function getNextPlayer($currentPlayer) {
    if($currentPlayer === PlayerType::Human) {
        return PlayerType::Computer;
    }
    return PlayerType::Human;
}

// returns number of matches the computer picks
// if possible computer leaves a mulitplier of 4 as remainder
function calculateComputerPick($matches, $minMatchPick, $maxMatchPick){
    $remainder = $matches % ($minMatchPick + $maxMatchPick);
    if($remainder < $minMatchPick) {
        return rand($minMatchPick, $maxMatchPick);
    }
    return $remainder;
}

?>