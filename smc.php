<?php
// SMC Tools V 1.0 Beta
system('clear'); //Linux
include "header.php";

function loop($url){
     $data = curl_init();
     curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($data, CURLOPT_URL, $url);
     curl_setopt($data, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Safari/537.36");
     $http = curl_getinfo($data, CURLINFO_HTTP_CODE);
     $output = curl_exec($data);
     curl_close($data);
     return $output;
}

// \033[92m[>_]\033[97m Pilih Options : 
                
function pilih(){
  echo "\n\033[91m---------------------------------------------
| \033[92mElige una opción :\033[91m                        |
|___________________________________________|
| \033[92m1. Obtener información Número de teléfono\033[91m |
| \033[92m2. Seguimiento de IP                      \033[91m|
| \033[92m3. Comprobar correo electrónico válido\033[91m    |
| \033[92m4. Detectar CMS\033[91m                           | 
| \033[92m5. Lookup Dominio\033[91m                         |
| \033[92m6. Información IP\033[91m                         |    
---------------------------------------------\033[97m\n";
}
while(true){
pilih();
echo "\033[92m[>_]\033[97m Elige una opción : ";
$c = trim(fgets(STDIN));
switch ($c){
  case 1:
    $phone = readline("Número de teléfono : ");
    echo "\033[92m[>_]\033[97m Obteniendo información Número de teléfono..\033[97m\n\n";
    // api sacada de https://numverify.com/
    $token = "3cdc7d946faa9e4ad32b57efdde98c8d";
    $responsephone = loop("http://apilayer.net/api/validate?access_key=$token&number=$phone&country_code=AR");
    $responsephone = json_decode($responsephone, TRUE);
    echo "\033[96m[INFORMACIÓN]\n\033[97m";
    echo "\033[92m[i]\033[97m Válido                : \033[95m ".$responsephone['valid']."\n";
    echo "\033[92m[i]\033[97m Número de teléfono    : \033[95m ".$responsephone['number']."\n";
    echo "\033[92m[i]\033[97m Formato local         : \033[95m ".$responsephone['local_format']."\n";
    echo "\033[92m[i]\033[97m Formato internacional : \033[95m ".$responsephone['international_format']."\n";
    echo "\033[92m[i]\033[97m Prefijo del país      : \033[95m ".$responsephone['country_prefix']."\n";
    echo "\033[92m[i]\033[97m Código de país        : \033[95m ".$responsephone['country_code']."\n";
    echo "\033[92m[i]\033[97m Nombre del país       : \033[95m ".$responsephone['country_name']."\n";
    echo "\033[92m[i]\033[97m Ubicación             : \033[95m ".$responsephone['location']."\n";
    echo "\033[92m[i]\033[97m Proveedor             : \033[95m ".$responsephone['carrier']."\n";
    echo "\033[92m[i]\033[97m Tipo de línea         : \033[95m ".$responsephone['line_type']."\n";
    break;
  case 2:
    $ip = readline("Dirección IP : ");
    echo "\033[92m[>_]\033[96m Obteniendo información IP.\033[97m\n\n";
    $responseip = loop("https://tools.keycdn.com/geo.json?host=$ip");
    $dec = json_decode($responseip);
    foreach($dec->data->geo as $key => $data){
      echo "\033[92m[i]\033[97m ".ucfirst(str_replace('_', ' ', $key))." : ".$data."\n";
      }
     //echo "\033[92m[i]\033[97m Google Maps : https://maps.google.com/maps?q=".$responseip['latitude'].",".$responseip['longitude']."&hl=ID;z=14&amp;output=embed\n";
    break;
  case 3:
    $email = readline("Email : ");
    echo "\033[92m[>_]\033[96m Chequeando si el Email es valido..\033[97m\n\n";
    $responseemail = loop("https://api.2ip.me/email.txt?email=$email");
    if (preg_match("/true/", $responseemail)){
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Email : \033[96m$email\033[97m - Email \033[92mValido [v]\033[97m\n";      
    }
    else if (preg_match("/Limit/", $responseemail)){
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Email : \033[96m$email - \033[91mLimit requests\n\033[97m";
    }
    else{
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Email : \033[96m$email\033[97m - Email \033[91mInvalido [x]\033[97m\n";
    }
    break;
  case 4:
    $site = readline("Sitio Web : ");
    echo "\033[92m[>_]\033[96m Detectando CMS..\033[97m\n\n";
    $responsesite = loop("$site");
    if (preg_match("/po-content|detailpost/", $responsesite)){ //Popoji
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mPopoji\n\033[97m";
    }
    else if(preg_match("/wp-content|wordpress|Wordpress|xmlrpc.php/", $responsesite)){ //Wordpress
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mWordpress\n\033[97m";
    }
    elseif(preg_match("/com_content|Joomla!|\/media\/system\/js\/|<script type=\"text\/javascript\" src=\"\/media\/system\/js\/mootools.js\"><\/script>/", $responsesite)){ //Joomla
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mJoomla\n\033[97m"; 
      }
    else if(preg_match("/Drupal|drupal|sites\/all|node|drupal.org/", $responsesite)){ //Drupal
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mDrupal\n\033[97m";
    }
    else if(preg_match("/Prestashop|prestashop/", $responsesite)){ //Prestashop
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mPrestashop\n\033[97m";
    }
    else if(preg_match("/foto_berita|foto_user|files|statis-|berita-/", $responsesite)){ //Lokomedia
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mLokomedia\n\033[97m";
    }
    else if(preg_match("/Interspire Email Marketer|Login with your username and password below|Take Me To/", $responsesite)){ //IEM
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mInterspire Email Marketer\n\033[97m";
    }
    else if(preg_match("/route=product|Powered by OpenCart/", $responsesite)){ //Open Cart
      echo "\033[96m[INFORMACIÓN]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mOpenCart\n\033[97m";
    }
    else if(preg_match("/Balitbang Kemdikbud|Tim Balitbang/", $responsesite)){ //Balitbang
      echo "\033[96m[Messages]\n";
      echo "\033[92m[>_]\033[97m Sitio Web : \033[96m$site\033[97m - CMS INFO \033[92mBalitbang\n\033[97m";
    }
    else{
      echo "\033[96m[Messages]\n";
      echo"\033[91m[-]\033[97m Sitio Web : \033[96m$site - CMS INFO \033[91mDesconocido/Error\n\033[97m";
    }
    break;
  case 5:
    $site2 = readline("Sitio Web : ");
    $lookupsite = loop("https://www.whoisxmlapi.com/whoisserver/WhoisService?apiKey=at_GZhISfBnRvfmABdgEKxhFK1GOnVWW&domainName=$site2");
    $xml = simplexml_load_string($lookupsite);
    $end = json_encode($xml);
    $declookup = json_decode($end, TRUE);
    echo "\033[96m[INFORMACIÓN]\n";
    echo "\n\033[96m[INFO DOMINIO]\n";
    echo "\033[92m[>_]\033[97m Dominio       : ".$declookup['domainName']."\n";
    echo "\033[92m[>_]\033[97m Registrado    : ".$declookup['registryData']['createdDate']."\n";
    echo "\033[92m[>_]\033[97m Actualizado   : ".$declookup['registryData']['updatedDate']."\n";
    echo "\033[92m[>_]\033[97m Expira        : ".$declookup['registryData']['expiresDate']."\n";
    echo "\n\033[96m[INFO REGISTRO]\n\033[97m";
    echo "\033[92m[>_]\033[97m Nombre        : ".$declookup['registrant']['name']."\n";
    echo "\033[92m[>_]\033[97m Organización  : ".$declookup['registrant']['organization']."\n";
    echo "\033[92m[>_]\033[97m Calle         : ".$declookup['registrant']['street1']."\n";
    echo "\033[92m[>_]\033[97m Ciudad        : ".$declookup['registrant']['city']."\n";
    echo "\033[92m[>_]\033[97m Región        : ".$declookup['registrant']['state']."\n";
    echo "\033[92m[>_]\033[97m Código Postal : ".$declookup['registrant']['postalCode']."\n";
    echo "\033[92m[>_]\033[97m País          : ".$declookup['registrant']['country']."\n";
    echo "\033[92m[>_]\033[97m Celular       : ".$declookup['registrant']['telephone']."\n";
    echo "\033[92m[>_]\033[97m Email         : ".$declookup['registrant']['email']."\n";
    echo "\n\033[96m[INFO NS]\n";
    echo "\033[92m[>_]\033[97m NS1        : ".$declookup['nameServers']['hostNames']['Address']['0']."\n";
    echo "\033[92m[>_]\033[97m NS2        : ".$declookup['nameServers']['hostNames']['Address']['1']."\n";
    break;
    echo "\033[91mIngrese una opción correctamente!\n\033[97m";
    break;
  case 6:
    $ipinf = readline("Dirección IP : ");
    echo "\033[92m[>_]\033[96m Obteniendo información IP.\033[97m\n\n";
    $tokenIP = "d6442e0cd8178e3fd8febc394c581ce3";
    $responseinfIP = loop("http://api.ipstack.com/$ipinf?access_key=$tokenIP&format=1");
    $responseinfIP = json_decode($responseinfIP, TRUE);
    echo "\033[96m[INFORMACIÓN]\n\033[97m";
    echo "\033[92m[i]\033[97m IP                    : \033[95m ".$responseinfIP['ip']."\n";
    echo "\033[92m[i]\033[97m Tipo                  : \033[95m ".$responseinfIP['type']."\n";
    echo "\033[92m[i]\033[97m Codigo Continente     : \033[95m ".$responseinfIP['continent_code']."\n";
    echo "\033[92m[i]\033[97m Nombre Continente     : \033[95m ".$responseinfIP['continent_name']."\n";
    echo "\033[92m[i]\033[97m Código País           : \033[95m ".$responseinfIP['country_code']."\n";
    echo "\033[92m[i]\033[97m Nombre País           : \033[95m ".$responseinfIP['country_name']."\n";
    echo "\033[92m[i]\033[97m Código Región         : \033[95m ".$responseinfIP['region_code']."\n";
    echo "\033[92m[i]\033[97m Nombre Regíon         : \033[95m ".$responseinfIP['region_name']."\n";
    echo "\033[92m[i]\033[97m Ciudad                : \033[95m ".$responseinfIP['city']."\n";
    echo "\033[92m[i]\033[97m Código Postal         : \033[95m ".$responseinfIP['zip']."\n";
    echo "\033[92m[i]\033[97m Coordenada Latitud    : \033[95m ".$responseinfIP['latitude']."\n";
    echo "\033[92m[i]\033[97m Coordenada Longitud   : \033[95m ".$responseinfIP['longitude']."\n";
    echo "\033[92m[i]\033[97m Capital               : \033[95m ".$responseinfIP['location']['capital']."\n";
    echo "\033[92m[i]\033[97m Codigo de Area        : \033[95m ".$responseinfIP['location']['calling_code']."\n";
    echo "\033[92m[i]\033[97m Google Maps : https://maps.google.com/maps?q=".$responseinfIP['latitude'].",".$responseinfIP['longitude']."&hl=ID;z=14&amp;output=embed\n";
    break;
    default:
}
}
?>
