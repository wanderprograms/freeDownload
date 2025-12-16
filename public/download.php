<?php
header('Cache-Control: no-cache');

if (isset($_GET['videoUrl'])) {
    header('Content-Type: text/event-stream');

    $videoUrl = escapeshellarg($_GET['videoUrl']);
    $ytDlpPath = "yt-dlp"; // Render imayika yt-dlp kudzera pip

    $cmd = "$ytDlpPath --newline -o \"downloads/%(title)s.%(ext)s\" $videoUrl";
    $descriptorspec = [1 => ["pipe", "w"], 2 => ["pipe", "w"]];
    $process = proc_open($cmd, $descriptorspec, $pipes);

    if (is_resource($process)) {
        $latestFile = "";
        while ($line = fgets($pipes[1])) {
            if (preg_match('/Destination: (.+)/', $line, $matches)) {
                $title = basename($matches[1]);
                echo "data: " . json_encode(["title" => $title]) . "\n\n";
                ob_flush(); flush();
                $latestFile = basename($matches[1]);
            }
            if (preg_match('/\s(\d+\.\d)%/', $line, $matches)) {
                $percent = (int)$matches[1];
                echo "data: " . json_encode(["percent" => $percent]) . "\n\n";
                ob_flush(); flush();
            }
        }
        fclose($pipes[1]); fclose($pipes[2]);
        proc_close($process);

        if ($latestFile) {
            echo "data: " . json_encode(["done" => $latestFile]) . "\n\n";
            ob_flush(); flush();
        }
    }
    exit;
}

if (isset($_GET['getFile'])) {
    $file = __DIR__ . "/downloads/" . basename($_GET['getFile']);
    if (file_exists($file)) {
        // Tumiza file ku browser
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);

        // Auto-delete ikangomaliza kutumiza
        unlink($file);
        exit;
    } else {
        echo "❌ File sinapezeke.";
    }
}
?>

