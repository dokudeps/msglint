<?php

$data = $_REQUEST['msg'] ?? '';

$fds = [
    0 => ["pipe", "r"], // STDIN
    1 => ["pipe", "w"], // STDOUT
    2 => ["pipe", "w"], // STDERR
];

$p = proc_open('./msglint', $fds, $pipes);
fwrite($pipes[0], $data . PHP_EOL);
fclose($pipes[0]);
$return = stream_get_contents($pipes[1]);
$err = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$exit_code = proc_close($p);

header('Content-Type: text/plain; charset=utf-8');

foreach (explode("\n", $data) as $lno => $line) {
    echo str_pad($lno + 1, 3, ' ', STR_PAD_LEFT);
    echo "\t";
    echo $line;
    echo "\n";
}
echo "-----------\n";
echo $return;
echo "-----------\n";
echo $err; // mostly/always? empty
