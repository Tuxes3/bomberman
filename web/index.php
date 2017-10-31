<?php
/*
 * Copyright (c) 2017, whatwedo GmbH
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
?>
<!doctype html>
<html>
    <head>
        <style>
            .block {
                width: 30px;
                height: 30px;
                border: 1px solid black;
                float: left;
            }
            .clear {
                clear: both;
            }
        </style>
    </head>
    <body>
        <div id="field"></div>

        <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
        <script type="text/javascript">
            var conn = new WebSocket('ws://localhost:8009');
            conn.onopen = function(e) {
                console.log("Connection established!");
            };

            conn.onmessage = function(e) {
                var field = JSON.parse(e.data);
                console.log(field);
                var fieldDiv = $('#field');
                fieldDiv.empty();
                console.log(field);
                for (var i = 0; i < field.field.length; i++) {
                    for (var j = 0; j < field.field[i].length; j++) {
                        var onField = $('<div class="block">');
                        onField.css('background-color', field.field[i][j].color);
                        fieldDiv.append(onField);
                    }
                    fieldDiv.append($('<div class="clear">'));
                }
            };
            $(document).keypress(function(e) {
                if (e.keyCode === 119) { // w
                    conn.send('w');
                } else if (e.keyCode === 97) { // a
                    conn.send('a');
                } else if (e.keyCode === 115) { // s
                    conn.send('s');
                } else if (e.keyCode === 100) { // d
                    conn.send('d');
                }
            });
        </script>
    </body>
</html>