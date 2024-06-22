<?php
include "googleroutesapi.php";
$coordinates_json = json_encode($coordinates);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <script crossorigin src="https://cdn.jsdelivr.net/npm/@babel/standalone@7/babel.min.js"></script>
    <script src="https://api-maps.yandex.ru/v3/?apikey=3aef3056-6610-4357-91f9-cef6e36abdaa&lang=en_US" type="text/javascript"></script>
</head>

<body>
    <div id="app"></div>
    <script>
        window.map = null;
        main();
        async function main() {
            await ymaps3.ready;
            const {
                YMap,
                YMapDefaultSchemeLayer,
                YMapDefaultFeaturesLayer
            } =
            ymaps3;
            const {
                YMapDefaultMarker
            } = await ymaps3.import(
                "@yandex/ymaps3-markers@0.0.1"
            );
            const markersGeoJsonSource = <?php echo $coordinates_json; ?>;
            map = new YMap(
                document.getElementById("app"), {
                    location: {
                        center: [32.8997638195335, 39.94556801742948],
                        zoom: 15
                    },
                    showScaleInCopyrights: true
                },
                [
                    new YMapDefaultSchemeLayer({}),
                    new YMapDefaultFeaturesLayer({}),
                ]
            );
            markersGeoJsonSource.forEach((markerSource) => {
                const marker = new YMapDefaultMarker(markerSource);
                map.addChild(marker);
            });
        }
    </script>
</body>

</html>