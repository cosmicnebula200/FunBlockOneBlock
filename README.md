# FunBlockOneBlock
 A OneBlock plugin for PocketMine-4.0.0

# Features
- MySql and Sqlite3 providers
- Easy to set up
- Fully customizable (including messages)
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
