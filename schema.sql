CREATE DATABASE IF NOT EXISTS wevote_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wevote_php;

CREATE TABLE IF NOT EXISTS users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 identifier VARCHAR(80) NOT NULL UNIQUE,
 full_name VARCHAR(150) NOT NULL,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('student','employee','admin','superadmin') NOT NULL DEFAULT 'student',
 has_voted TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS candidates (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 candidate_number INT NOT NULL DEFAULT 1,
 name VARCHAR(150) NOT NULL,
 photo VARCHAR(255) NOT NULL DEFAULT '',
 vision TEXT NOT NULL,
 mission TEXT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS votes (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL UNIQUE,
 candidate_id BIGINT UNSIGNED NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_votes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_votes_candidate FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
 INDEX idx_votes_candidate (candidate_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS settings (
 setting_key VARCHAR(100) PRIMARY KEY,
 setting_value TEXT NOT NULL
) ENGINE=InnoDB;

INSERT INTO settings(setting_key, setting_value) VALUES
 ('school_name','SMKN 31 BANDUNG'),
 ('school_logo',''),
 ('election_type','Ketua OSIS'),
 ('election_location','Aula Sekolah'),
 ('start_at','2030-01-01 08:00:00'),
 ('duration_minutes','120'),
 ('background_1','assets/bg-school.svg'),
 ('background_2','assets/bg-vote.svg'),
 ('background_3','assets/bg-community.svg'),
 ('active_background','1'),
 ('school_vision','Mewujudkan lingkungan sekolah yang unggul, inklusif, dan berkarakter.'),
 ('school_mission','Mendorong kolaborasi, kreativitas, dan kepemimpinan siswa.'),
 ('about_text','WeVote adalah aplikasi pemungutan suara digital untuk mendukung pemilihan sekolah yang tertib dan transparan.'),
 ('contact_text','Hubungi pengelola aplikasi melalui pihak sekolah.'),
 ('results_public','1')
ON DUPLICATE KEY UPDATE setting_key=VALUES(setting_key);

INSERT INTO candidates(candidate_number,name,photo,vision,mission) VALUES
 (1,'Wawan','assets/candidate-placeholder.svg','Membangun OSIS yang aktif dan terbuka.','Mengadakan kegiatan siswa yang kreatif; menampung aspirasi siswa; memperkuat kolaborasi antarkelas.'),
 (2,'Toto','assets/candidate-placeholder.svg','Menjadikan sekolah nyaman, berprestasi, dan inklusif.','Mendorong kegiatan akademik dan nonakademik; memperluas kegiatan sosial; meningkatkan komunikasi siswa.'),
 (3,'Wanto','assets/candidate-placeholder.svg','Mewujudkan organisasi siswa yang disiplin dan inovatif.','Mengembangkan program lingkungan; mengadakan forum aspirasi; mendukung kreativitas siswa.')
ON DUPLICATE KEY UPDATE name=VALUES(name);
