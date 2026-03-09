-- Migration 003: Procedures de usuários | User procedures

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_usuario_criar_primeiro_admin $$
CREATE PROCEDURE sp_usuario_criar_primeiro_admin(
    IN p_name VARCHAR(100),
    IN p_email VARCHAR(255),
    IN p_password_hash VARCHAR(255),
    IN p_resend_domain VARCHAR(255),
    OUT p_user_id INT,
    OUT p_codigo_retorno INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    IF EXISTS (SELECT 1 FROM users WHERE role = 'admin' AND deleted_at IS NULL) THEN
        SET p_user_id = NULL;
        SET p_codigo_retorno = 400;
        SET p_mensagem = 'Administrador já existe | Admin already exists';
    ELSE
        INSERT INTO users (name, email, password_hash, resend_domain, role, must_change_password, email_verified)
        VALUES (p_name, p_email, p_password_hash, p_resend_domain, 'admin', 0, 1);
        SET p_user_id = LAST_INSERT_ID();
        SET p_codigo_retorno = 201;
        SET p_mensagem = 'Administrador criado com sucesso | Admin created successfully';
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_usuario_autenticar $$
CREATE PROCEDURE sp_usuario_autenticar(
    IN p_email VARCHAR(255),
    IN p_ip VARCHAR(45),
    IN p_user_agent TEXT,
    OUT p_user_id INT,
    OUT p_nome VARCHAR(100),
    OUT p_role VARCHAR(20),
    OUT p_must_change TINYINT,
    OUT p_password_hash VARCHAR(255),
    OUT p_codigo INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    DECLARE v_user_id INT;

    SELECT id, name, role, must_change_password, password_hash
    INTO v_user_id, p_nome, p_role, p_must_change, p_password_hash
    FROM users
    WHERE email = p_email AND is_active = 1 AND deleted_at IS NULL
    LIMIT 1;

    IF v_user_id IS NULL THEN
        SET p_user_id = NULL;
        SET p_codigo = 401;
        SET p_mensagem = 'Credenciais inválidas | Invalid credentials';
    ELSE
        SET p_user_id = v_user_id;
        SET p_codigo = 200;
        SET p_mensagem = 'Usuário encontrado | User found';
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_usuario_atualizar_senha $$
CREATE PROCEDURE sp_usuario_atualizar_senha(
    IN p_user_id INT,
    IN p_nova_senha_hash VARCHAR(255),
    IN p_session_id_atual VARCHAR(128),
    OUT p_codigo_retorno INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    UPDATE users
    SET password_hash = p_nova_senha_hash,
        must_change_password = 0,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = p_user_id AND deleted_at IS NULL;

    UPDATE sessions
    SET is_active = 0
    WHERE user_id = p_user_id
      AND id <> p_session_id_atual
      AND is_active = 1;

    SET p_codigo_retorno = 200;
    SET p_mensagem = 'Senha atualizada com sucesso | Password updated successfully';
END $$

DROP PROCEDURE IF EXISTS sp_usuario_salvar_resend_key $$
CREATE PROCEDURE sp_usuario_salvar_resend_key(
    IN p_user_id INT,
    IN p_api_key VARBINARY(255),
    IN p_domain VARCHAR(255),
    IN p_last_digits CHAR(4),
    IN p_session_id VARCHAR(128),
    OUT p_codigo_retorno INT,
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    UPDATE users
    SET resend_api_key = p_api_key,
        resend_domain = p_domain,
        resend_api_key_last_digits = p_last_digits,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = p_user_id AND deleted_at IS NULL;

    INSERT INTO audit_logs (user_id, session_id, action, entity_type, entity_id, new_values)
    VALUES (p_user_id, p_session_id, 'update_resend_credentials', 'users', p_user_id, JSON_OBJECT('domain', p_domain));

    SET p_codigo_retorno = 200;
    SET p_mensagem = 'Credenciais Resend salvas | Resend credentials saved';
END $$

DELIMITER ;
