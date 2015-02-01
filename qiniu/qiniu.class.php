<?php
require_once("qiniu/io.php");
require_once("qiniu/rs.php");
require_once("curl.class.php");

class Qiniu
{
    private $_akey = '<yourAKEY>';
    private $_skey = '<yourSKEY>';
    private $_domain = '<yourdomain>';
    private $_bucket = '<yourbucket>';

    static $_client = null;
    static $_bucket = null;
    static $_domain = null;
    static $_curl   = null;
    static $_getPolicy = null;
    static $_putPolicy = null;
    static $_putExtra = null;
    
    public function __construct()
    {
        Qiniu_SetKeys($this->_akey, $this->_skey);
        //self::$_client = new Qiniu
    }

    public function download($key, $domain) {
        $domain = ($domain == '') ? $this->_domain : $domain;
        if(self::$_curl == null)
            self::$_curl = new CurlWrapper();
        if(self::$_getPolicy == null)
            self::$_getPolicy = new Qiniu_RS_GetPolicy();
        $baseUrl = Qiniu_RS_MakeBaseUrl($domain, $key);
        $privateUrl = self::$_getPolicy->MakeRequest($baseUrl, null);
       // echo "<img  src=\"{$privateUrl}\" width=\"480\" height=\"480\">click</img>";
        return $privateUrl;
    }

    public function upload($key, $bucket){
        $bucket = ($bucket == '') ? $this->_bucket : $bucket;
        if(self::$_putPolicy == null)
            self::$_putPolicy = new Qiniu_RS_PutPolicy($bucket);

        if(self::$_putExtra == null){
            self::$_putExtra = new Qiniu_PutExtra();
            self::$_putExtra->Crc32 = 1;
        }
        $upToken = self::$_putPolicy->Token(null);
        list($ret, $err) = Qiniu_PutFile($upToken, $key, __file__, self::$_putExtra);
        if ($err !== null)
            return false;
        return true;
    }

}

?>
