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
