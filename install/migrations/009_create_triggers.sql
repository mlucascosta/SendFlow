-- Migration 009: Triggers automáticos | Automatic triggers

DELIMITER $$

DROP TRIGGER IF EXISTS trg_users_update_audit $$
CREATE TRIGGER trg_users_update_audit
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF OLD.email <> NEW.email OR OLD.is_active <> NEW.is_active OR OLD.role <> NEW.role THEN
        INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values)
        VALUES (
            NEW.id,
            'user_updated',
            'users',
            NEW.id,
            JSON_OBJECT('email', OLD.email, 'is_active', OLD.is_active, 'role', OLD.role),
            JSON_OBJECT('email', NEW.email, 'is_active', NEW.is_active, 'role', NEW.role)
        );
    END IF;
END $$

DROP TRIGGER IF EXISTS trg_users_soft_delete $$
CREATE TRIGGER trg_users_soft_delete
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF OLD.deleted_at IS NULL AND NEW.deleted_at IS NOT NULL THEN
        UPDATE sessions
        SET is_active = 0
        WHERE user_id = NEW.id AND is_active = 1;
    END IF;
END $$

DROP TRIGGER IF EXISTS trg_emails_insert_audit $$
CREATE TRIGGER trg_emails_insert_audit
AFTER INSERT ON emails
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, session_id, action, entity_type, entity_id, new_values)
    VALUES (
        NEW.user_id,
        NEW.session_id,
        'email_created',
        'emails',
        NEW.id,
        JSON_OBJECT('to', NEW.to_email, 'subject', NEW.subject, 'status', NEW.status)
    );
END $$

DROP TRIGGER IF EXISTS trg_failed_logins_threshold $$
CREATE TRIGGER trg_failed_logins_threshold
AFTER INSERT ON failed_logins
FOR EACH ROW
BEGIN
    DECLARE v_attempts INT DEFAULT 0;

    SELECT COUNT(*) INTO v_attempts
    FROM failed_logins
    WHERE email = NEW.email
      AND ip_address = NEW.ip_address
      AND attempted_at >= (NOW() - INTERVAL 15 MINUTE);

    IF v_attempts >= 5 THEN
        INSERT INTO audit_logs (action, entity_type, new_values, ip_address, user_agent)
        VALUES (
            'security_bruteforce_alert',
            'failed_logins',
            JSON_OBJECT('email', NEW.email, 'attempts', v_attempts),
            NEW.ip_address,
            NEW.user_agent
        );
    END IF;
END $$

DELIMITER ;
