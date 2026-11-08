<?php

$missionFile = "mission.json";
$stateFile   = "state.json";
$logFile     = "completed.log";
$streamFile  = "telemetry_stream.log";

$rawInput = trim(file_get_contents("php://input"));

if (!$rawInput) {
    echo "NO_INPUT";
    exit;
}

// Load current state.json
$state = [];

if (file_exists($stateFile)) {
    $decoded = json_decode(file_get_contents($stateFile), true);
    if (is_array($decoded)) {
        $state = $decoded;
    }
}

/*
|--------------------------------------------------------------------------
| CASE 1: TELEMETRY JSON FROM APP
|--------------------------------------------------------------------------
*/
if (strpos($rawInput, "{") === 0) {

    $json = json_decode($rawInput, true);

    if (is_array($json)) {

        foreach ($json as $k => $v) {
            $state[$k] = $v;
        }

        file_put_contents($stateFile, json_encode($state, JSON_PRETTY_PRINT));

        // ?? Append live telemetry stream
        $lat     = isset($state["lat"]) ? round($state["lat"], 7) : "0";
        $lon     = isset($state["lon"]) ? round($state["lon"], 7) : "0";
        $battery = isset($state["battery"]) ? $state["battery"] : "0";

        file_put_contents(
            $streamFile,
            date("H:i:s") .
            " LAT=" . $lat .
            " LON=" . $lon .
            " BATT=" . $battery . PHP_EOL,
            FILE_APPEND
        );

        echo "OK";
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| CASE 2: SEQID8:STR REPLY FROM APP
|--------------------------------------------------------------------------
*/
if (strpos($rawInput, ":") !== false) {

    list($returnedSeq, $returnedStr) = explode(":", $rawInput, 2);

    $returnedSeq = trim($returnedSeq);
    $returnedStr = trim($returnedStr);

    if (strlen($returnedSeq) === 8) {

        // Update state.json
        $state["last_seq"]   = $returnedSeq;
        $state["last_reply"] = $returnedStr;

        file_put_contents($stateFile, json_encode($state, JSON_PRETTY_PRINT));

        // ?? Append to telemetry stream
        file_put_contents(
            $streamFile,
            date("H:i:s") .
            " REPLY SEQ=" . $returnedSeq .
            " MSG=" . $returnedStr . PHP_EOL,
            FILE_APPEND
        );

        // Interlock check
        if (file_exists($missionFile)) {

            $mission = trim(file_get_contents($missionFile));

            if (strpos($mission, ":") !== false) {

                list($currentSeq,) = explode(":", $mission, 2);
                $currentSeq = trim($currentSeq);

                if ($returnedSeq === $currentSeq) {

                    file_put_contents(
                        $logFile,
                        date("Y-m-d H:i:s") .
                        " COMPLETED: " . $rawInput . PHP_EOL,
                        FILE_APPEND
                    );

                    // Also log completion to live stream
                    file_put_contents(
                        $streamFile,
                        date("H:i:s") .
                        " COMPLETED: " . $rawInput . PHP_EOL,
                        FILE_APPEND
                    );

                    // Clear mission so it doesn't re-run
                    file_put_contents($missionFile, "");
                }
            }
        }
	file_put_contents("debug_ping.log", date("H:i:s") . " RAW=" . $rawInput . PHP_EOL, FILE_APPEND);
        echo "OK";
        exit;
    }
}

echo "INVALID_FORMAT";
exit;