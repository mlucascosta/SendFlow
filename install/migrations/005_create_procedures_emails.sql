-- Migration 005: Procedures de email | Email procedures

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_email_enviar $$
CREATE PROCEDURE sp_email_enviar(
    IN p_user_id INT,
    IN p_session_id VARCHAR(128),
    IN p_to_email VARCHAR(255),
    IN p_subject VARCHAR(255),
    IN p_body_html LONGTEXT,
    IN p_body_text LONGTEXT,
    OUT p_email_id INT,
    OUT p_codigo_retorno INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    DECLARE v_from_email VARCHAR(255);

    SELECT email INTO v_from_email FROM users WHERE id = p_user_id LIMIT 1;

    INSERT INTO emails (user_id, session_id, from_email, to_email, subject, body_html, body_text, status, direction)
    VALUES (p_user_id, p_session_id, v_from_email, p_to_email, p_subject, p_body_html, p_body_text, 'queued', 'outbound');

    SET p_email_id = LAST_INSERT_ID();
    SET p_codigo_retorno = 201;
    SET p_mensagem = 'Email preparado para envio | Email queued for sending';
END $$

DROP PROCEDURE IF EXISTS sp_email_atualizar_status_apos_envio $$
CREATE PROCEDURE sp_email_atualizar_status_apos_envio(
    IN p_email_id INT,
    IN p_resend_message_id VARCHAR(255),
    IN p_status VARCHAR(20),
    IN p_error TEXT
)
BEGIN
    UPDATE emails
    SET resend_message_id = COALESCE(p_resend_message_id, resend_message_id),
        status = p_status,
        sent_at = CASE WHEN p_status = 'sent' THEN NOW() ELSE sent_at END,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = p_email_id;

    IF p_error IS NOT NULL AND p_error <> '' THEN
        INSERT INTO audit_logs (action, entity_type, entity_id, new_values)
        VALUES ('email_send_error', 'emails', p_email_id, JSON_OBJECT('error', p_error));
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_email_listar_por_status $$
CREATE PROCEDURE sp_email_listar_por_status(
    IN p_user_id INT,
    IN p_status VARCHAR(20),
    IN p_direction VARCHAR(20),
    IN p_limit_rows INT,
    IN p_offset_rows INT
)
BEGIN
    SELECT *
    FROM emails
    WHERE user_id = p_user_id
      AND (p_status IS NULL OR status = p_status)
      AND (p_direction IS NULL OR direction = p_direction)
    ORDER BY created_at DESC
    LIMIT p_limit_rows OFFSET p_offset_rows;
END $$

DROP PROCEDURE IF EXISTS sp_email_buscar_por_id $$
CREATE PROCEDURE sp_email_buscar_por_id(
    IN p_email_id INT,
    IN p_user_id INT
)
BEGIN
    SELECT *
    FROM emails
    WHERE id = p_email_id AND user_id = p_user_id
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_email_registrar_abertura $$
CREATE PROCEDURE sp_email_registrar_abertura(
    IN p_resend_message_id VARCHAR(255)
)
BEGIN
    UPDATE emails
    SET opens = opens + 1,
        updated_at = CURRENT_TIMESTAMP
    WHERE resend_message_id = p_resend_message_id;
END $$

DELIMITER ;
