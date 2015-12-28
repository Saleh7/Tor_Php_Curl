<?php
/**
 * Php Tor Proxy Connector .
 *
 * @package : Tor;
 * @author : Saleh Bin Homud;
 * @version : 1.0;
 * @link : https://github.com/Saleh7;
 */
 
class Tor{
  
  // localhost is a hostname that means this computer or this host
  private $ip = '127.0.0.1';
  // $Port     | SOCKS connections on port 9050
  // $Port     | Tor Browser listens on port 9150
  // $Port     | https://www.torproject.org/docs/faq.html.en#TBBSocksPort
  private $Port = '9050'; 
  // $AuthPass | Connect to the TOR server using password authentication
  // $AuthPass | tor --hash-password PASSWORD 
  private $AuthPass = '';
  // Renew identity
  private $Command = 'signal NEWNYM';
  // Limits the maximum execution time
  private $Timeout = 40;
  private $Url;

  public function __construct($Url){
    $this->Url = $Url;
    $this->TorSwitchIdentity();
    $this->TorConnect();
  }

  public function TorConnect(){
    $Curl = curl_init();
    curl_setopt($Curl, CURLOPT_PROXY, $this->ip.":".$this->Port);
    curl_setopt($Curl, CURLOPT_URL, $this->Url);
    //curl_setopt($Curl, CURLOPT_HEADER, 1);
    curl_setopt($Curl, CURLOPT_USERAGENT, $this->TorUserAgent());
    curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($Curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    curl_setopt($Curl, CURLOPT_TIMEOUT, $this->Timeout);
    $Response = curl_exec($Curl);
    $Error    = curl_error($Curl);
    curl_close($Curl);
    return $Response;
  }

  private function TorUserAgent(){
     //list of browsers
     $TorAgentBrowser = array('Firefox','Safari','Opera','Flock',
     'Internet Explorer','Seamonkey','Konqueror','GoogleBot'
      );
     //list of operating systems
     $TorAgentOS = array('Windows 3.1','Windows 95','Windows 98',
     'Windows 2000','Windows NT','Windows XP','Windows Vista',
     'Redhat Linux','Ubuntu','Fedora','AmigaOS','OS 10.5'
     );
     //randomly generate UserAgent
     return $TorAgentBrowser[rand(0,7)].'/'.rand(1,8).'.'.rand(0,9).' (' .$TorAgentOS[rand(0,11)].' '.rand(1,7).'.'.rand(0,9).'; en-US;)';
  }

  private function TorSwitchIdentity(){
    $fp = fsockopen($this->ip,$this->Port,$ErrorNum,$ErrorStr,10);
    if(!$fp) { echo "ERROR: $ErrorNum : $ErrorStr";
        return false;
    } else {
        fwrite($fp,"AUTHENTICATE \"".$this->AuthPass."\"\n");
        $Rece = fread($fp,512);
        fwrite($fp,$this->Command."\n");
        $Rece = fread($fp,512);
    }
  }

}

?>
