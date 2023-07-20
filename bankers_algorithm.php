<?php

function isSafeState($available, $max, $allocation, $processes, $resources)
{
    $work = $available;
    $finish = array_fill(0, $processes, false);
    $sequence = [];

    $need = [];
    for ($p = 0; $p < $processes; $p++) {
        for ($r = 0; $r < $resources; $r++) {
            $need[$p][$r] = $max[$p][$r] - $allocation[$p][$r];
        }
    }

    $count = 0;
    while ($count < $processes) {
        $found = false;
        for ($p = 0; $p < $processes; $p++) {
            if (!$finish[$p] && isNeedLessOrEqual($need[$p], $work)) {
                for ($r = 0; $r < $resources; $r++) {
                    $work[$r] += $allocation[$p][$r];
                }
                $finish[$p] = true;
                $sequence[] = $p;
                $found = true;
                $count++;
            }
        }
        if (!$found) {
            break;
        }
    }

    return $count == $processes ? [$sequence, true] : [[], false];
}

function isNeedLessOrEqual($need, $work)
{
    $resources = count($need);
    for ($r = 0; $r < $resources; $r++) {
        if ($need[$r] > $work[$r]) {
            return false;
        }
    }
    return true;
}

function bankerAlgorithm($resources, $processes)
{
    $available = [];
    $max = [];
    $allocation = [];

    echo "Enter the number of available resources:\n";
    for ($r = 0; $r < $resources; $r++) {
        echo "Resource $r: ";
        $available[$r] = intval(trim(fgets(STDIN)));
    }

    echo "\nEnter the maximum resource allocation for each process:\n";
    for ($p = 0; $p < $processes; $p++) {
        echo "Process $p:\n";
        for ($r = 0; $r < $resources; $r++) {
            echo "Resource $r: ";
            $max[$p][$r] = intval(trim(fgets(STDIN)));
        }
    }

    echo "\nEnter the current resource allocation for each process:\n";
    for ($p = 0; $p < $processes; $p++) {
        echo "Process $p:\n";
        for ($r = 0; $r < $resources; $r++) {
            echo "Resource $r: ";
            $allocation[$p][$r] = intval(trim(fgets(STDIN)));
        }
    }

    $result = isSafeState($available, $max, $allocation, $processes, $resources);
    if ($result[1]) {
        echo "\nThe system is in a safe state.\n";
        echo "Safe sequence: " . implode(" -> ", $result[0]) . "\n";
    } else {
        echo "\nThe system is NOT in a safe state.\n";
    }
}

echo "Enter the number of resources: ";
$resources = intval(trim(fgets(STDIN)));
echo "Enter the number of processes: ";
$processes = intval(trim(fgets(STDIN)));

bankerAlgorithm($resources, $processes);
