# Tor Php Curl
# Simple | Php Tor Proxy Connector

## Install and configure Tor
### Ubuntu/Debian
sudo apt-get install tor

nano /etc/tor/torrc 

Uncomment the : ControlPort 9051

## Example usage | PHP 
```php
require 'Tor.php';

$Url = "https://api.ipify.org";
$Tor = new Tor($Url);
$TorConnect = $Tor->TorConnect();

echo $TorConnect;
```
