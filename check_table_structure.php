<?php
require 'vendor/autoload.php';

$db = \Config\Database::connect();
echo "Kolom-kolom di tabel 'pembeli':\n";
echo "================================\n";

$fields = $db->getFieldData('pembeli');
foreach($fields as $f) {
    echo $f->name . ' (' . $f->type . ')' . "\n";
}
