-- #! sqlite
-- #{ funblockoneblock

-- #{ player

-- # { init
CREATE TABLE IF NOT EXISTS funblockoneblock_player
(
    uuid VARCHAR(32) PRIMARY KEY,
    name VARCHAR(32),
    oneblock STRING DEFAULT ''
    );
-- # }

-- # { load
-- #    :uuid string
SELECT *
FROM funblockoneblock_player
WHERE uuid=:uuid;
-- # }

-- # { create
-- #   :uuid string
-- #   :name string
-- #   :oneblock string
INSERT INTO funblockoneblock_player (uuid, name, oneblock)
VALUES (:uuid, :name, :oneblock);
-- # }

-- # { update
-- #    :uuid string
-- #    :name string
-- #    :oneblock string
UPDATE funblockoneblock_player
SET oneblock=:oneblock,
    name=:name
WHERE uuid=:uuid;
-- # }

-- # }

-- # { oneblock

-- # { init
CREATE TABLE IF NOT EXISTS funblockoneblock_oneblock
(
    uuid VARCHAR(32) PRIMARY KEY,
    name VARCHAR(32),
    leader VARCHAR(32),
    members STRING,
    world STRING,
    xp INT,
    level INT,
    settings STRING,
    spawn STRING
    );
-- # }

-- # { load
-- #    :uuid string
SELECT *
FROM funblockoneblock_oneblock
WHERE uuid=:uuid;
-- # }

-- # { create
-- #   :uuid string
-- #   :name string
-- #   :leader string
-- #   :members string
-- #   :world string
-- #   :xp int
-- #   :level int
-- #   :settings string
-- #   :spawn string
INSERT INTO funblockoneblock_oneblock (uuid, name, leader, members, world, xp, level, settings, spawn)
VALUES (:uuid, :name, :leader, :members, :world, :xp, :level, :settings, :spawn);
-- # }

-- # { delete
-- #   :uuid string
DELETE
FROM funblockoneblock_oneblock
WHERE uuid=:uuid
-- # }

-- # { update
-- #    :uuid string
-- #    :name string
-- #    :leader string
-- #    :members string
-- #    :world string
-- #    :xp int
-- #    :level int
-- #    :settings string
-- #    :spawn string
UPDATE funblockoneblock_oneblock
SET name=:name,
    leader=:leader,
    members=:members,
    world=:world,
    xp=:xp,
    level=:level,
    settings=:settings,
    spawn=:spawn
WHERE uuid=:uuid;
-- # }

-- # }

-- # }
