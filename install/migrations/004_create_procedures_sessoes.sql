-- Compatibilidade: MariaDB/MySQL | Compatibility: MariaDB/MySQL
-- Migration 004: Procedures de sessão | Session procedures

DELIMITER //

DROP PROCEDURE IF EXISTS sp_sessao_criar //
-- Procedure `sp_sessao_criar` (MariaDB/MySQL) | Procedure `sp_sessao_criar` (MariaDB/MySQL)
CREATE PROCEDURE sp_sessao_criar(
    IN p_session_id VARCHAR(128),
    IN p_user_id INT,
    IN p_ip VARCHAR(45),
    IN p_user_agent TEXT,
    OUT p_codigo_retorno INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    INSERT INTO sessions (id, user_id, ip_address, user_agent, payload, last_activity, is_active)
    VALUES (p_session_id, p_user_id, p_ip, p_user_agent, NULL, UNIX_TIMESTAMP(), 1);

    INSERT INTO session_logs (user_id, session_id, action, ip_address, user_agent)
    VALUES (p_user_id, p_session_id, 'login', p_ip, p_user_agent);

    SET p_codigo_retorno = 201;
    SET p_mensagem = 'Sessão criada | Session created';
END //

DROP PROCEDURE IF EXISTS sp_sessao_validar //
-- Procedure `sp_sessao_validar` (MariaDB/MySQL) | Procedure `sp_sessao_validar` (MariaDB/MySQL)
CREATE PROCEDURE sp_sessao_validar(
    IN p_session_id VARCHAR(128),
    IN p_ip VARCHAR(45),
    IN p_ua TEXT,
    IN p_validar_ip TINYINT,
    IN p_validar_ua TINYINT,
    OUT p_user_id INT,
    OUT p_nome VARCHAR(100),
    OUT p_email VARCHAR(255),
    OUT p_role VARCHAR(20),
    OUT p_must_change TINYINT,
    OUT p_domain VARCHAR(255),
    OUT p_last_digits CHAR(4),
    OUT p_codigo INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    DECLARE v_ip VARCHAR(45);
    DECLARE v_ua TEXT;

    SELECT s.user_id, s.ip_address, s.user_agent, u.name, u.email, u.role, u.must_change_password, u.resend_domain, u.resend_api_key_last_digits
    INTO p_user_id, v_ip, v_ua, p_nome, p_email, p_role, p_must_change, p_domain, p_last_digits
    FROM sessions s
    INNER JOIN users u ON u.id = s.user_id
    WHERE s.id = p_session_id
      AND s.is_active = 1
      AND u.is_active = 1
      AND u.deleted_at IS NULL
      AND s.last_activity >= UNIX_TIMESTAMP(NOW() - INTERVAL 2 HOUR)
    LIMIT 1;

    IF p_user_id IS NULL THEN
        SET p_codigo = 401;
        SET p_mensagem = 'Sessão inválida ou expirada | Invalid or expired session';
    ELSEIF p_validar_ip = 1 AND v_ip <> p_ip THEN
        SET p_codigo = 401;
        SET p_mensagem = 'IP divergente | IP mismatch';
    ELSEIF p_validar_ua = 1 AND v_ua <> p_ua THEN
        SET p_codigo = 401;
        SET p_mensagem = 'User-Agent divergente | User-Agent mismatch';
    ELSE
        UPDATE sessions SET last_activity = UNIX_TIMESTAMP() WHERE id = p_session_id;
        SET p_codigo = 200;
        SET p_mensagem = 'Sessão válida | Valid session';
    END IF;
END //

DROP PROCEDURE IF EXISTS sp_sessao_listar_ativas //
-- Procedure `sp_sessao_listar_ativas` (MariaDB/MySQL) | Procedure `sp_sessao_listar_ativas` (MariaDB/MySQL)
CREATE PROCEDURE sp_sessao_listar_ativas(
    IN p_user_id INT,
    IN p_session_id_atual VARCHAR(128)
)
BEGIN
    SELECT
        id,
        ip_address,
        user_agent,
        FROM_UNIXTIME(last_activity) AS last_activity_at,
        created_at,
        (id = p_session_id_atual) AS is_current
    FROM sessions
    WHERE user_id = p_user_id AND is_active = 1
    ORDER BY last_activity DESC;
END //

DROP PROCEDURE IF EXISTS sp_sessao_encerrar //
-- Procedure `sp_sessao_encerrar` (MariaDB/MySQL) | Procedure `sp_sessao_encerrar` (MariaDB/MySQL)
CREATE PROCEDURE sp_sessao_encerrar(
    IN p_session_id VARCHAR(128),
    IN p_user_id INT,
    IN p_ip VARCHAR(45),
    IN p_ua TEXT,
    OUT p_codigo_retorno INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    UPDATE sessions
    SET is_active = 0
    WHERE id = p_session_id AND user_id = p_user_id;

    INSERT INTO session_logs (user_id, session_id, action, ip_address, user_agent)
    VALUES (p_user_id, p_session_id, 'logout', p_ip, p_ua);

    SET p_codigo_retorno = 200;
    SET p_mensagem = 'Sessão encerrada | Session terminated';
END //

DROP PROCEDURE IF EXISTS sp_sessao_encerrar_todas_exceto_atual //
-- Procedure `sp_sessao_encerrar_todas_exceto_atual` (MariaDB/MySQL) | Procedure `sp_sessao_encerrar_todas_exceto_atual` (MariaDB/MySQL)
CREATE PROCEDURE sp_sessao_encerrar_todas_exceto_atual(
    IN p_user_id INT,
    IN p_session_id_atual VARCHAR(128),
    IN p_ip VARCHAR(45),
    IN p_ua TEXT,
    OUT p_codigo_retorno INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    UPDATE sessions
    SET is_active = 0
    WHERE user_id = p_user_id
      AND id <> p_session_id_atual
      AND is_active = 1;

    INSERT INTO session_logs (user_id, session_id, action, ip_address, user_agent, details)
    VALUES (p_user_id, p_session_id_atual, 'revoked', p_ip, p_ua, JSON_OBJECT('scope', 'all_except_current'));

    SET p_codigo_retorno = 200;
    SET p_mensagem = 'Outras sessões encerradas | Other sessions terminated';
END //

DELIMITER ;
