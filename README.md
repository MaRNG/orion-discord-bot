# discord-bot-steam
Discord bot for Steam API endpoints

## Setup local config
### File config
You have to create `creds.json` file in `src/App/Config/`. 
Then you can run bot via `start.sh` shell script.

#### File content
```json
{
  "token": "xxxx",
  "steam": {
    "apiKey": "xxxx"
  }
}
```

### Environment variable config
You can use environment variable as config. This comes handy, while you are using bot via Docker.

#### Variables
 * **botToken** - for Discord bot token generated from admin section of Discord
 * **steamApiKey** - for Steam API generated in Steam

## Steam commands examples
### Searching games
https://steamcommunity.com/actions/SearchApps/(game-name)

#### Response:
```json
[{"appid":"1517290","name":"Battlefield™ 2042","icon":"https:\/\/cdn.cloudflare.steamstatic.com\/steamcommunity\/public\/images\/apps\/1517290\/dc805cd05c36a1b26f4eb57b64301e6708e20776.jpg","logo":"https:\/\/cdn.cloudflare.steamstatic.com\/steam\/apps\/1517290\/capsule_184x69.jpg"}]
```

### Current player count for game
https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1/?key=(api-key)&appid=(game-id)

#### Response:
```json
{"response":{"player_count":4517,"result":1}}
```
