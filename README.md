# FunBlockOneBlock

<p align="center">
<a href="https://github.com/cosmicnebula200/FunBlockOneBlock" target="_blank"><img src="https://media.discordapp.net/attachments/829673511009779723/918506120094564392/png_20211209_221513_0000.png?width=240&height=240"></a>
</p>

A OneBlock plugin for PocketMine-4.0.0

**What is OneBlock?**

OneBlock is a survival gamemode in which you stand on a lonely block floating in the void. You can mine the same block over and over, and it gives you basic materials that slowly become better and better. You go through certain phases, and the infinite block slowly upgrades to better blocks and this continues forever and ever.

# Features
- MySql and Sqlite3 providers
- Easy to set up
- Fully customizable (including messages and levels 🥺 )
- Lucky Block support (coming as FunBlockLuckyBlock plugin)

# Todo
- [❌] Chests with items on block generation
- [❌] Mobs with no AI to be spawned with some blocks on configurable levels
- Suggest more stuff so it can come here

# Permissions
```
  funblockoneblock.command:
    default: true
    description: this permission allows the set user to use the oneblock command
  funblockoneblock.create:
    default: true
    description: this permission allows the user to create a oneblock island
  funblockoneblock.chat:
    default: true
    description: this permission allows the user to use the oneblock chat
  funblockoneblock.delete:
    default: true
    description: with this permission the user can delete their oneblock island
  funblockoneblock.deleteothers:
    default: op
    description: with this permission the user can delete other players oneblock islands
  funblockoneblock.go:
    default: true
    description: this permission allows the user to teleport to the oneblock island
  funblockoneblock.visit:
    default: true
    description: this permission allows the user to visit other players oneblock islands
  funblockoneblock.invite:
    default: true
    description: this permission allows the user to invite players to their oneblock island
  funblockoneblock.accept:
    default: true
    description: this permission allows the user to accept incoming invites when they have no oneblock island
  funblockoneblock.rename:
    default: true
    description: this permission allows the user to rename their oneblock island
```

# Commands

Command | Description | Permission | Aliases |
----------------- | ------------- | ------------- | -------- |
OneBlock | OneBlock command for FunBlockOneBlock | funblockoneblock.command | ob

SubCommand | Description | Permission | Aliases |
----------------- | ------------- | ------------- | -------- |
accept | accepts the invite from the mentioned player| funblockoneblock.accept| |
create | creates a oneblock island | funblockoneblock.create | c, make
delete | deletes the mentioned players island | funblockoneblock.delete/ funblockoneblock.delete.others | |
go | teleports to your oneblock island | funblockoneblock.go |  |
invite | invites the mentioned player to your oneblock island | funblockoneblock.invite | |
visit | visit the mentioned player's oneblock island | funblockoneblock.visit | |

# ScoreHud Tags

Tag | Description |
------- | ----------------|
`funblockoneblock.xp` | Shows the XP of the member's OneBlock island |
`funblockoneblock.level` | Shows the Level of the member's OneBlock island |
`funblockoneblock.leader` | Shows the Leader of the member's OneBlock island ||
`funblockoneblock.world` | Shows the World name of the member's OneBlock island ||
`funblockoneblock.chat` | Shows the status (on/off) of the island chat ||
`funblockoneblock.level_name` | Shows the Level Name of the member's OneBlock island ||
`funblockoneblock.island_name` | Shows the Island Name of the member's OneBlock island ||


# Settings

Setting | Description | type |
--------|---------------|---------|
autoinv.enable | Enables the autoinventory **ON THE ONEBLOCK ISLANDS** | boolean (true/false)
autoinv.drop-when-full| Drops items on ground when the player has a full inventory | boolean (true/false)
autoxp| Automatically sends xp on Mining on the OneBlock Islands | boolean (true/false)
invite-time-out | The number of seconds to wait before an invite to a OneBlock Island expires | int (in seconds)
max-members | The maximum number of players a OneBlock Island can have . This value includes the Leader too | int
reset-xp | Reset xp upon level-ups | boolean (true/false)
damage | Toggles damages on OneBlock Island. **Here false means they wont take damage due to that cause** | boolean (true/false)
