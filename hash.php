<?php
// tcc_plata-saude/hash_generator_final.php
$senha_para_hash = 'lanterna1';
$hash_seguro = password_hash($senha_para_hash, PASSWORD_BCRYPT);
echo $hash_seguro;
?>