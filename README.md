# discord-bot-steam
Discord bot for Steam API endpoints

## Searching games
https://steamcommunity.com/actions/SearchApps/<game-name>

### Response:
```json
[{"appid":"1517290","name":"Battlefield™ 2042","icon":"https:\/\/cdn.cloudflare.steamstatic.com\/steamcommunity\/public\/images\/apps\/1517290\/dc805cd05c36a1b26f4eb57b64301e6708e20776.jpg","logo":"https:\/\/cdn.cloudflare.steamstatic.com\/steam\/apps\/1517290\/capsule_184x69.jpg"}]
```

## Current player count for game
https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1/?key=<api-key>&appid=<game-id>

### Response:
```json
{"response":{"player_count":4517,"result":1}}
```
