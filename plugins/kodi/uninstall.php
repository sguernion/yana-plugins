<?php

require_once('KodiCmd.class.php');

// Suppression de la table du plugin
$table = new KodiCmd();
//$table->drop();

$execQuery = $table->query('DROP TABLE yana_plugin_kodi');

$table_configuration = new configuration();
$table_configuration->getAll();
$table_configuration->remove('plugin_kodiCmd_api_url_kodi');
$table_configuration->remove('plugin_kodiCmd_api_timeout_kodi');
$table_configuration->remove('plugin_kodiCmd_api_recognition_status');

// suppression de la piece KODI
$table_room = new Room();
$table_room->delete(array('name'=>'KODI'));

// Recuperation de l'id et Suppression de la section
$table_section = new Section();
$id_section = $table_section->load(array("label"=>"kodi"))->getId();
$table_section->delete(array('label'=>'kodi'));
        
// suppression des droits correspondant à la section
$table_right = new Right();
$table_right->delete(array('section'=>$id_section)); 

?>