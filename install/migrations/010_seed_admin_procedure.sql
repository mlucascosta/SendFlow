-- Compatibilidade: MariaDB/MySQL | Compatibility: MariaDB/MySQL
-- Migration 010: Procedure de instalação e seed settings | Installation procedure and base settings

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_instalacao_verificar $$
-- Procedure `sp_instalacao_verificar` (MariaDB/MySQL) | Procedure `sp_instalacao_verificar` (MariaDB/MySQL)
CREATE PROCEDURE sp_instalacao_verificar(
    OUT p_ja_instalado TINYINT,
    OUT p_total_usuarios INT,
    OUT p_versao VARCHAR(20)
)
BEGIN
    SELECT COUNT(*) INTO p_total_usuarios FROM users WHERE deleted_at IS NULL;
    SET p_ja_instalado = IF(p_total_usuarios > 0, 1, 0);

    SELECT setting_value INTO p_versao
    FROM system_settings
    WHERE setting_key = 'app_version'
    LIMIT 1;

    IF p_versao IS NULL THEN
        SET p_versao = '1.0.0';
    END IF;
END $$

DELIMITER ;

INSERT INTO system_settings (setting_key, setting_value, setting_type)
VALUES
('app_name', 'SendFlow', 'string'),
('app_version', '1.0.0', 'string'),
('session_driver', 'database', 'string'),
('security_validate_ip', '0', 'boolean'),
('security_validate_user_agent', '0', 'boolean')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_type = VALUES(setting_type);
