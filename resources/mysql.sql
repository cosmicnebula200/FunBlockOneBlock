-- #!mysql
-- #{ funblockoneblock
-- #{ player
-- #    { init
CREATE TABLE IF NOT EXISTS funblockoneblock_player
(
    name VARCHAR(32) PRIMARY KEY,
    oneblock STRING DEFAULT ''
    );
--  #   }

-- # { load
SELECT *
FROM funblockoneblock_player;
-- #    }

-- # { create
-- #        :name string
-- #        :oneblock string
INSERT INTO funblockoneblock_player (name, oneblock)
VALUES (:name, :oneblock);
-- # }

-- # { update
-- #        :name string
-- #        :oneblock string
UPDATE funblockoneblock_player
SET oneblock=:oneblock
WHERE name=:name;
-- # }
-- # }

-- # { oneblock
-- # { init
CREATE TABLE IF NOT EXISTS funblockoneblock_oneblock
(
    name VARCHAR(32) PRIMARY KEY,
    leader VARCHAR(32),
    members STRING,
    world STRING,
    xp INT,
    level INT,
    spawn STRING
    );
-- # }

-- # { load
SELECT *
FROM funblockoneblock_oneblock;
-- # }

-- # { create
-- #        :name string
-- #        :leader string
-- #        :members string
-- #        :world string
-- #        :xp int
-- #        :level int
-- #        :spawn string
INSERT INTO funblock_oneblock (name, leader, members, world, xp, level, spawn)
VALUES (:name, :leader, :members, :world, :xp, :level, :spawn);
-- # }

-- # { delete
-- #        :name string
DELETE
FROM funblockoneblock_oneblock
WHERE name=:name
-- # }

-- # { update
-- #        :name string
-- #        :leader string
-- #        :members string
-- #        :world string
-- #        :xp int
-- #        :level int
-- #        :spawn string
UPDATE funblock_oneblock
SET leader=:leader,
    members=:members,
    world=:world,
    xp=:xp,
    level=:level
    spawn=:spawn
WHERE name=:name;
-- # }
-- # }
-- # }