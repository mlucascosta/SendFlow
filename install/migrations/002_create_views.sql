-- Compatibilidade: MariaDB/MySQL | Compatibility: MariaDB/MySQL
-- Migration 002: Criar views de consulta | Create reporting views

-- View `vw_usuarios_ativos_com_sessoes` (MySQL/MariaDB) | View `vw_usuarios_ativos_com_sessoes` (MySQL/MariaDB)
CREATE OR REPLACE VIEW vw_usuarios_ativos_com_sessoes AS
SELECT
    u.id,
    u.name,
    u.email,
    u.role,
    COUNT(s.id) AS active_sessions
FROM users u
LEFT JOIN sessions s ON s.user_id = u.id AND s.is_active = 1
WHERE u.is_active = 1 AND u.deleted_at IS NULL
GROUP BY u.id, u.name, u.email, u.role;

-- View `vw_estatisticas_envios_hoje` (MySQL/MariaDB) | View `vw_estatisticas_envios_hoje` (MySQL/MariaDB)
CREATE OR REPLACE VIEW vw_estatisticas_envios_hoje AS
SELECT
    DATE(created_at) AS ref_date,
    SUM(CASE WHEN direction = 'outbound' THEN 1 ELSE 0 END) AS outbound_total,
    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) AS sent_total,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) AS failed_total,
    SUM(opens) AS opens_total,
    SUM(clicks) AS clicks_total
FROM emails
WHERE DATE(created_at) = CURDATE()
GROUP BY DATE(created_at);

-- View `vw_sessoes_ativas_detalhadas` (MySQL/MariaDB) | View `vw_sessoes_ativas_detalhadas` (MySQL/MariaDB)
CREATE OR REPLACE VIEW vw_sessoes_ativas_detalhadas AS
SELECT
    s.id AS session_id,
    s.user_id,
    u.name,
    u.email,
    s.ip_address,
    s.user_agent,
    FROM_UNIXTIME(s.last_activity) AS last_activity_at,
    s.created_at,
    s.expires_at
FROM sessions s
INNER JOIN users u ON u.id = s.user_id
WHERE s.is_active = 1;

-- View `vw_email_summary_by_status` (MySQL/MariaDB) | View `vw_email_summary_by_status` (MySQL/MariaDB)
CREATE OR REPLACE VIEW vw_email_summary_by_status AS
SELECT
    user_id,
    status,
    direction,
    COUNT(*) AS total
FROM emails
GROUP BY user_id, status, direction;

-- View `vw_security_alerts` (MySQL/MariaDB) | View `vw_security_alerts` (MySQL/MariaDB)
CREATE OR REPLACE VIEW vw_security_alerts AS
SELECT
    f.email,
    f.ip_address,
    COUNT(*) AS attempts,
    MIN(f.attempted_at) AS first_attempt,
    MAX(f.attempted_at) AS last_attempt
FROM failed_logins f
WHERE f.attempted_at >= (NOW() - INTERVAL 15 MINUTE)
GROUP BY f.email, f.ip_address
HAVING COUNT(*) >= 5;
