<?php
require_once __DIR__.'/config.php';
$pdo = db();
$accounts = [
 ['admin','Administrator WeVote','Admin123!','admin'],
 ['superadmin','Superadmin WeVote','SuperAdmin123!','superadmin'],
 ['2026001','Contoh Siswa','Siswa123!','student'],
 ['1987001','Contoh Pegawai','Pegawai123!','employee'],
];
$up = $pdo->prepare('INSERT INTO users(identifier,full_name,password_hash,role) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), role=VALUES(role)');
foreach ($accounts as [$id,$name,$password,$role]) $up->execute([$id,$name,password_hash($password, PASSWORD_DEFAULT),$role]);
echo "Seed selesai. Akun demo:\nadmin / Admin123!\nsuperadmin / SuperAdmin123!\n2026001 / Siswa123!\n1987001 / Pegawai123!\nSegera ganti password demo setelah login.\n";
