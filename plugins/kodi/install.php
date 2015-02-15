<?php
require_once('KodiCmd.class.php');
$table = new KodiCmd();
$table->create();

$s1 = New Section();
$s1->setLabel('kodi');
$s1->save();

$r1 = New Right();
$r1->setSection($s1->getId());
$r1->setRead('1');
$r1->setDelete('1');
$r1->setCreate('1');
$r1->setUpdate('1');
$r1->setRank('1');
$r1->save();

$conf = new Configuration();
$conf->put('plugin_kodiCmd_api_url_kodi','http://192.168.1.107:85/jsonrpc');
$conf->put('plugin_kodiCmd_api_timeout_kodi',5);
$conf->put('plugin_kodiCmd_api_recognition_status','');


$ro = New Room();
$ro->setName('KODI');
$ro->setDescription('De la bonne zic, un bon p\'tit film....');
$ro->save();

$roomManager = new Room();
$rooms = $roomManager->populate();
foreach($rooms as $room){
     if($room->getName() == "KODI"){ $kodiRoomId = $room->getId(); }    
}

$id=0;

$kodi = New KodiCmd();
$kodi->setName('à droite');
$kodi->setDescription('se déplacer à droite');
$kodi->setJson('"method":"Input.Right","id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('à gauche');
$kodi->setDescription('se déplacer à gauche');
$kodi->setJson('"method":"Input.Left","id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('en haut');
$kodi->setDescription('se déplacer en haut');
$kodi->setJson('"method":"Input.Up","id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('en bas');
$kodi->setDescription('se déplacer en bas');
$kodi->setJson('"method":"Input.Down","id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('ok');
$kodi->setDescription('valider');
$kodi->setJson('"method":"Input.Select","id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('retour');
$kodi->setDescription('retour en arrière');
$kodi->setJson('"method":"Input.Back","id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('info');
$kodi->setDescription('afficher les infos');
$kodi->setJson('"method":"Input.Info","id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('menu principal');
$kodi->setDescription('afficher le menu principal');
$kodi->setJson('"method":"Input.Home","id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('coupe le son');
$kodi->setDescription('je coupe le son...');
$kodi->setJson('"method":"Application.SetMute","params":{"mute":"toggle"},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('remet le son');
$kodi->setDescription('...et je remets le son');
$kodi->setJson('"method":"Application.SetMute","params":{"mute":"toggle"},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('volume trente');
$kodi->setDescription('volume du son à 30%');
$kodi->setJson('"method":"Application.SetVolume","params":{"volume":30},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('volume cinquante');
$kodi->setDescription('volume du son à 50%');
$kodi->setJson('"method":"Application.SetVolume","params":{"volume":50},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('volume soixante dix');
$kodi->setDescription('volume du son à 70%');
$kodi->setJson('"method":"Application.SetVolume","params":{"volume":70},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('volume cent');
$kodi->setDescription('volume du son à 100%');
$kodi->setJson('"method":"Application.SetVolume","params":{"volume":100},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('reprise');
$kodi->setDescription('lecture du media apres une pause ');
$kodi->setJson('"method":"Player.PlayPause","params":{"playerid":0},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('pause');
$kodi->setDescription('mettre en pause le media');
$kodi->setJson('"method":"Player.PlayPause","params":{"playerid":0},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();


$kodi = New KodiCmd();
$kodi->setName('suivant');
$kodi->setDescription('média suivant');
$kodi->setJson('"method":"Player.GoTo","params":{"playerid":0,"to":"next"},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('précédant');
$kodi->setDescription('media précédent');
$kodi->setJson('"method":"Player.GoTo","params":{"playerid":0,"to":"previous"},"id":"1"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

$kodi = New KodiCmd();
$kodi->setName('info lecture en cours');
$kodi->setDescription('donne des infos');
$kodi->setJson('"method":"Player.GetItem","params":{"properties":["title","streamdetails"],"playerid":0},"id":"AudioGetItem"');
$kodi->setConfidence('0.8');
$kodi->setRoom($kodiRoomId);
$kodi->save();

?>
