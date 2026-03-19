-- Compatibilidade: MariaDB/MySQL | Compatibility: MariaDB/MySQL
-- Migration 006: Procedures de segurança | Security procedures

DELIMITER //

DROP PROCEDURE IF EXISTS sp_seguranca_verificar_rate_limit //
-- Procedure `sp_seguranca_verificar_rate_limit` (MariaDB/MySQL) | Procedure `sp_seguranca_verificar_rate_limit` (MariaDB/MySQL)
CREATE PROCEDURE sp_seguranca_verificar_rate_limit(
    IN p_email VARCHAR(255),
    IN p_ip VARCHAR(45),
    OUT p_permitido TINYINT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    DECLARE v_count_email INT DEFAULT 0;
    DECLARE v_count_ip INT DEFAULT 0;

    SELECT COUNT(*) INTO v_count_email
    FROM failed_logins
    WHERE email = p_email AND attempted_at >= (NOW() - INTERVAL 15 MINUTE);

    SELECT COUNT(*) INTO v_count_ip
    FROM failed_logins
    WHERE ip_address = p_ip AND attempted_at >= (NOW() - INTERVAL 15 MINUTE);

    IF v_count_email >= 5 OR v_count_ip >= 5 THEN
        SET p_permitido = 0;
        SET p_mensagem = 'Muitas tentativas. Tente novamente em 15 minutos | Too many attempts';
    ELSE
        SET p_permitido = 1;
        SET p_mensagem = 'Permitido | Allowed';
    END IF;
END //

DROP PROCEDURE IF EXISTS sp_seguranca_limpar_failed_logins //
-- Procedure `sp_seguranca_limpar_failed_logins` (MariaDB/MySQL) | Procedure `sp_seguranca_limpar_failed_logins` (MariaDB/MySQL)
CREATE PROCEDURE sp_seguranca_limpar_failed_logins(
    IN p_email VARCHAR(255),
    IN p_ip VARCHAR(45)
)
BEGIN
    DELETE FROM failed_logins
    WHERE email = p_email OR ip_address = p_ip;
END //

DROP PROCEDURE IF EXISTS sp_seguranca_auditar_acao //
-- Procedure `sp_seguranca_auditar_acao` (MariaDB/MySQL) | Procedure `sp_seguranca_auditar_acao` (MariaDB/MySQL)
CREATE PROCEDURE sp_seguranca_auditar_acao(
    IN p_user_id INT,
    IN p_session_id VARCHAR(128),
    IN p_acao VARCHAR(100),
    IN p_entity_type VARCHAR(50),
    IN p_entity_id INT,
    IN p_old JSON,
    IN p_new JSON,
    IN p_ip VARCHAR(45),
    IN p_ua TEXT
)
BEGIN
    INSERT INTO audit_logs (user_id, session_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent)
    VALUES (p_user_id, p_session_id, p_acao, p_entity_type, p_entity_id, p_old, p_new, p_ip, p_ua);
END //

DROP PROCEDURE IF EXISTS sp_seguranca_garbage_collector //
-- Procedure `sp_seguranca_garbage_collector` (MariaDB/MySQL) | Procedure `sp_seguranca_garbage_collector` (MariaDB/MySQL)
CREATE PROCEDURE sp_seguranca_garbage_collector()
BEGIN
    UPDATE sessions
    SET is_active = 0
    WHERE is_active = 1 AND last_activity < UNIX_TIMESTAMP(NOW() - INTERVAL 2 HOUR);

    DELETE FROM failed_logins WHERE attempted_at < (NOW() - INTERVAL 7 DAY);
    DELETE FROM session_logs WHERE created_at < (NOW() - INTERVAL 30 DAY);
    DELETE FROM webhook_logs WHERE created_at < (NOW() - INTERVAL 30 DAY);
    DELETE FROM audit_logs WHERE created_at < (NOW() - INTERVAL 30 DAY);
END //

DELIMITER ;
