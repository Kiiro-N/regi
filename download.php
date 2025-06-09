<?php
        $filename = "output.csv";
        $filepath = "/public_html/regi/output.csv";
        
        header('Content-Type: application/octet-stream');
        header('Content-Length: '.filesize($filename));
        header('Content-Disposition: attachment; filename=log.csv');
        
        readfile($filepath);
        unlink("log.csv");
        unlink("output.csv");
        exit;
?>