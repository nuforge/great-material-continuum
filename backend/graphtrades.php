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
        INNER JOIN inventory h ON u1.id = h.user_id
        INNER JOIN wishlist w ON u1.id = w.user_id
        LEFT JOIN cards c1 ON h.card_id = c1.id
        LEFT JOIN cards c2 ON w.card_id = c2.id";
    $result = $db->query($query);

    $graph = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $userId = $row['user_id'];
        if (!isset($graph[$userId])) {
            $graph[$userId] = [
                'user_id' => $row['user_id'],
                'user_name' => $row['user_name'],
                'inventory' => [],
                'wishlist' => []
            ];
        }
        $graph[$userId]['inventory'][] = [
            'card_id' => $row['have'],
            'card_name' => $row['have_name']
        ];
        $graph[$userId]['wishlist'][] = [
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
    $dfs = function($node, $path) use (&$dfs, &$visited, &$stack, &$cycles, $graph) {
        $nodeId = $node['card_id'];
        if (in_array($nodeId, array_column($stack, 'card_id'))) {
            $cycle = array_slice($stack, array_search($nodeId, array_column($stack, 'card_id')));
            $cycles[] = $cycle;
            return;
        }
        if (isset($visited[$nodeId])) return;
        $visited[$nodeId] = true;
        $stack[] = $node;

        foreach ($graph as $userId => $data) {
            foreach ($data['inventory'] as $have) {
                if ($have['card_id'] === $nodeId) {
                    foreach ($data['wishlist'] as $want) {
                        // Skip if the user is trading with themselves or already has the card
                        if ($userId == $node['user']['user_id']) continue;
                        $dfs([
                            'card_id' => $want['card_id'],
                            'user' => [
                                'user_id' => $userId,
                                'user_name' => $data['user_name']
                            ],
                            'card' => [
                                'card_id' => $want['card_id'],
                                'card_name' => $want['card_name']
                            ]
                        ], $path);
                    }
                }
            }
        }

        array_pop($stack);
    };

    foreach ($graph as $userId => $data) {
        foreach ($data['inventory'] as $have) {
            $dfs([
                'card_id' => $have['card_id'],
                'user' => [
                    'user_id' => $userId,
                    'user_name' => $data['user_name']
                ],
                'card' => [
                    'card_id' => $have['card_id'],
                    'card_name' => $have['card_name']
                ]
            ], []);
        }
    }

    return $cycles;
}

// Main trade generation logic
function generateTrades($db) {
    $graph = loadGraph($db);
    $cycles = findCycles($graph);
    header('Content-Type: application/json');
    $trades = [];

    foreach ($cycles as $cycle) {
        $trade = [];
        for ($i = 0; $i < count($cycle); $i++) {
            $from = $cycle[$i];
            $to = $cycle[($i + 1) % count($cycle)];
            $item = null;

            foreach ($graph as $userId => $data) {
                foreach ($data['inventory'] as $have) {
                    if ($have['card_id'] == $from) {
                        foreach ($data['wishlist'] as $want) {
                            if ($want['card_id'] == $to) {
                                $item = $have['card_name'];
                                break 2;
                            }
                        }
                    }
                }
            }

            $trade[] = [
                'from' => $from,
                'to' => $to,
            ];
        }
        $trades[] = $trade;
    }

    return json_encode($trades, JSON_PRETTY_PRINT);
}
