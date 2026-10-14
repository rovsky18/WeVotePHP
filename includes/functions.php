<?php
require_once __DIR__ . '/../config.php';
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function setting(string $key, $default = '') {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try { foreach (db()->query('SELECT setting_key, setting_value FROM settings') as $row) $cache[$row['setting_key']] = $row['setting_value']; }
        catch (Throwable $t) { return $default; }
    }
    return $cache[$key] ?? $default;
}
function save_setting(string $key, string $value): void {
    $q = db()->prepare('INSERT INTO settings(setting_key, setting_value) VALUES(?, ?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');
    $q->execute([$key, $value]);
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function is_admin(): bool { return current_user() && in_array(current_user()['role'], ['admin','superadmin'], true); }
function require_admin(): void { if (!is_admin()) { header('Location: index.php?page=admin-login'); exit; } }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(): void { if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', (string)$_POST['csrf'])) { http_response_code(403); exit('Permintaan tidak valid. Silakan kembali dan coba lagi.'); } }
function flash(string $message, string $type='success'): void { $_SESSION['flash'] = ['message'=>$message,'type'=>$type]; }
function get_flash(): ?array { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function election_status(): string {
    $start = strtotime((string)setting('start_at', date('Y-m-d H:i:s')));
    $duration = max(1, (int)setting('duration_minutes', '60')) * 60;
    $now = time();
    if ($now < $start) return 'upcoming';
    if ($now < $start + $duration) return 'active';
    return 'finished';
}
function has_voted(int $userId): bool { $q=db()->prepare('SELECT id FROM votes WHERE user_id=? LIMIT 1'); $q->execute([$userId]); return (bool)$q->fetch(); }
function redirect(string $url): never { header('Location: '.$url); exit; }
function upload_image(string $field, string $prefix): ?string {
    if (empty($_FILES[$field]['name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) return null;
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK || $_FILES[$field]['size'] > 5*1024*1024) throw new RuntimeException('File gagal diunggah atau ukurannya melebihi 5 MB.');
    $info = getimagesize($_FILES[$field]['tmp_name']);
    if (!$info || !in_array($info['mime'], ['image/jpeg','image/png','image/webp'], true)) throw new RuntimeException('Gunakan gambar JPG, PNG, atau WebP.');
    $ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$info['mime']];
    $name = $prefix . '-' . bin2hex(random_bytes(8)) . '.' . $ext;
    $dir = __DIR__ . '/../assets/uploads';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dir.'/'.$name)) throw new RuntimeException('Tidak dapat menyimpan gambar. Periksa izin folder uploads.');
    return 'assets/uploads/'.$name;
}
function image_url(?string $path): string { $path = trim((string)$path); return $path !== '' ? $path : 'assets/candidate-placeholder.svg'; }
