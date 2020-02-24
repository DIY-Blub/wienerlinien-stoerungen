<?php

# die Linien in diesem Array werden immer ganz oben angezeigt
$wichtigeLinien = array("U1","U2","U3","U4","U5","U6","13A",6,62);


date_default_timezone_set("Europe/Vienna");

?>
<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
        body {
          margin: 10px auto;
          padding: 10px;
          max-width: 60em;
        }
        * {
          padding: 0px;
          margin: 0px;
        }
        span {
            font-weight: normal;
        }
        hr.trennlinie {
            border-style: dashed;
            margin-bottom: 5px;
        }
        div.abfahrtszeit {
          font-size:10px;
          position:absolute;
          top:2px;
          right:4px;
        }
        div.stoerung {
            margin: 10px 0px 5px;
            padding: 0px;
        }
        p.titel {
            font-size: 14px;
            font-weight: bold;
        }
        </style>
        <title>WienerLinien</title>
    </head>
<body>
    <h3>Wiener Linien</h3>
    <hr class="trennlinie">
    <h4>Störungen & Verspätungen</h4>
<?

$data_Stoerungen = file_get_contents("http://www.wienerlinien.at/ogd_realtime/trafficInfoList?name=stoerunglang");
$data_Stoerungen = json_decode($data_Stoerungen, true);

if (array_key_exists("trafficInfos", $data_Stoerungen['data'])) {
#    echo '<h4><u>relevante Verbindungen</u></h4>';
    foreach ($data_Stoerungen["data"]["trafficInfos"] as $linie) {
        if (in_array(key($linie["attributes"]["relatedLineTypes"]), $wichtigeLinien)) {
            echo '<div class="stoerung"><p class="titel">Linie '.$linie["title"].' <span>(seit '.substr($linie["time"]["start"],11,5).' Uhr)</span></p>';
            echo '<p style=""><u>Beschreibung:</u> '.$linie["description"].'</p></div>';
        }
    }
#    echo '<hr class="trennlinie">';
#    echo '<h4><u>andere Verbindungen</u></h4>';
    foreach ($data_Stoerungen["data"]["trafficInfos"] as $linie) {
        if (!in_array(key($linie["attributes"]["relatedLineTypes"]), $wichtigeLinien)) {
            echo '<div class="stoerung"><p class="titel">Linie '.$linie["title"].' <span>(seit '.substr($linie["time"]["start"],11,5).' Uhr)</span></p>';
            echo '<p style=""><u>Beschreibung:</u> '.$linie["description"].'</p></div>';
        }
    }
} else {
    echo '<p>keine Störungen vorhanden</p>';
}


# eine Abfrage der Abfahrtszeit
/*
echo '<div class="abfahrtszeit"><i>U6 Perfektastraße nach Floridsdorf,</i><br>';

$data_abfahrtszeit = file_get_contents("http://www.wienerlinien.at/ogd_realtime/monitor?rbl=4636");
$data_abfahrtszeit = json_decode($data_abfahrtszeit, true);

    # DEBUGGING
#    echo '<br>';
#    echo '<br>';
#    echo '<pre>';
#    echo print_r($data_abfahrtszeit);
#    echo '</pre>';



if (array_key_exists("vehicle",$data_abfahrtszeit["data"]["monitors"][0]["lines"][0]["departures"]["departure"][0])) {
    echo $data_abfahrtszeit["data"]["monitors"][0]["lines"][0]["departures"]["departure"][0]["vehicle"]["towards"];
} elseif (array_key_exists("departureTime",$data_abfahrtszeit["data"]["monitors"][0]["lines"][0]["departures"]["departure"][0])) {
    echo 'nächste Abfahrt in '.$data_abfahrtszeit["data"]["monitors"][0]["lines"][0]["departures"]["departure"][0]["departureTime"]["countdown"].' Minuten';
} else {
    echo 'Abfrage nicht möglich';
}

echo '</div>';
*/

?>
</body>
</html>
<?

?>
