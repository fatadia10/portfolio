<?php
function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur));
}

function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

function email_valide(string $email): bool {
    if (empty($email)) return false;
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function generer_csrf(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifier_csrf(string $token): bool {
    if (empty($_SESSION['csrf_token'])) return false;
    return hash_equals($_SESSION['csrf_token'], $token);
}

function enregistrer_visite(PDO $pdo, string $page): void {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
    $stmt = $pdo->prepare("INSERT INTO visites (adresse_ip, page) VALUES (?, ?)");
    $stmt->execute([$ip, $page]);
}
?>