<?php
function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur));
}
function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}
function email_valide(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
?>