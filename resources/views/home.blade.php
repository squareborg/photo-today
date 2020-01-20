<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="{{ asset('js/app.js') }}" defer></script>


        <!-- Styles -->
        <style>
            html, body {
                background-color: {{$bg ?? ''}};
            }

            .flex-row {
              display: flex;
              flex-direction: row;
              align-items: flex-start;
            }

            .justify-between {
              justify-content: space-between
            }

            .wrapper {
              margin-right: auto; /* 1 */
              margin-left:  auto; /* 1 */

              max-width: 1024px; /* 2 */

              padding-right: 10px; /* 3 */
              padding-left:  10px; /* 3 */
            }
            .photo {
                max-width: 100%;
                max-height: 100%;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
                overflow: auto;
                z-index: 0;
            }

            .video {
                max-width: 100%;
                max-height: 100%;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
                overflow: auto;
                z-index: 0;
            }

            .logo {
                max-width: 200px;
                max-height: 200px;
                top: 2em;
                left: 2em;
                z-index: 1;
                opacity: 0.9;
            }

            .video-control {
              top: 2em;
              right: 2em;
              color: white;
              z-index: 4;
            }

            .btn {
              padding: 0.75em;
              border: 1px solid;
              display: inline-block;
              border-radius: 5px;
              background-color: black;
              user-select: none;
              cursor: pointer;
              height: fit-content;
            }

            .video-button {
                z-index: 1;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <div class="wrapper">
                <image-area />
            </div>
        </div>
    </body>
</html>
