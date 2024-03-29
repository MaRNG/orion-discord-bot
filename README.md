# discord-bot-steam
Discord bot, that search for game and show how many players are playing the game currently.

Bot has only one command `/player-count <game-name>`.

To run bot, you need first setup config, then you can
start bot via shell script `start-detached.sh`.

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

#### Docker environments variables
You have to create `.env` file.

##### .env content
```env
botToken=xxxx
steamApiKey=xxxx
```

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
