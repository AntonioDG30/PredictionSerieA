<?php
include_once 'config.php';
function calculateAggregatedRanking($conn) {
    $ranking = [];
    $sql = "SELECT * FROM predictions";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            for ($i = 1; $i <= 20; $i++) {
                $team = $row["position$i"];
                if (!isset($ranking[$team])) {
                    $ranking[$team] = [];
                }
                $ranking[$team][] = $i;
            }
        }

        foreach ($ranking as $team => &$positions) {
            $average = array_sum($positions) / count($positions);
            $ranking[$team] = $average;
        }

        asort($ranking);
    }

    return $ranking;
}


function calculateAggregatedRanking2($conn) {
    $ranking = [];
    $sql = "SELECT * FROM predictionsmarcatori";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            for ($i = 1; $i <= 5; $i++) {
                $player = $row["scorer$i"];
                if (!empty($player)) {
                    if (!isset($ranking[$player])) {
                        $ranking[$player] = [];
                    }
                    $ranking[$player][] = $i;
                }
            }
        }

        foreach ($ranking as $player => &$positions) {
            $average = array_sum($positions) / count($positions);
            $ranking[$player] = $average;
        }

        asort($ranking);  // Ordinamento per media posizione in ordine crescente
    }

    return $ranking;
}

?>
