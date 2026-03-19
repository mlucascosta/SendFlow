-- Compatibilidade: MariaDB/MySQL | Compatibility: MariaDB/MySQL
-- Migration 007: Procedures de integração Resend | Resend integration procedures

DELIMITER //

DROP PROCEDURE IF EXISTS sp_resend_webhook_receber //
-- Procedure `sp_resend_webhook_receber` (MariaDB/MySQL) | Procedure `sp_resend_webhook_receber` (MariaDB/MySQL)
CREATE PROCEDURE sp_resend_webhook_receber(
    IN p_event_type VARCHAR(50),
    IN p_resend_id VARCHAR(255),
    IN p_payload JSON,
    OUT p_webhook_id INT,
    OUT p_codigo INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    INSERT INTO webhook_logs (event_type, resend_message_id, payload, processed, signature_valid)
    VALUES (p_event_type, p_resend_id, p_payload, 0, 1);

    SET p_webhook_id = LAST_INSERT_ID();

    IF p_event_type = 'email.opened' THEN
        UPDATE emails SET opens = opens + 1 WHERE resend_message_id = p_resend_id;
    ELSEIF p_event_type = 'email.clicked' THEN
        UPDATE emails SET clicks = clicks + 1 WHERE resend_message_id = p_resend_id;
    ELSEIF p_event_type = 'email.sent' THEN
        UPDATE emails SET status = 'sent', updated_at = CURRENT_TIMESTAMP WHERE resend_message_id = p_resend_id;
    ELSEIF p_event_type IN ('email.bounced', 'email.complained') THEN
        UPDATE emails SET status = 'failed', updated_at = CURRENT_TIMESTAMP WHERE resend_message_id = p_resend_id;
    END IF;

    UPDATE webhook_logs SET processed = 1 WHERE id = p_webhook_id;
    SET p_codigo = 200;
    SET p_mensagem = 'Webhook processado | Webhook processed';
END //

DROP PROCEDURE IF EXISTS sp_resend_webhooks_pendentes //
-- Procedure `sp_resend_webhooks_pendentes` (MariaDB/MySQL) | Procedure `sp_resend_webhooks_pendentes` (MariaDB/MySQL)
CREATE PROCEDURE sp_resend_webhooks_pendentes()
BEGIN
    SELECT *
    FROM webhook_logs
    WHERE processed = 0
    ORDER BY created_at ASC
    LIMIT 100;
END //

DROP PROCEDURE IF EXISTS sp_resend_testar_conexao //
-- Procedure `sp_resend_testar_conexao` (MariaDB/MySQL) | Procedure `sp_resend_testar_conexao` (MariaDB/MySQL)
CREATE PROCEDURE sp_resend_testar_conexao(
    IN p_user_id INT,
    OUT p_domain_verificado TINYINT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    DECLARE v_domain VARCHAR(255);
    SELECT resend_domain INTO v_domain FROM users WHERE id = p_user_id LIMIT 1;

    IF v_domain IS NULL OR v_domain = '' THEN
        SET p_domain_verificado = 0;
        SET p_mensagem = 'Domínio não configurado | Domain not configured';
    ELSE
        SET p_domain_verificado = 1;
        SET p_mensagem = 'Domínio configurado no perfil | Domain configured in profile';
    END IF;
END //

DELIMITER ;
