<?php
/**
 * 2007-2014 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2007-2014 PagSeguro Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

$PagSeguroConfig = array();

$PagSeguroConfig['environment'] = "sandbox"; // production, sandbox

$PagSeguroConfig['credentials'] = array();
$PagSeguroConfig['credentials']['email'] = "wn_br@hotmail.com";
$PagSeguroConfig['credentials']['token']['production'] = "5973A2657EFF40AC89905B6F55F7F923";
$PagSeguroConfig['credentials']['token']['sandbox'] = "FBA61CD5BD5C455095C9417D3D0A3652";
$PagSeguroConfig['credentials']['appId']['production'] = "";
$PagSeguroConfig['credentials']['appId']['sandbox'] = "app4463619587";
$PagSeguroConfig['credentials']['appKey']['production'] = "";
$PagSeguroConfig['credentials']['appKey']['sandbox'] = "6F1B097BAFAF0BE114EBBFB9FA28B852";

$PagSeguroConfig['application'] = array();
$PagSeguroConfig['application']['charset'] = "UTF-8"; // UTF-8, ISO-8859-1

$PagSeguroConfig['log'] = array();
$PagSeguroConfig['log']['active'] = false;
// Informe o path completo (relativo ao path da lib) para o arquivo, ex.: ../PagSeguroLibrary/logs.txt
$PagSeguroConfig['log']['fileLocation'] = "";
