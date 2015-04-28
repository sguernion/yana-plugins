<?php
/*
@name KODI
@author S.Guernion <email@gmail.com>
@author Stéphane BOLLARD <fafnus@yahoo.fr>
@link https://github.com/sguernion/yana-plugins/tree/master/plugins/kodi
@licence CC by nc sa
@version 2.0.4
@description Plugin permettant la reconnaissance vocale du Media-Center KODI.

*/

include('KodiCmd.class.php');
 


function kodi_plugin_setting_page(){
	global $_,$myUser;
	if(isset($_['section']) && $_['section']=='kodiCmd' ){

		if($myUser!=false){
			$kodiManager = new KodiCmd();
			$kodiCmds = $kodiManager->populate();
			$roomManager = new Room();
			$rooms = $roomManager->populate();
	?>

		<div class="span9 userBloc">


		<h1>KODI</h1>
		<p>Gestion media-center</p>  
		<ul class="nav nav-tabs">
			<li <?php echo (!isset($_['block']) || $_['block']=='cmd'?'class="active"':'')?> > <a href="setting.php?section=kodiCmd&amp;block=cmd"><i class="fa fa-angle-right"></i> Commandes Vocales</a></li>
			<li <?php echo (isset($_['block']) && $_['block']=='new'?'class="active"':'')?> > <a href="setting.php?section=kodiCmd&amp;block=new"><i class="fa fa-angle-right"></i> Ajouter une Commande</a></li>
		</ul>

		<?php 
		 if((isset($_['section']) && $_['section']=='kodiCmd' && (@$_['block']=='new' ))  ){
		?>
		<form action="action.php?action=kodiCmd_add_kodiCmd" method="POST">
		<fieldset>
		    <div class="left">
			    <label for="name">Nom</label>
			    <input type="text" id="name" onkeyup="$('#vocalCommand').html($(this).val());" name="name" placeholder="musique suivante"/> <small>Commande vocale associée : "<span id="vocalCommand"></span>"</small></label>
			    
			    <label for="description">Description</label>
			    <input type="text" name="description" id="description" placeholder="Lire la prochaine musique" />
			    <label for="kodiJsonCode">Code json de la commande</label>
			    <input type="text" name="kodiJsonCode" id="kodiJsonCode" placeholder='"method":"Input.Right","id":"1"' /> <a href="http://wiki.kodi.org/index.php?title=JSON-RPC_API/v6" target="_blank" >JSON-RPC_API-V6-WIKI </a>
                            <label for="room">Pièce</label>
                            <select name="room" id="room">
			    	<?php foreach($rooms as $room){ ?>
			    	<option value="<?php echo $room->getId(); ?>" <?php echo ($room->getName() == "KODI")?"selected":""; ?>><?php echo $room->getName(); ?></option>
			    	<?php } ?>
			    </select>   
                            <label for="confidence">Confidence</label>
                            <select name="confidence" id="confidence">
                                <?php for($confidence=1; $confidence<=9; $confidence++){ ?>                                
                                    <option value=0.<?php echo $confidence ?> <?php echo ($confidence == 8)?"selected":""; ?>>0.<?php echo $confidence; ?></option>
                                <?php } ?>
                            </select>                          
			</div>

  			<div class="clear"></div>
		    <br/><button type="submit" class="btn">Ajouter</button>
	  	</fieldset>
		<br/>
	</form>
	
		<?php 
		}
		 if((isset($_['section']) && $_['section']=='kodiCmd' && (@$_['block']=='edit' ))  ){
		 
			$kodiCmd = new KodiCmd();
				$kodiCmd = $kodiCmd->getById($_['id']);
		?>
		<form action="action.php?action=kodiCmd_edit_kodiCmd" method="POST">
		<fieldset>
		    <div class="left">
			    <label for="name">Nom</label>
				<input type="hidden" name="id" value="<? echo $kodiCmd->getId(); ?>" />
			    <input type="text" id="name" onkeyup="$('#vocalCommand').html($(this).val());" value="<? echo $kodiCmd->getName(); ?>" name="name" /> <small>Commande vocale associée : "<span id="vocalCommand"></span>"</small></label>
			    
			    <label for="description">Description</label>
			    <input type="text" name="description" value="<? echo $kodiCmd->getDescription(); ?>" id="description"  />
			    <label for="kodiJsonCode">Code json de la commande</label>
			    <input type="text" name="kodiJsonCode" id="kodiJsonCode" value='<? echo $kodiCmd->getJson(); ?>' /> <a href="http://wiki.kodi.org/index.php?title=JSON-RPC_API/v6" target="_blank" >JSON-RPC_API-V6-WIKI </a>
                <label for="room">Pièce</label>
                <select name="room" id="room">
			    	<?php foreach($rooms as $room){ ?>
			    	<option value="<?php echo $room->getId(); ?>" <?php echo ($room->getId() == $kodiCmd->getRoom())?"selected":""; ?>><?php echo $room->getName(); ?></option>
			    	<?php } ?>
			    </select>   
                <label for="confidence">Confidence</label>
                <select name="confidence" id="confidence">
                                <?php for($confidence=1; $confidence<=9; $confidence++){ ?>                                
                                    <option value=0.<?php echo $confidence ?> <?php echo ('0.'.$confidence == $kodiCmd->getConfidence())?"selected":""; ?>>0.<?php echo $confidence; ?></option>
                                <?php } ?>
                </select>                          
			</div>

  			<div class="clear"></div>
		    <br/><button type="submit" class="btn">Modifier</button>
	  	</fieldset>
		<br/>
	</form>
	
		<?php 
		}
		 if((isset($_['section']) && $_['section']=='kodiCmd' && (@$_['block']=='cmd'  || @$_['block']==''))  ){
		?>

		<table class="table table-striped table-bordered table-hover">
	    <thead>
	    <tr>
	    	<th>Nom</th>
		    <th>Description</th>
		    <th>Code json de la commande</th>
            <th>Confidence</th>
		    <th>Pièce</th>
			<th>Actions</th>
	    </tr>
	    </thead>
	    
	    <?php foreach($kodiCmds as $kodiCmd){ 

	    	$room = $roomManager->load(array('id'=>$kodiCmd->getRoom())); 
	    	?>
	    <tr>
	    	<td><?php echo $kodiCmd->getName(); ?></td>
		    <td><?php echo $kodiCmd->getDescription(); ?></td>
		    <td><?php echo $kodiCmd->getJson(); ?></td>  
            <td><?php echo $kodiCmd->getConfidence(); ?></td>
		    <td><?php echo $room->getName(); ?></td>                    
            <td>
				<a class="btn" href="setting.php?section=kodiCmd&amp;block=edit&id=<?php echo $kodiCmd->getId(); ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
										
				<a class="btn" href="action.php?action=kodiCmd_delete_kodiCmd&id=<?php echo $kodiCmd->getId(); ?>"><i class="fa fa-trash-o fa-lg"></i></a></td>
	    </tr>
	    <?php } ?>
	    </table>
		</div>

<?php 
}
}else{ 	header('location:index.php?connexion=ko');}
	}

}

function kodiCmd_plugin_setting_menu(){
	global $_;
	echo '<li '.(isset($_['section']) && $_['section']=='kodiCmd'?'class="active"':'').'><a href="setting.php?section=kodiCmd"><i class="fa fa-angle-right"></i> KODI </a></li>';
}




function kodiCmd_display($room){
	global $_,$conf;


	$kodiManager = new KodiCmd();
	$kodiCmds = $kodiManager->loadAll(array('room'=>$room->getId()));
	
	foreach ($kodiCmds as $kodiCmd) {
			
	?>

	<div class="span3">
          <h5><?php echo $kodiCmd->getName() ?></h5>
		   
		   <p><?php $desc=$kodiCmd->getDescription(); echo (!empty($desc))? $desc:'<br>'; ?>
		  	</p><ul>
		  		<li>Code json: <code><?php echo $kodiCmd->getJson() ?></code></li> 
                                <li>Confidence: <code><?php echo $kodiCmd->getConfidence() ?></code></li>
		  	</ul>
		  <p></p>
		  	 <div class="btn-toolbar">
				<div class="btn-group"> 
				<a class="btn btn-success" href="action.php?action=kodiCmd_change_state&engine=<?php echo $kodiCmd->getId() ?>"><i class="icon-thumbs-up icon-white"></i></a>
				</div>
			</div>   
        </div>


	<?php
	}
}

function kodiCmd_vocal_command(&$response,$actionUrl){
        global $_,$conf;
        
        $response['commands'][] = array('command'=>$conf->get('VOCAL_ENTITY_NAME').', active KODI','url'=>$actionUrl.'?action=kodiCmd_recognition_status&state=1&webservice=true','confidence'=>0.8);
        $response['commands'][] = array('command'=>$conf->get('VOCAL_ENTITY_NAME').', désactive KODI','url'=>$actionUrl.'?action=kodiCmd_recognition_status&state=0&webservice=true','confidence'=>0.8);
 
	$kodiManager = new KodiCmd();
	$kodiCmds = $kodiManager->populate();
	foreach($kodiCmds as $kodiCmd){
            
                $response['commands'][] = array('command'=>''.$kodiCmd->getName(),'url'=>$actionUrl.'?action=kodiCmd_change_state&engine='.$kodiCmd->getID().'&webservice=true','confidence'=>$kodiCmd->getConfidence());

        }
            
}

function kodiCmd_action_kodiCmd(){
	global $_,$conf,$myUser;

	switch($_['action']){
		case 'kodiCmd_delete_kodiCmd':
			if($myUser->can('kodi','d')){
				$kodiManager = new KodiCmd();
				$kodiManager->delete(array('id'=>$_['id']));
			}
			header('location:setting.php?section=kodiCmd');
		break;
		case 'kodiCmd_plugin_setting':
			$conf->put('plugin_kodiCmd_api_url_kodi',$_['api_url_kodi']);
			header('location: setting.php?section=preference&block=kodiCmd');
		break;

		case 'kodiCmd_add_kodiCmd':
			if($myUser->can('kodi','c')){
                $kodiCmd = new KodiCmd();
				$kodiCmd->setName($_['name']);
				$kodiCmd->setDescription($_['description']);
				$kodiCmd->setJson($_['kodiJsonCode']);
                $kodiCmd->setConfidence($_['confidence']);
				$kodiCmd->setRoom($_['room']);
				$kodiCmd->save();
			}
			header('location:setting.php?section=kodiCmd');

		break;
		
		case 'kodiCmd_edit_kodiCmd':
			if($myUser->can('kodi','c')){
			
			
                $kodiCmd = new KodiCmd();
				$kodiCmd = $kodiCmd->getById($_['id']);
				$kodiCmd->setName($_['name']);
				$kodiCmd->setDescription($_['description']);
				$kodiCmd->setJson($_['kodiJsonCode']);
                $kodiCmd->setConfidence($_['confidence']);
				$kodiCmd->setRoom($_['room']);
				$kodiCmd->save();
			}
			header('location:setting.php?section=kodiCmd');

		break;
		
		case 'kodiCmd_change_state':   
			global $_,$myUser;

			
			if($myUser->can('kodi','u')){
				$kodiCmd = new KodiCmd();
				$kodiCmd = $kodiCmd->getById($_['engine']);

                                $url = $conf->get('plugin_kodiCmd_api_url_kodi');
                                $timeOut = $conf->get('plugin_kodiCmd_api_timeout_kodi');
                                $recoStatus = $conf->get('plugin_kodiCmd_api_recognition_status');
                                $reqJSON = str_replace(chr(34),'%22','{"jsonrpc":"2.0",'.html_entity_decode($kodiCmd->getJson()).'}'); 
                                
                                if($recoStatus <> '') {  $conf->put('plugin_kodiCmd_api_recognition_status',date('H:i:s')); }
                                if(($recoStatus <> '') || (!isset($_['webservice']))){
                                    $affirmation = kodiCmd_send_json_request($url,$reqJSON,$timeOut);                                     
                                }
                   
				if(!isset($_['webservice'])){
                                        if($affirmation<>"OK"){ 
                                            echo "<script>
                                                        alert('".$affirmation."');
                                                        window.location.href = \"index.php?module=room&id=".$kodiCmd->getRoom()."\";
                                                  </script>";
                                        }else{
                                            header('location:index.php?module=room&id='.$kodiCmd->getRoom());
                                        }
				}else{
                                    if(($affirmation<>"OK") && ($recoStatus <> '')){
                                            $response = array('responses'=>array(
                                                                                    array('type'=>'talk','sentence'=>$affirmation)
                                                                                )
                                                                );

                                            $json = json_encode($response);
                                            echo ($json=='[]'?'{}':$json);                                      
                                    }
                                }
			}else{
				$response = array('responses'=>array(
									array('type'=>'talk','sentence'=>'Je ne vous connais pas, je refuse de faire ça!')
                                                                    )
                                                                );
				echo json_encode($response);
			}
		break;  
                
                case 'kodiCmd_recognition_status':       
                    $url = $conf->get('plugin_kodiCmd_api_url_kodi');
                    $timeOut = $conf->get('plugin_kodiCmd_api_timeout_kodi');
                    
                    switch($_['state']){
                        case '1' :
                            
                            $conf->put('plugin_kodiCmd_api_recognition_status',date('H:i:s'));
                            
                            $json = '"method":"GUI.ShowNotification","params":{"title":"Reconnaissance Vocale","message":"Activée"},"id":"1"';
                            $json = urlencode(htmlspecialchars_decode($json));
                            $reqJSON = str_replace(chr(34),'%22','{"jsonrpc":"2.0",'.html_entity_decode($json).'}'); 
                            
                            $retJSON = kodiCmd_send_json_request($url,$reqJSON,$timeOut);
                            break;
                        
                        case '0' :
                            
                            $conf->put('plugin_kodiCmd_api_recognition_status','');
                            
                            $json = '"method":"GUI.ShowNotification","params":{"title":"Reconnaissance Vocale","message":"Désactivée"},"id":"1"';
                            $json = urlencode(htmlspecialchars_decode($json));
                            $reqJSON = str_replace(chr(34),'%22','{"jsonrpc":"2.0",'.html_entity_decode($json).'}');
                            
                            $retJSON = kodiCmd_send_json_request($url,$reqJSON,$timeOut);
                            break;
                    }

                    
                break;
		
	}
}


function kodiCmd_plugin_preference_menu(){
	global $_;
	echo '<li '.(@$_['block']=='kodi'?'class="active"':'').'><a  href="setting.php?section=preference&block=kodi"><i class="fa fa-angle-right"></i> KODI </a></li>';
}

function kodiCmd_plugin_preference_page(){
	global $myUser,$_,$conf;
	if((isset($_['section']) && $_['section']=='preference' && @$_['block']=='kodi' )  ){
		if($myUser!=false){
	?>

		<div class="span9 userBloc">
			<form class="form-inline" action="action.php?action=kodiCmd_plugin_setting" method="POST">

			    <p>api_url_kodi: </p>
			    <input type="text" class="input-large" name="api_url_kodi" value="<?php echo $conf->get('plugin_kodiCmd_api_url_kodi');?>" placeholder="http://[FIXME]:[FIXME]/jsonrpc">
			    <p>api_timeout_kodi: </p>
			    <input type="text" class="input-large" name="api_timeout_kodi" value="<?php echo $conf->get('plugin_kodiCmd_api_timeout_kodi');?>" placeholder="5">
			    

			    <button type="submit" class="btn">Enregistrer</button>
		    </form>
		    <p>
                          Cette version du plugin (testé sur raspbmc) reste plutôt basique, mais ne demande qu'à  evoluer......;)	 <br>
                    <br>      
                        <li>Installer php5-curl (envoi de commandes vers KODI)</li>
                          # sudo apt-get install php5-curl  <br>
                    <br>      
                        <li> Activer le plugin depuis la gestion des plugins   </li>
                        <li> Se deconnecter, puis se reconnecter au serveur Yana (redémarrer les clients Yana-Windows/Android) </li> <br>
                    <br>      
                        <li> aller dans Configuration / preferences / KODI </li>
                              &nbsp; - renseigner l'adresse et le port de votre serveur KODI :  http://192.168.1.105:85/jsonrpc   <br>
                              &nbsp; - renseigner le timeout en cas de non reponse de KODI : 5 <br>
                    <br>      
                          <li> aller dans Pieces / KODI  </li>
                              vous pouvez voir toutes les commandes préexistantes </li><br>
                    <br>      
                          <li> aller dans Configuration / KODI  </li>
                              vous pouvez ajouter de nouvelles commandes (voir <a href="http://wiki.kodi.org/index.php?title=JSON-RPC_API/v6" target="_blank">http://wiki.kodi.org/index.php?title=JSON-RPC_API/v6</a> )  </li><br>
                    <br>      
                          <li> activer le serveur WEB sous KODI / menu systeme / parametres / service / WEB  </li><br>
                    <br>      
                          	    
		    </p>                    
		</div>

<?php }else{ 	header('location:index.php?connexion=ko');

		}
	}
}

function kodiCmd_send_json_request($uri, $reqJSON, $timeout=2){

    $url = $uri.'?request='.$reqJSON;
file_put_contents("test.txt", $reqJSON);
    // Initialisation d'une session cURL
    $ch = curl_init($url);

    // Forcer l'utilisation d'une nouvelle connexion (pas de cache)
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

    // Définition du timeout de la requête (en secondes)
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    // Si l'URL est en HTTPS
    if (preg_match('`^https://`i', $url))
    {
     // Ne pas vérifier la validité du certificat SSL
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }

    // Suivre les redirections [facultatif]
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Récupération du contenu retourné par la requête
    // sous forme de chaîne de caractères via curl_exec()
    // (directement affiché au navigateur client sinon)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Récupérer le contenu de la page requêtée
    curl_setopt($ch, CURLOPT_NOBODY, false);

    // Execution de la requête
    $retJSON = curl_exec($ch);

    // Récupération du code HTTP retourné par la requête
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Récupération du contenu retourné par la requête
    $http_data = curl_getinfo($ch, CURLOPT_RETURNTRANSFER);

    // Fermeture de la session cURL
    curl_close($ch);

    // Si on à un coe retour different de 200, 
    // alors on a un probleme avec le serveur
    if ($http_code != 200) {        
        return "Impossible de contacter le serveur KODI (".$http_code.")";
    }

    // Si tout c'est bien passé, 
    // retourne {"id":"1","jsonrpc":"2.0","result":"OK"}
    // sinon  {"error":{"code":-32601,"message":"Method not found."},"id":"1","jsonrpc":"2.0"}
    
//    file_put_contents("test.txt", $retJSON); // Ecriture (à la racine de Yana-server) du retour KODI pour debug
    
    if(strpos($retJSON, "OK")!==FALSE){    return "OK"; }
    if(strpos($retJSON, "pong")!==FALSE){    return "PONG"; }
    if(strpos($retJSON, "error")!==FALSE){     return "Impossible déxécuter cette action ";    }
	if(strpos($retJSON, "true")!==FALSE){    return "Le son est maintenant activé"; }
	if(strpos($retJSON, "false")!==FALSE){    return "Le son est maintenant désactivé"; }
	if(strpos($retJSON, "50")!==FALSE){    return "Le son est maintenant à 50%"; }
	if(strpos($retJSON, "30")!==FALSE){    return "Le son est maintenant à 30%"; }
	if(strpos($retJSON, "70")!==FALSE){    return "Le son est maintenant à 70%"; }
	if(strpos($retJSON, "100")!==FALSE){    return "Le son est maintenant au maximum"; }
        if(strpos($retJSON, "label")!==FALSE){ 
            $tab = explode("label", $retJSON);
            $tab2 = explode(",",$tab[1]);
            if (empty($tab2[0])) { return "Information non disponible"; }
            return $tab2[0];            
        }
    
}

function desactive_recognition(&$response){
    global $conf;
    $url = $conf->get('plugin_kodiCmd_api_url_kodi');
    $timeOut = $conf->get('plugin_kodiCmd_api_timeout_kodi');    
    $recoStatus = $conf->get('plugin_kodiCmd_api_recognition_status');
    
    $ActiveTime = explode(":",$recoStatus);
    $Hi = $ActiveTime[0].':'.$ActiveTime[1];
    $seconde = $ActiveTime[2];
    
    if($recoStatus!=''){
        
        if((date('H:i')>$Hi) && (date('s')>$seconde)){        
        
            $conf->put('plugin_kodiCmd_api_recognition_status','');

            $json = '"method":"GUI.ShowNotification","params":{"title":"Reconnaissance Vocale","message":"Désactivation auto "},"id":"1"';
            $json = urlencode(htmlspecialchars_decode($json));
            $reqJSON = str_replace(chr(34),'%22','{"jsonrpc":"2.0",'.html_entity_decode($json).'}');

            $retJSON = kodiCmd_send_json_request($url,$reqJSON,$timeOut);    
        }

    }

}



Plugin::addHook("get_event", "desactive_recognition");

Plugin::addHook("preference_menu", "kodiCmd_plugin_preference_menu"); 
Plugin::addHook("preference_content", "kodiCmd_plugin_preference_page"); 

Plugin::addCss("/css/style.css"); 
Plugin::addHook("action_post_case", "kodiCmd_action_kodiCmd"); 

Plugin::addHook("node_display", "kodiCmd_display");   
Plugin::addHook("setting_bloc", "kodi_plugin_setting_page");
Plugin::addHook("setting_menu", "kodiCmd_plugin_setting_menu");  
Plugin::addHook("vocal_command", "kodiCmd_vocal_command");
Plugin::addHook("send_json_request", "kodiCmd_send_json_request");
?>
