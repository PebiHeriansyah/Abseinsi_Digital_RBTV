-- ============================================================
-- SQL Schema untuk Absensi RBTV di Neon.tech
-- Paste seluruh SQL ini ke Neon SQL Editor
-- URL: https://console.neon.tech -> SQL Editor
-- ============================================================

-- Users & Sessions
CREATE TABLE IF NOT EXISTS "users" (
    "id" bigserial PRIMARY KEY,
    "name" varchar(255) NOT NULL,
    "email" varchar(255) NOT NULL UNIQUE,
    "email_verified_at" timestamp NULL,
    "password" varchar(255) NOT NULL,
    "remember_token" varchar(100) NULL,
    "created_at" timestamp NULL,
    "updated_at" timestamp NULL
);

CREATE TABLE IF NOT EXISTS "password_reset_tokens" (
    "email" varchar(255) PRIMARY KEY,
    "token" varchar(255) NOT NULL,
    "created_at" timestamp NULL
);

CREATE TABLE IF NOT EXISTS "sessions" (
    "id" varchar(255) PRIMARY KEY,
    "user_id" bigint NULL,
    "ip_address" varchar(45) NULL,
    "user_agent" text NULL,
    "payload" text NOT NULL,
    "last_activity" integer NOT NULL
);
CREATE INDEX IF NOT EXISTS "sessions_user_id_index" ON "sessions" ("user_id");
CREATE INDEX IF NOT EXISTS "sessions_last_activity_index" ON "sessions" ("last_activity");

-- Cache
CREATE TABLE IF NOT EXISTS "cache" (
    "key" varchar(255) PRIMARY KEY,
    "value" text NOT NULL,
    "expiration" bigint NOT NULL
);
CREATE INDEX IF NOT EXISTS "cache_expiration_index" ON "cache" ("expiration");

CREATE TABLE IF NOT EXISTS "cache_locks" (
    "key" varchar(255) PRIMARY KEY,
    "owner" varchar(255) NOT NULL,
    "expiration" bigint NOT NULL
);
CREATE INDEX IF NOT EXISTS "cache_locks_expiration_index" ON "cache_locks" ("expiration");

-- Jobs
CREATE TABLE IF NOT EXISTS "jobs" (
    "id" bigserial PRIMARY KEY,
    "queue" varchar(255) NOT NULL,
    "payload" text NOT NULL,
    "attempts" smallint NOT NULL,
    "reserved_at" integer NULL,
    "available_at" integer NOT NULL,
    "created_at" integer NOT NULL
);
CREATE INDEX IF NOT EXISTS "jobs_queue_index" ON "jobs" ("queue");

CREATE TABLE IF NOT EXISTS "job_batches" (
    "id" varchar(255) PRIMARY KEY,
    "name" varchar(255) NOT NULL,
    "total_jobs" integer NOT NULL,
    "pending_jobs" integer NOT NULL,
    "failed_jobs" integer NOT NULL,
    "failed_job_ids" text NOT NULL,
    "options" text NULL,
    "cancelled_at" integer NULL,
    "created_at" integer NOT NULL,
    "finished_at" integer NULL
);

CREATE TABLE IF NOT EXISTS "failed_jobs" (
    "id" bigserial PRIMARY KEY,
    "uuid" varchar(255) NOT NULL UNIQUE,
    "connection" text NOT NULL,
    "queue" text NOT NULL,
    "payload" text NOT NULL,
    "exception" text NOT NULL,
    "failed_at" timestamp NOT NULL DEFAULT NOW()
);

-- Karyawans
CREATE TABLE IF NOT EXISTS "karyawans" (
    "id" bigserial PRIMARY KEY,
    "nik" varchar(16) NOT NULL UNIQUE,
    "nama_depan" varchar(255) NOT NULL,
    "nama_belakang" varchar(255) NOT NULL,
    "jenis_kelamin" varchar(10) NOT NULL CHECK ("jenis_kelamin" IN ('Laki-laki', 'Perempuan')),
    "email" varchar(255) NOT NULL UNIQUE,
    "no_hp" varchar(255) NOT NULL,
    "status" varchar(20) NOT NULL CHECK ("status" IN ('Karyawan Tetap', 'Magang')),
    "alamat" text NOT NULL,
    "foto" varchar(255) NULL,
    "qr_code" text NULL,
    "created_at" timestamp NULL,
    "updated_at" timestamp NULL
);

-- Absensis
CREATE TABLE IF NOT EXISTS "absensis" (
    "id" bigserial PRIMARY KEY,
    "karyawan_id" bigint NOT NULL REFERENCES "karyawans"("id") ON DELETE CASCADE,
    "tanggal" date NOT NULL,
    "jam_masuk" time NULL,
    "jam_keluar" time NULL,
    "latitude" decimal(10,7) NULL,
    "longitude" decimal(10,7) NULL,
    "status" varchar(255) NULL,
    "created_at" timestamp NULL,
    "updated_at" timestamp NULL
);

-- Lokasis
CREATE TABLE IF NOT EXISTS "lokasis" (
    "id" bigserial PRIMARY KEY,
    "nama_lokasi" varchar(255) NOT NULL,
    "latitude" decimal(10,7) NOT NULL,
    "longitude" decimal(10,7) NOT NULL,
    "radius" integer NOT NULL,
    "created_at" timestamp NULL,
    "updated_at" timestamp NULL
);

-- Log Absensis
CREATE TABLE IF NOT EXISTS "log_absensis" (
    "id" bigserial PRIMARY KEY,
    "nik" varchar(255) NOT NULL,
    "status" varchar(255) NOT NULL,
    "keterangan" text NULL,
    "created_at" timestamp NULL,
    "updated_at" timestamp NULL
);

-- Laravel Migrations Table
CREATE TABLE IF NOT EXISTS "migrations" (
    "id" serial PRIMARY KEY,
    "migration" varchar(255) NOT NULL,
    "batch" integer NOT NULL
);

-- Insert migration records
INSERT INTO "migrations" ("migration", "batch") VALUES
    ('0001_01_01_000000_create_users_table', 1),
    ('0001_01_01_000001_create_cache_table', 1),
    ('0001_01_01_000002_create_jobs_table', 1),
    ('2026_04_02_181159_create_karyawans_table', 1),
    ('2026_04_02_181215_create_absensis_table', 1),
    ('2026_04_02_181225_create_lokasis_table', 1),
    ('2026_04_02_181235_create_log_absensis_table', 1),
    ('2026_04_05_174816_change_qr_code_column', 1)
ON CONFLICT DO NOTHING;

-- Admin User (password: Admin@RBTV2024)
INSERT INTO "users" ("name", "email", "password", "created_at", "updated_at")
VALUES (
    'Admin RBTV',
    'admin@rbtv.com',
    '$2y$12$4x5APDGJI81PDCgfB6xh3.HeALmm7L0WAAolXBJLDInJijxp5aScq',
    NOW(),
    NOW()
) ON CONFLICT (email) DO NOTHING;

-- Lokasi Kantor RBTV
INSERT INTO "lokasis" ("nama_lokasi", "latitude", "longitude", "radius", "created_at", "updated_at")
VALUES (
    'Kantor RBTV',
    -6.914744,
    107.609810,
    100,
    NOW(),
    NOW()
) ON CONFLICT DO NOTHING;
