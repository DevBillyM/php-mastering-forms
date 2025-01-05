-- Store procedure for adding new submissions


DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddSubscriber`(IN subscriber_email VARCHAR(255))
BEGIN
    DECLARE duplicate_email CONDITION FOR SQLSTATE '23000';
    DECLARE exit handler FOR duplicate_email
    BEGIN
        SIGNAL SQLSTATE '23000' SET MESSAGE_TEXT = 'This email is already subscribed.';
    END;

    INSERT INTO subscribers (email) VALUES (subscriber_email);
END$$
DELIMITER ;