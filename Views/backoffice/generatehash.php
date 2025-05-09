<?php
$mdp = 'kharamant';
$hash = password_hash($mdp, PASSWORD_DEFAULT);
echo "Nouveau hash : $hash";
// --- UPDATE user SET mdp = 'TON_NOUVEAU_HASH_GENERE' WHERE email = 'azerty@azert.com'; ---
?>
 