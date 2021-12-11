<p align="center">
<a href="https://github.com/cosmicnebula200/FunBlockOneBlock" target="_blank"><img src="https://media.discordapp.net/attachments/829673511009779723/918506120094564392/png_20211209_221513_0000.png?width=240&height=240"></a>
</p>

# FunBlockOneBlock
 A OneBlock plugin for PocketMine-4.0.0

# Features
- MySql and Sqlite3 providers
- Easy to set up
- Fully customizable (including messages and levels 🥺 )
- Lucky Block support (coming as FunBlockLuckyBlock plugin)

# Permissions
```
  funblockoneblock.command:
    default: true
    description: this permission allows the set user to use the oneblock command
  funblockoneblock.create:
    default: true
    description: this permission allows the user to create a OneBlock Island
  funblockoneblock.delete:
    default: true
    description: with this permission the user can open the admin menu
  funblockoneblock.delete.others:
    default: op
    description: with this permission the user can delete other players islands
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
