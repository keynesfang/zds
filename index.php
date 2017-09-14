<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript">
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
        window.URL = window.URL || window.webkitURL || window.mozURL || window.msURL;
        function getUserMedia(constraints, success, failure) {
            navigator.getUserMedia(constraints, function (stream) {
                var videoSrc = (window.URL && window.URL.createObjectURL(stream)) || stream;
                success.apply(null, [videoSrc]);
            }, failure);
        }
        function initCamera(constraints, video, callback) {
            getUserMedia(constraints, function (src) {
                video.src = src;
                video.addEventListener('loadeddata', function () {
                    var attempts = 10;

                    function checkVideo() {
                        if (attempts > 0) {
                            if (video.videoWidth > 0 && video.videoHeight > 0) {
                                console.log(video.videoWidth + "px x " + video.videoHeight + "px");
                                video.play();
                                callback();
                            } else {
                                window.setTimeout(checkVideo, 100);
                            }
                        } else {
                            callback('Unable to play video stream.');
                        }
                        attempts--;
                    }

                    checkVideo();
                }, false);
            }, function (e) {
                console.log(e);
            });
        }
        function copyToCanvas(video, ctx) {
            ( function frame() {
                ctx.drawImage(video, 0, 0);
                window.requestAnimationFrame(frame);
            }());
        }
        window.addEventListener('load', function () {
            var constraints = {
                    video: {
                        mandatory: {
                            minWidth: 1280,
                            minHeight: 720
                        }
                    }
                },
                video = document.createElement('video'),
                canvas = document.createElement('canvas');
            document.body.appendChild(video);
            document.body.appendChild(canvas);
            initCamera(constraints, video, function () {
                canvas.setAttribute('width', video.videoWidth);
                canvas.setAttribute('height', video.videoHeight);
                copyToCanvas(video, canvas.getContext('2d'));
            });
        }, false);
    </script>
</head>
<body>
<header>
    <div class="headline">
        <h1>QuaggaJS</h1>
        <h2>An advanced barcode-scanner written in JavaScript</h2>
    </div>
</header>
<section id="container" class="container">
    <h3>The user's camera</h3>
    <p>If your platform supports the <strong>getUserMedia</strong> API call, you can try the real-time locating and
        decoding features.
        Simply allow the page to access your web-cam and point it to a barcode. You can switch between
        <strong>Code128</strong>
        and <strong>EAN</strong> to test different scenarios.
        It works best if your camera has built-in auto-focus.
    </p>
    <div class="controls">
        <fieldset class="input-group">
            <button class="stop">Stop</button>
        </fieldset>
        <fieldset class="reader-config-group">
            <label>
                <span>Barcode-Type</span>
                <select name="decoder_readers">
                    <option value="code_128" selected="selected">Code 128</option>
                    <option value="code_39">Code 39</option>
                    <option value="code_39_vin">Code 39 VIN</option>
                    <option value="ean">EAN</option>
                    <option value="ean_extended">EAN-extended</option>
                    <option value="ean_8">EAN-8</option>
                    <option value="upc">UPC</option>
                    <option value="upc_e">UPC-E</option>
                    <option value="codabar">Codabar</option>
                    <option value="i2of5">Interleaved 2 of 5</option>
                    <option value="2of5">Standard 2 of 5</option>
                    <option value="code_93">Code 93</option>
                </select>
            </label>
            <label>
                <span>Resolution (width)</span>
                <select name="input-stream_constraints">
                    <option value="320x240">320px</option>
                    <option selected="selected" value="640x480">640px</option>
                    <option value="800x600">800px</option>
                    <option value="1280x720">1280px</option>
                    <option value="1600x960">1600px</option>
                    <option value="1920x1080">1920px</option>
                </select>
            </label>
            <label>
                <span>Patch-Size</span>
                <select name="locator_patch-size">
                    <option value="x-small">x-small</option>
                    <option value="small">small</option>
                    <option selected="selected" value="medium">medium</option>
                    <option value="large">large</option>
                    <option value="x-large">x-large</option>
                </select>
            </label>
            <label>
                <span>Half-Sample</span>
                <input type="checkbox" checked="checked" name="locator_half-sample"/>
            </label>
            <label>
                <span>Workers</span>
                <select name="numOfWorkers">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option selected="selected" value="4">4</option>
                    <option value="8">8</option>
                </select>
            </label>
            <label>
                <span>Camera</span>
                <select name="input-stream_constraints" id="deviceSelection">
                </select>
            </label>
            <label style="display: none">
                <span>Zoom</span>
                <select name="settings_zoom"></select>
            </label>
            <label style="display: none">
                <span>Torch</span>
                <input type="checkbox" name="settings_torch"/>
            </label>
        </fieldset>
    </div>
    <div id="result_strip">
        <ul class="thumbnails"></ul>
        <ul class="collector"></ul>
    </div>
    <div id="interactive" class="viewport"></div>
</section>
<footer>
    <p>
        &copy; Made with ❤️ by Christoph Oberhofer
    </p>
</footer>
<script src="./js/jquery-3.2.1.min.js"></script>
<script src="./js/JsBarcode.all.min.js"></script>  <!-- https://github.com/lindell/JsBarcode 生成条形码-->
<script src="./js/quagga.min.js"></script>  <!-- https://github.com/serratus/quaggaJS 扫描条形码-->
<script src="./js/live_w_locator.js" type="text/javascript"></script>
</body>
</html>