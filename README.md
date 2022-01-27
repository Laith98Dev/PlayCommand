# PlayCommand
Simple play command plugin like hypixel

# How to use the plugin
- `/play add` -> for add a new game, for example i have an game called sumo and join command = `/sumo join` and want added it, usage: `/play add sumo /sumo join`.
- `/play set` -> for edit an game command, for example i need to change sumo join command, usage: `/play set sumo /newcommand`.
- `/play remove` -> for remove an game, for example i want remove sumo, usage: `/play remove sumo`
- `/play list` -> that's command to show games list. 
- `/play <GameName>` -> to join the game, for example i want to playe sumo, usage: `/play sumo`, and if your game can join a specific arena by name like `/sumo join Towers`, you can type it in the play command like `/play sumo Towers`.

# Commands List
Command | Permission
--- | ---
`/play <GameName> <others:optional>` | `No Permission`
`/play add <GameName> <JoinComamnd>` | `play.cmd.admin`
`/play set <GameName> <NewComamnd>` | `play.cmd.admin`
`/play remove <GameName>` | `play.cmd.admin`
`/play list` | `No Permission`
