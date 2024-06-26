<?php

$filename = __DIR__ . '/data.json';

$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT);
$id = $_GET['id'] ?? '';

if ($id) :
    $data = file_get_contents($filename);
    $listes  = json_decode($data, true) ?? [];

    //cherche la première clé/index des tableaux : 
    //array_search (mixte $aiguille, array $botte de foin)...
    //$aiguille = 'id'.
    //array $botte de foin = le la colomne des 'id'.
    $index = array_search($id, array_column($listes, 'id')); // si 3 tableaux l'index va de 0 à 2.
    $listes[$index]['tacheAchevee'] = !$listes[$index]['tacheAchevee'];
    file_put_contents($filename, json_encode($listes));

endif;

header('Location: ' . 'index.php');
exit;
