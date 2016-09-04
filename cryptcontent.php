<?php

include 'dbconnection.php';

//Used for content two way encryption

class Crypt
{
    private $secretkey = 'ENTER SECRET KEY';

    //Encrypts a string
    //MCRYPT_RIJNDAEL_128 is AES compliant
    public function encrypt($text)
    {
        $data = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->secretkey, $text, MCRYPT_MODE_ECB);

        return base64_encode($data);
    }

    //Decrypts a string
    public function decrypt($text)
    {
        $text = base64_decode($text);

        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->secretkey, $text, MCRYPT_MODE_ECB);
    }
}
