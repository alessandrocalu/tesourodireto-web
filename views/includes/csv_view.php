<?php
$this->output->set_header('Content-Type: application/text; charset=utf-8');
$this->output->set_header('Content-Disposition: attachement; filename="' . $filename . '"');

$fp = fopen('php://output', 'w');
foreach($data_rows['data'] as $row) {
    fputcsv($fp, $row);
}

fclose($fp);