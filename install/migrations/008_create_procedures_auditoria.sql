-- Compatibilidade: MariaDB/MySQL | Compatibility: MariaDB/MySQL
-- Migration 008: Procedures de auditoria | Audit procedures

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_auditoria_por_usuario $$
-- Procedure `sp_auditoria_por_usuario` (MariaDB/MySQL) | Procedure `sp_auditoria_por_usuario` (MariaDB/MySQL)
CREATE PROCEDURE sp_auditoria_por_usuario(
    IN p_user_id INT,
    IN p_dias INT,
    IN p_limit_rows INT
)
BEGIN
    SELECT *
    FROM audit_logs
    WHERE user_id = p_user_id
      AND created_at >= (NOW() - INTERVAL p_dias DAY)
    ORDER BY created_at DESC
    LIMIT p_limit_rows;
END $$

DROP PROCEDURE IF EXISTS sp_auditoria_sessoes_ativas $$
-- Procedure `sp_auditoria_sessoes_ativas` (MariaDB/MySQL) | Procedure `sp_auditoria_sessoes_ativas` (MariaDB/MySQL)
CREATE PROCEDURE sp_auditoria_sessoes_ativas()
BEGIN
    SELECT * FROM vw_sessoes_ativas_detalhadas;
END $$

DROP PROCEDURE IF EXISTS sp_auditoria_estatisticas_envios $$
-- Procedure `sp_auditoria_estatisticas_envios` (MariaDB/MySQL) | Procedure `sp_auditoria_estatisticas_envios` (MariaDB/MySQL)
CREATE PROCEDURE sp_auditoria_estatisticas_envios(
    IN p_user_id INT,
    IN p_periodo_dias INT
)
BEGIN
    SELECT
        DATE(created_at) AS ref_date,
        COUNT(*) AS total,
        SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) AS sent_total,
        SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) AS failed_total,
        SUM(opens) AS opens_total,
        SUM(clicks) AS clicks_total
    FROM emails
    WHERE user_id = p_user_id
      AND created_at >= (NOW() - INTERVAL p_periodo_dias DAY)
    GROUP BY DATE(created_at)
    ORDER BY ref_date DESC;
END $$

DROP PROCEDURE IF EXISTS sp_auditoria_tentativas_falhas $$
-- Procedure `sp_auditoria_tentativas_falhas` (MariaDB/MySQL) | Procedure `sp_auditoria_tentativas_falhas` (MariaDB/MySQL)
CREATE PROCEDURE sp_auditoria_tentativas_falhas(
    IN p_ip VARCHAR(45),
    IN p_dias INT
)
BEGIN
    SELECT *
    FROM failed_logins
    WHERE (p_ip IS NULL OR ip_address = p_ip)
      AND attempted_at >= (NOW() - INTERVAL p_dias DAY)
    ORDER BY attempted_at DESC;
END $$

DELIMITER ;
