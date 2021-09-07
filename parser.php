<?php

$pathToDirectory = __DIR__;

require_once("{$pathToDirectory}/classes/ApacheLogParser.php");
require_once("{$pathToDirectory}/classes/CalculateResults.php");

$calculateResults = new \CalculateResults();

if (!$argv[1]) {
    $result = [
        'result' => false,
        'message' => 'To few arguments'
    ];
} else {
    try {
        $apacheLogParser = new \ApacheLogParser($pathToDirectory . $argv[1]);
    } catch (Exception $e) {
        echo 'Exception: ', $e->getMessage(), "\n";
        exit();
    }
    $lines = $apacheLogParser->handle();
    foreach ($lines as $line) {
        $calculateResults->handle($line);
    }

    $result = $calculateResults->prepareResult();
}
echo json_encode($result);
unset($apacheLogParser);
unset($calculateResults);