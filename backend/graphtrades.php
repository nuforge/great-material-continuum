<?php

// Load graph data from database
function loadGraph($db) {
    $query = "
        SELECT u1.id AS user_id, h.id AS have, w.id AS want
        FROM users u1
        JOIN haves h ON u1.id = h.user_id
        JOIN wants w ON u1.id = w.user_id";
    $result = $db->query($query);

    $graph = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $graph[$row['user_id']][] = [
            'have' => $row['have'],
            'want' => $row['want'],
        ];
    }
    return $graph;
}

// Function to detect cycles (multi-way trades)
function findCycles($graph) {
    $visited = [];
    $stack = [];
    $cycles = [];

    $dfs = function($node, $path) use (&$dfs, &$visited, &$stack, &$cycles, $graph) {
        if (in_array($node, $stack)) {
            $cycle = array_slice($stack, array_search($node, $stack));
            $cycles[] = $cycle;
            return;
        }
        if (isset($visited[$node])) return;
        $visited[$node] = true;
        $stack[] = $node;

        foreach ($graph[$node] ?? [] as $edge) {
            $dfs($edge['want'], $path);
        }

        array_pop($stack);
    };

    foreach (array_keys($graph) as $node) {
        $dfs($node, []);
    }

    return $cycles;
}

// Main trade generation logic
function generateTrades($db) {
    $graph = loadGraph($db);
    $cycles = findCycles($graph);

    $trades = [];
    foreach ($cycles as $cycle) {
        $trade = [];
        for ($i = 0; $i < count($cycle); $i++) {
            $from = $cycle[$i];
            $to = $cycle[($i + 1) % count($cycle)];
            $item = null;

            foreach ($graph[$from] as $edge) {
                if ($edge['want'] == $to) {
                    $item = $edge['have'];
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
    }

    return $trades;
}
