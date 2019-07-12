<?php

/*
 ************************************************************************
 PagSeguro Config File
 ************************************************************************
 */

$PagSeguroConfig = array();

$PagSeguroConfig['environment'] = "production"; // production, sandbox

$PagSeguroConfig['credentials'] = array();
$PagSeguroConfig['credentials']['email'] = "wn_br@hotmail.com";
$PagSeguroConfig['credentials']['token']['production'] = "5973A2657EFF40AC89905B6F55F7F923";
$PagSeguroConfig['credentials']['token']['sandbox'] = "FBA61CD5BD5C455095C9417D3D0A3652";

$PagSeguroConfig['application'] = array();
$PagSeguroConfig['application']['charset'] = "UTF-8"; // UTF-8, ISO-8859-1

$PagSeguroConfig['log'] = array();
$PagSeguroConfig['log']['active'] = false;
$PagSeguroConfig['log']['fileLocation'] = "";
