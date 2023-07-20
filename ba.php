<?php

// Function to check if a process can be allocated resources
function isSafe($allocation, $max, $need, $available, $processes, $resources)
{
    $work = $available;
    $finish = array_fill(0, $processes, false);

    // Array to store the sequence of processes
    $safeSequence = array();

    // Loop through all processes until all are finished
    while (true) {
        $safe = false;

        for ($i = 0; $i < $processes; $i++) {
            // Check if the process is not finished and its resource needs can be satisfied
            if (!$finish[$i] && isNeedLessOrEqual($need[$i], $work, $resources)) {
                // Allocate the resources to the process
                allocateResources($work, $allocation[$i]);
                // Mark the process as finished
                $finish[$i] = true;
                // Add the process to the safe sequence
                $safeSequence[] = $i;
                $safe = true;
            }
        }

        // If no process can be allocated resources, break the loop
        if (!$safe)
            break;
    }

    // Check if all processes are finished
    for ($i = 0; $i < $processes; $i++) {
        if (!$finish[$i])
            return false;
    }

    // Print the safe sequence
    echo "Safe sequence: ";
    foreach ($safeSequence as $process)
        echo "P" . $process . " ";

    return true;
}

// Function to check if process's need is less than or equal to available resources
function isNeedLessOrEqual($need, $work, $resources)
{
    for ($i = 0; $i < $resources; $i++) {
        if (!isset($need[$i]) || $need[$i] > $work[$i]) {
            return false;
        }
    }
    return true;
}

// Function to allocate resources to a process
function allocateResources(&$work, $allocation)
{
    for ($i = 0; $i < count($allocation); $i++) {
        $work[$i] += $allocation[$i];
    }
}

// Get the number of resources and processes from the user
$resources = (int) readline("Enter the number of resources: ");
$processes = (int) readline("Enter the number of processes: ");

// Initialize arrays to store allocation, maximum, need, and available resources
$allocation = array();
$max = array();
$need = array();
$available = array();

// Get the allocation matrix from the user
echo "Enter the allocation matrix:\n";
for ($i = 0; $i < $processes; $i++) {
    $allocation[$i] = array_map('intval', explode(" ", readline("P" . $i . ": ")));
    if (count($allocation[$i]) !== $resources) {
        echo "Error: The number of resources entered does not match the specified number of resources.\n";
        exit(1);
    }
}

// Get the maximum matrix from the user
echo "Enter the maximum matrix:\n";
for ($i = 0; $i < $processes; $i++) {
    $max[$i] = array_map('intval', explode(" ", readline("P" . $i . ": ")));
    if (count($max[$i]) !== $resources) {
        echo "Error: The number of resources entered does not match the specified number of resources.\n";
        exit(1);
    }
}

// Calculate the need matrix
for ($i = 0; $i < $processes; $i++) {
    for ($j = 0; $j < $resources; $j++) {
        if (!isset($need[$i])) {
            $need[$i] = array();
        }
        $need[$i][$j] = $max[$i][$j] - $allocation[$i][$j];
    }
}

// Get the available resources from the user
echo "Enter the available resources:\n";
$available = array_map('intval', explode(" ", readline()));

// Check if the system is in a safe state
if (isSafe($allocation, $max, $need, $available, $processes, $resources)) {
    echo "\nThe system is in a safe state.\n";
} else {
    echo "\nThe system is NOT in a safe state.\n";
}

?>