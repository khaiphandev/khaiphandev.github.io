<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>JWPlayer with hls.js engine and P2P demo</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <script src="https://cdn.jsdelivr.net/npm/p2p-media-loader-core@latest/build/p2p-media-loader-core.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/p2p-media-loader-hlsjs@latest/build/p2p-media-loader-hlsjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@hola.org/jwplayer-hlsjs@latest/dist/jwplayer.hlsjs.min.js"></script>
    <script src="https://content.jwplatform.com/libraries/aG3IMhIy.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

</head>
<body>
    <div id="player">Loading...</div>

    <script>
        if (Hls.isSupported()) {
            var player = jwplayer("player");
            
            if (p2pml.hlsjs.Engine.isSupported()) {
            var engine = new p2pml.hlsjs.Engine();

            player.setup({
                file: "https://wowza.peer5.com/live/smil:bbb_abr.smil/playlist.m3u8"
            });

            jwplayer_hls_provider.attach();

            p2pml.hlsjs.initJwPlayer(player, {
                liveSyncDurationCount: 7, // To have at least 7 segments in queue
                loader: engine.createLoaderClass()
            });
          } else {
          player.setup({
                file: "https://wowza.peer5.com/live/smil:bbb_abr.smil/playlist.m3u8"
            });
          }
        } else {
            document.write("Not supported :(");
        }

    </script>

</body>
</html>
