<?php

$missionFile = "mission.json";
$debugLog = "debug_ping.log";

$raw = trim(file_get_contents("php://input"));

if (!$raw) {
    echo json_encode(["error" => "empty command"]);
    exit;
}

// Generate 8-char SEQID
$seq = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);

// Format: SEQID8:COMMAND
$missionLine = $seq . ":" . $raw;

// Write mission.json (overwrite - single command mode)
file_put_contents($missionFile, $missionLine);

// WAIT for Android ACK (max 10 seconds)
$start = time();
$confirmed = false;

while (time() - $start < 10) {
    if (file_exists($debugLog)) {
        $log = file_get_contents($debugLog);
        // Look for the SEQ ID in the log (Android sent reply)
        if (strpos($log, $seq) !== false) {
            $confirmed = true;
            break;
        }
    }
    usleep(200000); // Wait 0.2 seconds
}

if ($confirmed) {
    echo json_encode([
        "status" => "confirmed",
        "seq" => $seq
    ]);
} else {
    echo json_encode([
        "status" => "timeout",
        "seq" => $seq
    ]);
}