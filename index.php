<?php

if(empty($_REQUEST)){
    echo '';
}
$param1 = $_REQUEST['param1'] ?? 1;
$param2 = $_REQUEST['param2'] ?? 2;

echo json_encode([
    'result' => intval($param1) * intval($param2),
]);
