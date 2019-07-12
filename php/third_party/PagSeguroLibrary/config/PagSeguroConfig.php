<?php

/*
 ************************************************************************
 PagSeguro Config File
 ************************************************************************
 */

$PagSeguroConfig = array();

$PagSeguroConfig['environment'] = "production"; // production, sandbox

$PagSeguroConfig['credentials'] = array();
// $PagSeguroConfig['credentials']['email'] = "wn_br@hotmail.com";
// $PagSeguroConfig['credentials']['token']['production'] = "5973A2657EFF40AC89905B6F55F7F923";

// $PagSeguroConfig['credentials']['email'] = "leonardo.bady03@gmail.com";
//$PagSeguroConfig['credentials']['token']['production'] = "24E4802E80514B1CA8BFAC6F328A2FA7";

$PagSeguroConfig['credentials']['email'] = "pagseguro@zedoingresso.com.br";
$PagSeguroConfig['credentials']['token']['production'] = "bbbe064a-5f2b-4c62-9373-f45e893383b90e0c47964ec987ca47cd050f69465cfb5b9e-5254-4c09-9a7c-9eee9727a7f0";



$PagSeguroConfig['credentials']['token']['sandbox'] = "FBA61CD5BD5C455095C9417D3D0A3652";

$PagSeguroConfig['application'] = array();
$PagSeguroConfig['application']['charset'] = "UTF-8"; // UTF-8, ISO-8859-1

$PagSeguroConfig['log'] = array();
$PagSeguroConfig['log']['active'] = false;
$PagSeguroConfig['log']['fileLocation'] = "";
