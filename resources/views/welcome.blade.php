

<!DOCTYPE html>
<html>
<head>
    <title>Embed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="robots" content="noindex" />
    <style type="text/css">
        body,html {
            margin:0;
            padding:0
        }
        #previewPlayer {
            position:absolute;
            width:100%important!;
            height:100%important!;
            border:none;
            overflow:hidden;
        }
        #frameSlider {
            width:100%
        }
        .jwplayer.jw-state-buffering .jw-display-icon-display .jw-icon {
            -webkit-animation:spin 2s linear infinite;
            animation:spin 2s linear infinite;
        }
        .jw-progress {
            background-color: #cc0000!important;
        }
        .jw-skin-vapor .jw-rail {
            background: rgba(2, 36, 134, 0.76)!important;
        }
        .jw-icon-rewind {
            display:none!important;
        }
        #first {
            display:none
        }
    </style>
    <script src="{{ asset('js/jwplayer.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        jwplayer.key = "ITWMv7t88JGzI0xPwW8I0+LveiXX9SWbfdmt0ArUSyc=";
    </script>
    <script src="//cdn.jsdelivr.net/npm/devtools-detector"></script>
    <script type="text/javascript">

    </script>
</head>
<body>
<div id="previewPlayer"></div>
<script>
    const url = "test.m3u8"

    var videoPlayer = jwplayer("previewPlayer");
    class player {
        async initJwPlayer() {
            videoPlayer.setup({
                sources: [{"file":`/${url}`,"type":"hls"}],
                width: '100%',
                height: '100%',
                primary: 'html5',
                volume: 100,
                displaytitle: false,
                autostart: false,
                playbackRateControls: true,
                mute: false
            });

            videoPlayer.addButton('{{ asset('forward.svg') }}', " ข้ามไป 10 วินาที", function () {
                videoPlayer.seek(videoPlayer.getPosition() + 10);
            }, "ข้ามไป 10 วินาที");
            videoPlayer.addButton('{{ asset('backward.svg') }}', "ย้อนกลับ 10 วินาที", function () {
                videoPlayer.seek(videoPlayer.getPosition() - 10);
            }, "ย้อนกลับ 10 วินาที");
                    }

    }

    const P2PPlayer = new player();

    P2PPlayer.initJwPlayer();

</script>
</body>
</html>
