<?php
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/geral.php");

// crypt class for SHA512 double salt encripting passwords
// Author: Oriel Frigo

class crypt
{
 
function __construct()
    {
        // static salt (56 bytes)
        $this->fs = 'welEJSaioBLyTEjwaQNqmNnljdYbY_kDyOmVhAIxEzWkAbWTlCuhg_cA';
    }

function random_salt()
{
  // random salt stored on database (8 bytes)
  return random_str(8);
}

// this function register a new encrypted password
function register($pass)
{
  $rs = $this->random_salt();
  $enc = hash("sha512",$rs.$pass); // salt 1
  $enc = hash("sha512",$this->fs.$enc); // salt 2
  $this->password = $enc;
  $this->saltdb = $rs;
}

// this function compare the input password with the database one
function compare($saltdb, $input, $passdb)
{
  $enc_input = hash("sha512",$saltdb.$input); // salt 1
  $enc_input = hash("sha512",$this->fs.$enc_input); // salt 2
  return ($enc_input == $passdb);
}
}
  

?>