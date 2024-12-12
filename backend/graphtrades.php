<?php

// Load graph data from database
function loadGraph($db) {
    $query = "
        SELECT u1.id AS user_id, 
        u1.name AS user_name, 
        h.card_id AS have, 
        w.card_id AS want, 
        c1.card_name AS have_name, 
        c2.card_name AS want_name
        FROM users u1
        INNER JOIN haves h ON u1.id = h.user_id
        INNER JOIN wants w ON u1.id = w.user_id
        LEFT JOIN cards c1 ON h.card_id = c1.id
        LEFT JOIN cards c2 ON w.card_id = c2.id
        GROUP BY h.card_id, w.card_id";
    $result = $db->query($query);

    $graph = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $userId = $row['user_id'];
        if (!isset($graph[$userId])) {
            $graph[$userId] = [
                'user_id' => $row['user_id'],
                'user_name' => $row['user_name'],
                'haves' => [],
                'wants' => []
            ];
        }
        $graph[$userId]['haves'][] = [
            'card_id' => $row['have'],
            'card_name' => $row['have_name']
        ];
        $graph[$userId]['wants'][] = [
            'card_id' => $row['want'],
            'card_name' => $row['want_name']
        ];
    }
    return $graph;
}

// Function to detect cycles (multi-way trades)
function findCycles($graph) {
    $visited = [];
    $stack = [];
    $cycles = [];
    $dfs = function($cardId, $path) use (&$dfs, &$visited, &$stack, &$cycles, $graph) {
        if (in_array($cardId, $stack)) {
            $cycle = array_slice($stack, array_search($cardId, $stack));
            $cycles[] = $cycle;
            return;
        }
        if (isset($visited[$cardId])) return;
        $visited[$cardId] = true;
        $stack[] = $cardId;

        foreach ($graph as $userId => $data) {
            foreach ($data['haves'] as $have) {
                if ($have['card_id'] === $cardId) {
                    foreach ($data['wants'] as $want) {
                        $dfs($want['card_id'], $path);
                    }
                }
            }
        }

        array_pop($stack);
    };

    foreach ($graph as $userId => $data) {
        foreach ($data['haves'] as $have) {
            $dfs($have['card_id'], []);
        }
    }

    return $cycles;
}

// Main trade generation logic
function generateTrades($db) {
    $graph = loadGraph($db);
    $cycles = findCycles($graph);
    header('Content-Type: application/json');
    $trades = json_encode($graph, JSON_PRETTY_PRINT);

    /*
    $trades = [];
    foreach ($cycles as $cycle) {
        $trade = [];
        for ($i = 0; $i < count($cycle); $i++) {
            $from = $cycle[$i];
            $to = $cycle[($i + 1) % count($cycle)];
            $item = null;

            foreach ($graph[$from] as $edge) {
                if ($edge['wants'] == $to) {
                    $item = $edge['haves'];
                    break;
                }
            }

            $trade[] = [
                'from' => $from,
                'to' => $to,
                'item' => $item,
            ];
        }
        $trades[] = $trade;
    }*/

    return $trades;
}
