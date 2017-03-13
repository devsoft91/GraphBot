DROP PROCEDURE IF EXISTS `add_stats`;

DELIMITER $$
CREATE PROCEDURE `add_stats`
(IN `id_par` BIGINT(30), IN `hour_par` INT(2)) DETERMINISTIC
BEGIN
  DECLARE `c` INTEGER(6);
  
  IF NOT EXISTS(SELECT * FROM `statistics` WHERE `chat_id` = `id_par`) THEN
    INSERT INTO `statistics` VALUES
      (`id_par`,0,0),
      (`id_par`,1,0),
      (`id_par`,2,0),
      (`id_par`,3,0),
      (`id_par`,4,0),
      (`id_par`,5,0),
      (`id_par`,6,0),
      (`id_par`,7,0),
      (`id_par`,8,0),
      (`id_par`,9,0),
      (`id_par`,10,0),
      (`id_par`,11,0),
      (`id_par`,12,0),
      (`id_par`,13,0),
      (`id_par`,14,0),
      (`id_par`,15,0),
      (`id_par`,16,0),
      (`id_par`,17,0),
      (`id_par`,18,0),
      (`id_par`,19,0),
      (`id_par`,20,0),
      (`id_par`,21,0),
      (`id_par`,22,0),
      (`id_par`,23,0);

    END IF;

    SET `c` = (SELECT `counter` FROM `statistics` WHERE `chat_id` = `id_par` AND `hour` = `hour_par`) + 1;

    UPDATE `statistics`
    SET `counter` = `c`
    WHERE `chat_id` = `id_par` AND `hour` = `hour_par`;

END$$

DELIMITER ;