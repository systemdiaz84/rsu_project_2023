CREATE FUNCTION get_zone_id(@latitude FLOAT, @longitude FLOAT)
RETURNS INT
AS
BEGIN
    DECLARE @zone_id INT;
    DECLARE @num_intersections INT;
    DECLARE @i INT;
    DECLARE @j INT;
    DECLARE @p1_lat FLOAT;
    DECLARE @p1_long FLOAT;
    DECLARE @p2_lat FLOAT;
    DECLARE @p2_long FLOAT;
    DECLARE @x_inters FLOAT;

    SET @num_intersections = 0;
    SET @i = 1;

    WHILE @i <= (SELECT COUNT(*) FROM zones)
    begin
	    while @j <= (select COUNT(*) from zone_coords where zone_id = @i)
        begin
            SET @p1_lat = (SELECT latitude FROM zone_cords WHERE id = @i);
            SET @p1_long = (SELECT longitude FROM zone_cords WHERE id = @i);
            SET @p2_lat = (SELECT latitude FROM zone_cords WHERE id = ((@i % (SELECT COUNT(*) FROM zone_cords)) + 1));
            SET @p2_long = (SELECT longitude FROM zone_cords WHERE id = ((@i % (SELECT COUNT(*) FROM zone_cords)) + 1));

            IF @latitude > MIN(@p1_lat, @p2_lat) AND @latitude <= MAX(@p1_lat, @p2_lat) AND @longitude <= MAX(@p1_long, @p2_long) THEN
            BEGIN
                IF @p1_lat != @p2_lat THEN
                BEGIN
                    SET @x_inters = (@latitude - @p1_lat) * (@p2_long - @p1_long) / (@p2_lat - @p1_lat) + @p1_long;
                    IF @p1_long = @p2_long OR @longitude <= @x_inters THEN
                    BEGIN
                        SET @num_intersections = @num_intersections + 1;
                    END;
                END;
            END;
            SET @j = @j + 1;
        END;
        IF @num_intersections % 2 = 1 THEN
        BEGIN
            SET @zone_id = (SELECT id FROM zones WHERE id = @i);
        END
        ELSE
        BEGIN
            SET @zone_id = NULL;
        END;
        SET @i = @i + 1;
    END;

    RETURN @zone_id;
END;

-- probar funcion
<div>Latitud: -6.760440551780828<br>Longitud: -79.86309855041476</div>
SELECT dbo.get_zone_id(4.5, 4.5);

SELECT get_zone_id(-6.760440551780828, -79.86309855041476);



CREATE FUNCTION get_zone_id(latitude FLOAT, longitude FLOAT)
RETURNS INT
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE zone_id INT;
    DECLARE num_intersections INT DEFAULT 0;
    DECLARE p1_lat FLOAT;
    DECLARE p1_long FLOAT;
    DECLARE p2_lat FLOAT;
    DECLARE p2_long FLOAT;
    DECLARE x_inters FLOAT;


    DECLARE zone_cursor CURSOR FOR
        SELECT id FROM zones ORDER BY id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN zone_cursor;

    zone_loop: LOOP
        FETCH zone_cursor INTO zone_id;
        IF done IS NULL THEN
            LEAVE zone_loop;
        END IF;
        BLOCK2: BEGIN
            DECLARE coord_cursor CURSOR FOR
                SELECT latitude, longitude FROM zone_coords WHERE zone_id = zone_id ORDER BY id;

            OPEN coord_cursor;

            coord_loop: LOOP
                FETCH coord_cursor INTO p1_lat, p1_long;
                IF p1_lat IS NULL OR p1_long IS NULL THEN
                    LEAVE coord_loop;
                END IF;

                FETCH coord_cursor INTO p2_lat, p2_long;
                IF p2_lat IS NULL OR p2_long IS NULL THEN
                    LEAVE coord_loop;
                END IF;

                IF latitude > LEAST(p1_lat, p2_lat) AND latitude <= GREATEST(p1_lat, p2_lat) AND longitude <= GREATEST(p1_long, p2_long) THEN
                    IF p1_lat != p2_lat THEN
                        SET x_inters = (latitude - p1_lat) * (p2_long - p1_long) / (p2_lat - p1_lat) + p1_long;
                        IF p1_long = p2_long OR longitude <= x_inters THEN
                            SET num_intersections = num_intersections + 1;
                        END IF;
                    END IF;
                END IF;
            END LOOP;

            CLOSE coord_cursor;
        END BLOCK2;

        IF num_intersections % 2 = 1 THEN
            CLOSE zone_cursor;
            SET zone_id = (SELECT id FROM zones WHERE id = zone_id);
            LEAVE zone_loop;
        ELSE
            SET zone_id = NULL;
        END IF;

        SET num_intersections = 0;
    END LOOP;

    CLOSE zone_cursor;

    RETURN zone_id;
END;


CREATE PROCEDURE puntoEnPoligono(IN x DOUBLE, IN y DOUBLE, IN x1 DOUBLE, IN y1 DOUBLE, IN x2 DOUBLE, IN y2 DOUBLE, OUT resultado BOOLEAN)
BEGIN
  DECLARE cruces INT DEFAULT 0;
  IF x = x1 AND y = y1 THEN
    SET resultado = TRUE;
  ELSEIF y1 = y2 AND y = y1 AND x > LEAST(x1, x2) AND x < GREATEST(x1, x2) THEN
    SET resultado = TRUE;
  ELSEIF y < LEAST(y1, y2) OR y > GREATEST(y1, y2) THEN
    SET resultado = FALSE;
  ELSEIF x < LEAST(x1, x2) THEN
    SET cruces = cruces + 1;
    SET resultado = cruces % 2 = 1;
  ELSEIF x > GREATEST(x1, x2) THEN
    SET resultado = FALSE;
  ELSE
    DECLARE m DOUBLE;
    DECLARE b DOUBLE;
    SET m = (y2 - y1) / (x2 - x1);
    SET b = y1 - m * x1;
    IF y = m * x + b THEN
      SET resultado = TRUE;
    ELSEIF y > m * x + b THEN
      SET cruces = cruces + 1;
      SET resultado = cruces % 2 = 1;
    END IF;
  END IF;
END;




-- DECLARE coord_cursor CURSOR FOR
--                 SELECT latitude, longitude FROM zone_coords WHERE zone_id = zone_id ORDER BY id;

--             OPEN coord_cursor;

CREATE FUNCTION get_zone_id(latitude DOUBLE, longitude DOUBLE)
RETURNS INT
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE zone_id INT;
    DECLARE num_intersections INT DEFAULT 0;
    DECLARE p1_lat DOUBLE;
    DECLARE p1_long DOUBLE;
    DECLARE p2_lat DOUBLE;
    DECLARE p2_long DOUBLE;
    DECLARE x_inters DOUBLE;
    DECLARE m DOUBLE;
    DECLARE b DOUBLE;
    DECLARE num_coords INT DEFAULT 0;
    DECLARE i INT DEFAULT 1;

    DECLARE zone_cursor CURSOR FOR
        SELECT id FROM zones ORDER BY id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SELECT COUNT(id) INTO num_coords FROM zone_coords WHERE zone_id = zone_id;
    
    OPEN zone_cursor;

    zone_loop: LOOP
        FETCH zone_cursor INTO zone_id;
        IF done IS NULL THEN
            LEAVE zone_loop;
        END IF;
        BLOCK2: BEGIN
            

            coord_loop: LOOP
                SELECT latitude, longitude INTO p1_lat, p1_long FROM zone_coords WHERE zone_id = zone_id ORDER BY id LIMIT i, 1;

                IF p1_lat IS NULL OR p1_long IS NULL THEN
                    LEAVE coord_loop;
                END IF;

                SELECT latitude, longitude INTO p2_lat, p2_long FROM zone_coords WHERE zone_id = zone_id ORDER BY id LIMIT 1, 1;

                IF p2_lat IS NULL OR p2_long IS NULL THEN
                    SELECT latitude, longitude INTO p2_lat, p2_long FROM zone_coords WHERE zone_id = zone_id ORDER BY id LIMIT 0, 1;
                END IF;

                IF latitude = p1_lat AND longitude = p1_long THEN
                    SET zone_id = (SELECT id FROM zones WHERE id = zone_id);
                    LEAVE zone_loop;
                ELSEIF p1_lat = p2_lat AND latitude = p1_lat AND longitude > LEAST(p1_long, p2_long) AND longitude < GREATEST(p1_long, p2_long) THEN
                    SET zone_id = (SELECT id FROM zones WHERE id = zone_id);
                    LEAVE zone_loop;
                ELSEIF latitude < LEAST(p1_lat, p2_lat) OR latitude > GREATEST(p1_lat, p2_lat) THEN
                    ITERATE coord_loop;
                ELSEIF longitude < LEAST(p1_long, p2_long) THEN
                    SET num_intersections = num_intersections + 1;
                ELSEIF longitude > GREATEST(p1_long, p2_long) THEN
                    ITERATE coord_loop;
                ELSE
                    SET m = (p2_lat - p1_lat) / (p2_long - p1_long);
                    SET b = p1_lat - m * p1_long;
                    IF latitude = m * longitude + b THEN
                        SET zone_id = (SELECT id FROM zones WHERE id = zone_id);
                        LEAVE zone_loop;
                    ELSEIF latitude > m * longitude + b THEN
                        SET num_intersections = num_intersections + 1;
                    END IF;
                END IF;
                SET i = i + 1;
            END LOOP;

            IF num_intersections % 2 = 1 THEN
                CLOSE zone_cursor;
                RETURN zone_id;
            ELSE
                SET zone_id = NULL;
            END IF;

            SET num_intersections = 0;

        END BLOCK2;
        SET i = 1;
    END LOOP;

    CLOSE zone_cursor;

    RETURN zone_id;
END;

DELIMITER //
CREATE FUNCTION get_zone_id(lat DOUBLE, lng DOUBLE)
RETURNS INT
BEGIN
    DECLARE zone_id INT;

    SELECT zone_id INTO zone_id
    FROM zone_coords
    WHERE ST_Contains(
        ST_GeomFromText(CONCAT('POLYGON((', 
            GROUP_CONCAT(CONCAT(latitude, ' ', longitude) SEPARATOR ', '), '))')),
        ST_PointFromText(CONCAT('POINT(', lat, ' ', lng, ')'))
    )
    LIMIT 1;

    RETURN zone_id;
END//
DELIMITER ;

DELIMITER //
CREATE FUNCTION get_zone_id(lat DOUBLE, lng DOUBLE)
RETURNS INT
BEGIN
    DECLARE _zone_id INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE cur CURSOR FOR SELECT DISTINCT zone_id FROM zone_coords ORDER BY zone_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO _zone_id;
        IF done THEN
            SET _zone_id = NULL;
            LEAVE read_loop;
        END IF;

        IF ST_Contains(
            ST_GeomFromText(CONCAT('POLYGON((',
                (SELECT CONCAT(
                    GROUP_CONCAT(CONCAT(latitude, ' ', longitude) SEPARATOR ','),
                    ',', 
                    SUBSTRING_INDEX(GROUP_CONCAT(CONCAT(latitude, ' ', longitude) SEPARATOR ','), ',', 1)
                )
                FROM zone_coords WHERE zone_id = _zone_id), '))')),
            ST_PointFromText(CONCAT('POINT(', lat, ' ', lng, ')'))
        ) = 1 THEN
            LEAVE read_loop;
        END IF;
    END LOOP;

    CLOSE cur;

    RETURN _zone_id;
END//
DELIMITER ;



-- FUNCIÓN FINAL
DELIMITER //
CREATE FUNCTION get_zone_id(lat DOUBLE, lng DOUBLE)
RETURNS INT
BEGIN
    DECLARE _zone_id INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE cur CURSOR FOR SELECT DISTINCT zone_id FROM zone_coords ORDER BY zone_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO _zone_id;
        IF done THEN
            SET _zone_id = NULL;
            LEAVE read_loop;
        END IF;

        IF (SELECT COUNT(id) FROM zone_coords WHERE zone_id = _zone_id) < 3 THEN
            ITERATE read_loop;
        END IF;

        IF ST_Contains(
            ST_GeomFromText(CONCAT('POLYGON((',
                (SELECT CONCAT(
                    GROUP_CONCAT(CONCAT(latitude, ' ', longitude) SEPARATOR ','),
                    ',',
                    (SELECT CONCAT(latitude, ' ', longitude) FROM zone_coords WHERE zone_id = _zone_id LIMIT 1)
                )
                FROM zone_coords WHERE zone_id = _zone_id), '))')),
            ST_PointFromText(CONCAT('POINT(', lat, ' ', lng, ')'))
        ) = 1 THEN
            LEAVE read_loop;
        END IF;
    END LOOP;

    CLOSE cur;

    RETURN _zone_id;
END//
DELIMITER ;