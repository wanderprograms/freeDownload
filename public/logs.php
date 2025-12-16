<?php
$logFile = __DIR__ . "/downloads/log.txt";
if (file_exists($logFile)) {
    echo "<pre style='font-family:monospace;'>";
    echo htmlspecialchars(file_get_contents($logFile));
    echo "</pre>";
} else {
    echo "Palibe mbiri ya ma download.";
}
?>

