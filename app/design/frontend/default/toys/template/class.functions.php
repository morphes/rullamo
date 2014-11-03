<?php

require_once("class.db.php");
require_once("class.user.php");

$mods = array( "flats" ,"houses","rents","business" );

class Functions {

//---Функции формирования перечисления полей для SQL запросов Insert и Update---//
//---Возвращает строку со списком полей. Берет список полей из файла например flats.flds---//
    function GetFieldsInsert($mod)
    {
		global $mods_val,$mods;
		
		$fp = fopen( APP_PATH."include/data/$mod.flds", "r" ) or die ( "Не удалось открыть файл" );
		$flds_ins = '(';
		$flds_ins_end = ' VALUES (';
		while ( ! feof ( $fp ) ) {
			$fld_str = ( fgets( $fp, 1024 ) );
			list($fld,$type) = explode(" ",$fld_str);
			$type = chop($type);
			if(($type == 'intnull' || $_POST[$fld] ) &&  $fld != 'id' &&  $fld != 'idx') {
					if ($type == 'varchar') {
							$flds_ins .= "`".$fld."`, ";
							$flds_ins_end .= "'".$_POST[$fld]."', ";
						}
					if ($type == 'multi' && Functions::MultiFields($fld)) {#было if ($type == 'multi') { так. Перенес Functions::MultiFields($fld) из верхнего if
							$flds_ins .= "`".$fld."`, ";
							$flds_ins_end .= "'".Functions::MultiFields($fld)."', ";
						}
					if ($type == 'int') {
							$flds_ins .= "`".$fld."`, ";
							$flds_ins_end .= "".$_POST[$fld].", ";
						}
					if ($type == 'intnull')	{
							$flds_ins .= "`".$fld."`, ";
							$flds_ins_end .= ($_POST[$fld]?$_POST[$fld]:"''").", ";
					}
				}
			if($fld == 'idx') {
						$flds_ins .= "`".$fld."`, ";
						$flds_ins_end .= "'".$mods_val[$mod].Functions::GetSeq($mod).User::GetUserPhil($_SESSION['user_id'])."', ";
			}
		}
		
		$flds_ins = substr($flds_ins,0,strlen($flds_ins)-2);
		$flds_ins_end = substr($flds_ins_end,0,strlen($flds_ins_end)-2);
		
		$ur = User::GetUserRights($_SESSION['user_id']);
		
		$flds_ins .= ($ur['profiles_dispetcher'] && in_array($mod, $mods)?',status_cont':'').',status, created, updated, creator)';
		$flds_ins_end .= ($ur['profiles_dispetcher'] && in_array($mod, $mods)?',4':'').',1, now(), now(),'.$_SESSION['user_id'].')';
		
		return $flds_ins.$flds_ins_end;	
	}
	
	function GetFieldsUpdate($mod) {
		global $mods;
		
		$fp = fopen( APP_PATH."include/data/$mod.flds", "r" ) or die ( "Не удалось открыть файл" );
		$flds_upd = '';
		while ( ! feof ( $fp ) ) {
			$fld_str = ( fgets( $fp, 1024 ) );
			list($fld,$type) = explode(" ",$fld_str);
			$type = chop($type);
			if(($type == 'intnull' || $type == 'varchar' || $type == 'chb' || $_POST[$fld]) &&  $fld != 'id' &&  $fld != 'idx')	{
					if ($type == 'varchar') {
							$flds_upd .= "`".$fld."` = '".addslashes($_POST[$fld])."', ";
						}
					if ($type == 'multi' && Functions::MultiFields($fld)) { #было if ($type == 'multi') { так. Перенес Functions::MultiFields($fld) из верхнего if
							$flds_upd .= "`".$fld."` = '".Functions::MultiFields($fld)."', ";
						}
					if ($type == 'int')	{
							$flds_upd .= "`".$fld."` = ".$_POST[$fld].", ";
						}
					if ($type == 'chb')	{
							$flds_upd .= "`".$fld."` = ".($_POST[$fld]?1:0).", ";
						}
					if ($type == 'intnull')	{
						$flds_upd .= "`".$fld."` = ".($_POST[$fld]?$_POST[$fld]:"''").", ";
					}
				} 
			# if($type == 'chb' && !$_POST[$fld]) {
				# $flds_upd .= "`".$fld."` = 0, ";
			# }
		}
		$ur = User::GetUserRights($_SESSION['user_id']);
		if (!$ur['profiles_dispetcher'] && !$ur['profiles_top_control'] && in_array($mod, $mods)) {
			$flds_upd .= "`status_cont` = '1', ";
		}
		
		$flds_upd = substr($flds_upd,0,strlen($flds_upd)-2);
		
		return $flds_upd;
	}
	
//--------------------------------------------------------------------------------------------------------------------//	

function MultiFields($fld) {
		$fld_val = "";
		foreach ($_POST as $sec_key => $secvalue) {
			if (preg_match("/^$fld/", $sec_key)) 
			{if(!$fld_val) {$fld_val .= $secvalue; }else {$fld_val .= ",".$secvalue;}
			}
		}
		if($fld_val) {
			return $fld_val;
		} else {
			return 0;
		}
	}	
	

function GetSeq($tbl) {
		$link = dbh::connect();
        $sql = "SELECT value FROM `settings` WHERE setting='".$tbl."_seq_id' ";
		//print $sql;
        $res = mysql_query($sql);
		dbh::disconnect($link);
		if (mysql_num_rows($res)>0) {
					return mysql_result($res,0,value);
        } else {
            return 0;
        }
	}
	
function GetObjectMod($num) {
		global $mods_dict_val;
		if (substr($num,1,1)=="0"){$mod_f=substr($num,0,3);}else{$mod_f=substr($num,0,1);}
		return $mods_dict_val[$mod_f];
	}
	
function GetTotalRec() {
	global $mods;
	$total = 0;
	foreach ($mods as $mod)	{
		$link = dbh::connect();
        $sql = "SELECT count(*) as cnt FROM `".$mod."` where status<>0";
		//print $sql;
        $res = mysql_query($sql);
		dbh::disconnect($link);
		if (mysql_num_rows($res)>0) {
					$total+=mysql_result($res,0,cnt);
        }
	}
	
	if ($total) return $total;
}
	
function GetRecSum($period, $myobject) {
	$whr = '';
	
	if ($myobject) { 
		$whr = ' and owner='.$_SESSION['user_id'];
	}
	
	global $mods;
	//$total = 0;
	foreach ($mods as $mod)	{
		$link = dbh::connect();
		$sql = "SELECT count(*) as cnt FROM `".$mod."` WHERE status<>0 and DATE(created)>=DATE_SUB(NOW(), INTERVAL ".$period." DAY)".$whr;
		//print $sql;
		$res = mysql_query($sql);
		dbh::disconnect($link);
		if (mysql_num_rows($res)>0) {
			$total[$mod] = mysql_result($res,0,cnt);
		}
	}
	
	if ($total) return $total;
}

function GetRecSumObjType($period, $myobject, $mod) {
		$whr = '';
		
		if ($myobject) { $whr = ' and owner='.$_SESSION['user_id'];}
		
		#if ($_SESSION['user_id']==3) {$whr = ' and owner=47';};
		
		$total = 0;
		$link = dbh::connect();
		#and DATE(created)>=DATE_SUB(NOW(), INTERVAL ".$period." DAY) убрали период
        $sql = "SELECT count(*) as cnt FROM `".$mod."` WHERE created=updated and status<>0 ".$whr;
		//print $sql;
        $res = mysql_query($sql);
		dbh::disconnect($link);
		if (mysql_num_rows($res)>0) {
					$total = mysql_result($res,0,cnt);
        }
		
		if ($total) return $total;
	}

function GetCntObjMyAgents() {
		
		$agents_ids_arr = User::GetMyAgentsIds($_SESSION['user_id']);
		$agents_ids_str = implode(",", $agents_ids_arr);
		
		if ($agents_ids_str) {
			$link = dbh::connect();
			foreach (array ('flats','houses') as $mod) {
				$sql = 'SELECT owner,count(owner) as cnt FROM '.$mod.' where owner in ('.$agents_ids_str.') and status>0 group by owner';
				//print $sql;
				$res = mysql_query($sql);
				if (mysql_num_rows($res)>0) {
					for($i=0; $i<mysql_num_rows($res); $i++) {
						$statistic[mysql_result($res,$i,owner)][$mod] = mysql_result($res,$i,cnt);
					}
				}
				
					foreach (array ('day'=>1,'week'=>7,'month'=>31) as $k => $period) {
					$sql = 'SELECT owner,count(owner) as cnt FROM '.$mod.' where owner in ('.$agents_ids_str.') and status>0 and DATE(created)>=DATE_SUB(NOW(), INTERVAL '.$period.' DAY) group by owner';
					$res = mysql_query($sql);
					if (mysql_num_rows($res)>0) {
						for($i=0; $i<mysql_num_rows($res); $i++) {
							$statistic[mysql_result($res,$i,owner)][$mod.'_'.$k] = mysql_result($res,$i,cnt);
						}
					}
				}
			}
			
			
			
			dbh::disconnect($link);
			
			$users = User::GetUsersAr();
			$users_online_ar = User::GetUserOnlineAr();
			
			foreach ($agents_ids_arr as $k) {
				$statistic[$k]['name']		= $users[$k];
				$statistic[$k]['id']		= $k;
				$statistic[$k]['online']	= 0;
				if (in_array($k, $users_online_ar)) {
					$statistic[$k]['online']= 1;
				}
			}
			
			# if($_SESSION['user_id']==84){
				# print "<pre>";
				# print_r($statistic);
			# }
			
			return $statistic;
		} else {
			return 0;
		}
}

function GetClientCnt($period, $mod) {
		
	global $mods;
	$total = 0;
	
	$link = dbh::connect();
	//DATE(created)>=DATE_SUB(NOW(), INTERVAL ".$period." DAY)".'
	$sql = "SELECT count(*) as cnt FROM `clients_".$mod."` WHERE status<>0 and created=updated  and owner=".$_SESSION['user_id'];
	//print $sql;
	$res = mysql_query($sql);
	dbh::disconnect($link);
	if (mysql_num_rows($res)>0) {
		$total=mysql_result($res,0,cnt);
	}
	
	return $total;
}

function GetMessage($stat) {
		$total = 0;
		
		$link = dbh::connect();
		$sql = "SELECT count(*) as cnt FROM `messages` WHERE status<=".$stat." and user_id_to=".$_SESSION['user_id'];
		//print $sql;
		$res = mysql_query($sql);
		dbh::disconnect($link);
		if (mysql_num_rows($res)>0) {
				$total = mysql_result($res,0,cnt);
		}
		return $total;
}

function GetErrorObject($stat, $user) {
	global $mods;
	$total = array();
	$whr = '';
	$link = dbh::connect();
	if ($user) {
		$whr = ' and owner='."'".$user."'";
	}
	foreach ($mods as $mod)	{
		$sql = "SELECT count(*) as cnt FROM `".$mod."` WHERE status>0 and status_cont='".$stat."'".$whr;
		//print $sql;
		$res = mysql_query($sql);
		if (mysql_num_rows($res)>0) {
				$total[$mod] = mysql_result($res,0,cnt);
				$total['all'] += mysql_result($res,0,cnt);
		}
	}
	dbh::disconnect($link);
	return $total;
}

function SendMessage($user_id_from, $user_id_to, $message) {
		$link = dbh::connect();
		$sql = "INSERT INTO `messages` (`user_id_from`, `user_id_to`, `created`, `status`, `message`)".
				" VALUES ('".$user_id_from."', '".$user_id_to."', NOW(), '0', '".$message."')";
		#print $sql;
		mysql_query($sql);
		dbh::disconnect($link);
}

function IncSeq($tbl)
	{
		$link = dbh::connect();
        $sql = "UPDATE `settings` SET value=value+1 WHERE setting='".$tbl."_seq_id' ";
		//print $sql;
        $res = mysql_query($sql);
		dbh::disconnect($link);
		if ($res) {
			return 1;		
        } else {
            return 0;
        }
	}
	
function ParseMultiCB($str)
	{
		$check = explode(",",$str);
		foreach($check as $ch)
		$check_ret[$ch] = "checked";
		
		if ($check_ret) return $check_ret;
		else return 0;
	}
	


function InitializeAdminTemplate($tpl,$type, $page_title)
	{
		$rights = User::GetUserRights($_SESSION['user_id']);
		$tpl->assign('rights', $rights);
		
		$tpl->assign('session_user_id', $_SESSION['user_id']);
		
		$tpl->assign('type', $type);
		$tpl->assign('total', Functions::GetTotalRec());
		$tpl->assign('day', Functions::GetRecSum(1));
		$tpl->assign('week', Functions::GetRecSum(7));
		$tpl->assign('month', Functions::GetRecSum(31));
		
		#$tpl->assign('my_obj_day', Functions::GetRecSum(1,1));
		
		$tpl->assign('my_new_flats',  Functions::GetRecSumObjType(1,1,'flats'));
		$tpl->assign('my_new_houses', Functions::GetRecSumObjType(1,1,'houses'));
		
		$tpl->assign('usersonline', User::GetUserOnline());
		
		$tpl->assign('message_all', Functions::GetMessage(1));
		$tpl->assign('message_new', Functions::GetMessage(0));
		
		$tpl->assign('my_client_flats_day', Functions::GetClientCnt(1,'flats'));
		$tpl->assign('my_client_houses_day', Functions::GetClientCnt(1,'houses'));
		
		$tpl->assign('page_title', $page_title);
		
		if ($rights['profiles_manager']) {
			$tpl->assign('cnt_obj_my_agents', Functions::GetCntObjMyAgents());
		}
		
		$usr = $rights['profiles_agent'] || $rights['profiles_manager']?$_SESSION['user_id']:0;
		
		$tpl->assign('cnt_error_obj', Functions::GetErrorObject(2, $usr));

        
            
    


    $link = dbh::connect();
    $sql_order = "SELECT * FROM `nasledie`.`usersmessages` WHERE `to` = '" . $_SESSION['user_id'] . "' OR `from` = '" . $_SESSION['user_id'] . "' GROUP BY `from`,`to`";
    $friends = array();
    $result = mysql_query($sql_order);  
    while ($row = mysql_fetch_array($result)) {    	
    	if($row['from']==$_SESSION['user_id']) {
    		$friends[] = $row['to'];
    	} else {
    		$friends[] = $row['from'];
    	}
    }        
    $friends = array_unique($friends);                   
    $messages = array();      
    foreach($friends as $friend) {
    	$sql = "SELECT * FROM `nasledie`.`usersmessages` WHERE `from` IN ('" . $friend . "','" . $_SESSION['user_id'] . "') AND `to` IN ('" . $friend . "','" . $_SESSION['user_id'] . "') ORDER BY time";    	    	    	
    	$res = mysql_query($sql);    	    	
    	$key = $_SESSION['user_id'].'~'.$friend;

    	while ($rowmess = mysql_fetch_array($res)) {     	 	
        	$messages[$key][] = $rowmess;        	
    	}    	        	
    }       
    
    $tpl->assign("allmessages", $messages);


    $sql = "SELECT * FROM `nasledie`.`users` WHERE `id`='" . $_SESSION['user_id'] . "'";
    $res = mysql_query($sql);
    $result = mysql_fetch_array($res);
    $names[$_SESSION['user_id']] = $result['name'];
    $avatars[$_SESSION['user_id']] = '/newadmin/images/users/' . $result['hash_file'] . '_p.' . $result['ext'];
    
    $sql = "SELECT * FROM `nasledie`.`users` WHERE `id`='" . $_GET['id'] . "'";
    
    $res = mysql_query($sql);
    $result = mysql_fetch_array($res);
    $names[$_GET['id']] = $result['name'];
    $avatars[$_GET['id']] = '/newadmin/images/users/' . $result['hash_file'] . '_p.' . $result['ext'];
    $result = array();
    $result['names'] = $names;
    $result['avatars'] = $avatars;
    $sql = "SELECT * FROM `nasledie`.`users` WHERE `id`='" . $_SESSION['user_id'] . "'";
    $res = mysql_query($sql);
    
    $result = mysql_fetch_array($res);
    $names[$_SESSION['user_id']] = $result['name'];
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/newadmin/images/users/' . $result['hash_file'] . '_p.' . $result['ext'])) {
            $avatars[$_SESSION['user_id']] = '/newadmin/images/users/' . $result['hash_file'] . '_p.' . $result['ext'];
        } else {
            $avatars[$_SESSION['user_id']] = '/newadmin/images/notimage.png';
        }
    //$avatars[$_SESSION['user_id']] = '/newadmin/images/users/' . $result['hash_file'] . '_p.' . $result['ext'];
    $sql = "SELECT * FROM `nasledie`.`users` WHERE `id`='" . $_GET['id'] . "'";
    $res = mysql_query($sql);
    
    $result = mysql_fetch_array($res);
    $names[$_GET['id']] = $result['name'];
            if(file_exists($_SERVER['DOCUMENT_ROOT'].'/newadmin/images/users/' . $result['hash_file'] . '_p.' . $result['ext'])) { 
            $avatars[$_GET['id']] = '/newadmin/images/users/' . $result['hash_file'] . '_p.' . $result['ext'];
        } else {
            $avatars[$_GET['id']] = '/newadmin/images/notimage.png';
        }
    //$avatars[$_GET['id']] = '/newadmin/images/users/' . $result['hash_file'] . '_p.' . $result['ext'];
    $result = array();
    $result['names'] = $names;
    $result['avatars'] = $avatars;
    
    $tpl->assign('friend', $_GET['id']);
    
    $tpl->assign('names', $names);
    $tpl->assign('avatars', $avatars);
    $tpl->assign("usermessages", User::TranslateUsers(User::GetUsersList()));
    
    

        
	}
	
function LoadPhoto ($cont, $idx) {
		$uploaddir			= '../attached_file/';
		$microtime			= microtime();
		$max_size			= 5*1024*1024;
		$max_image_width	= 5000;
		$max_image_height	= 5000;
		$error				= '';
		$max_side_px		= 800;
		
		
		$filetypes = array('image/jpeg'); //'image/png', 'image/bmp', 'image/gif'
		$extens = array('jpg', 'jpeg'); //'png', 'gif', 'bmp'
		
		$id_file = substr($microtime, 11).substr($microtime, 2, 6);
		$ext = strtolower(substr($cont['name'], strrpos($cont['name'], ".")+1));
		
		if (is_uploaded_file($cont['tmp_name'])) {
			#Проверка веса
			if ($cont['size'] == 0) {
				$error = 'Файл 0кб.';
			} elseif ($cont['size'] > $max_size) {
					$error = 'Размер превышает 5мб.';
			} else {
				#Проверка типов
				if(in_array($cont['type'], $filetypes) and in_array($ext, $extens)) {
					
					$size = GetImageSize($cont['tmp_name']);
					#Проверка размера
					if (($size) && ($size[0] < $max_image_width) && ($size[1] < $max_image_height)) {
						#Загрузка
						if (move_uploaded_file($cont['tmp_name'], $uploaddir.$id_file.'.'.$ext)) {
							
							$link = dbh::connect();
							$sql = "INSERT INTO `attached_file` (id_file,ext,idx) VALUES('$id_file','$ext','$idx')";
							$res = mysql_query($sql);
							dbh::disconnect($link);
							
							#Инициализируем максимальную сторону фотки, и принимаем решение делаем ли ресайз
							$x = $max_side_px; $y = $max_side_px;
							if ($size[0]>$size[1]) $y = 0; else $x = 0;
							$rex = $size[0]>$size[1]?$size[0]:$size[1];
							$rex = $rex>$max_side_px?1:0;
							
							#Фото без водяного знака, обрезаем большую сторону до 800, делает Quality=70%
							$img_no_mark = new Imagick($uploaddir.$id_file.'.'.$ext);
							$img_no_mark->setImageCompression(imagick::COMPRESSION_JPEG);
							$img_no_mark->setImageCompressionQuality(70);
							if ($rex) {
								$img_no_mark->scaleImage($x, $y);
							}
							
							$img_no_mark->writeImage($uploaddir.$id_file.'_s.'.$ext);
							$img_no_mark->destroy();
							
							#Фото с водяным знаком, обрезаем большую сторону до 800
							$img = new Imagick($uploaddir.$id_file.'.'.$ext);
							$mark = new Imagick($uploaddir.'mark.png');
							$rgs = new Imagick($uploaddir.'rgs.png');
							if ($rex) {
								$img->scaleImage($x, $y);
							}
							$w = $img->getImageWidth() - 371;
							$h = $img->getImageHeight() - 150;
							
							$wrgs = $img->getImageWidth() - 120;
							$hrgs = $h - 50;
							$textWrite='застраховано';
							$wtxt = $img->getImageWidth() - 120;
							$htxt = $hrgs - 10;
							$fontColor = '#FFFFFF';
							$fontSize = 18;
							$colorPix=new ImagickPixel ($fontColor);
							$draw=new ImagickDraw(); 
							$draw->setFontSize($fontSize);
							$draw->setFillColor($colorPix);
							$img->annotateImage($draw,$wtxt,$htxt,0,$textWrite);
							
							$img->compositeImage($mark, imagick::COMPOSITE_OVER, $w, $h);
							$img->compositeImage($rgs, imagick::COMPOSITE_OVER, $wrgs, $hrgs);
							$img->writeImage($uploaddir.$id_file.'.'.$ext);
							
							#Так же делаем привью
							if ($size[0]>$size[1]) {
								$img->scaleImage(0, 150);
							} else {
								$img->scaleImage(150, 0);
							}
							$img->writeImage($uploaddir.$id_file.'_p.'.$ext);
							$img->destroy();
							
						} else { $error = $cont['tmp_name'].'   '.$uploaddir.$id_file.$ext;}
						
					} else { $error = 'Превышен размер '.$max_image_width.'x'.$max_image_height.'.'; }
				
				} else { $error = 'Файл имеет недопустимый формат! Разрешены только jpg, jpeg.';}
			}
		} else { $error = 'Файл пустой.';}
		
		if ($error) { $error = 'Ошибка загрузки файла '.$cont['name'].'! '.$error.'<br><br>';}
		
		return $error;
	}
	
function LoadPhotoUser ($cont, $id) {
		$uploaddir			= 'images/users/';
		$microtime			= microtime();
		$max_size			= 5*1024*1024;
		$max_image_width	= 5000;
		$max_image_height	= 5000;
		$error				= '';
		$max_side_px		= 800;
		
		
		$filetypes = array('image/jpeg'); //'image/png', 'image/bmp', 'image/gif'
		$extens = array('jpg', 'jpeg'); //'png', 'gif', 'bmp'
		
		$id_file = md5(substr($microtime, 11).substr($microtime, 2, 6));
		$ext = strtolower(substr($cont['name'], strrpos($cont['name'], ".")+1));
		
		if (is_uploaded_file($cont['tmp_name'])) {
			#Проверка веса
			if ($cont['size'] == 0) {
				$error = 'Файл 0кб.';
			} elseif ($cont['size'] > $max_size) {
					$error = 'Размер превышает 5мб.';
			} else {
				#Проверка типов
				if(in_array($cont['type'], $filetypes) and in_array($ext, $extens)) {
					
					$size = GetImageSize($cont['tmp_name']);
					#Проверка размера
					if (($size) && ($size[0] < $max_image_width) && ($size[1] < $max_image_height)) {
						#Загрузка
						if (move_uploaded_file($cont['tmp_name'], $uploaddir.$id_file.'.'.$ext)) {
							
							$link = dbh::connect();
							$sql = "UPDATE `users` SET ext = '$ext', hash_file = '$id_file' WHERE id = $id";
							$res = mysql_query($sql);
							dbh::disconnect($link);
							
							#Инициализируем максимальную сторону фотки, и принимаем решение делаем ли ресайз
							$x = $max_side_px; $y = $max_side_px;
							if ($size[0]>$size[1]) $y = 0; else $x = 0;
							$rex = $size[0]>$size[1]?$size[0]:$size[1];
							$rex = $rex>$max_side_px?1:0;
							
							#Фото без водяного знака, обрезаем большую сторону до 800, делает Quality=70%
							$img_no_mark = new Imagick($uploaddir.$id_file.'.'.$ext);
							//$img_no_mark->setImageCompression(imagick::COMPRESSION_JPEG);
							//$img_no_mark->setImageCompressionQuality(70);
							if ($rex) {
								$img_no_mark->scaleImage($x, $y);
							}
							
							$img_no_mark->writeImage($uploaddir.$id_file.'.'.$ext);
							$img_no_mark->destroy();
							
							#Фото с водяным знаком, обрезаем большую сторону до 800
							$img = new Imagick($uploaddir.$id_file.'.'.$ext);
							# $mark = new Imagick('attached_file/mark.png');
							# if ($rex) {
								# $img->scaleImage($x, $y);
							# }
							# $w = $img->getImageWidth() - 371;
							# $h = $img->getImageHeight() - 150;
							# $img->compositeImage($mark, imagick::COMPOSITE_OVER, $w, $h);
							# $img->writeImage($uploaddir.$id_file.'.'.$ext);
							
							#Так же делаем привью
							if ($size[0]>$size[1]) {
								$img->scaleImage(0, 150);
							} else {
								$img->scaleImage(150, 0);
							}
							$img->writeImage($uploaddir.$id_file.'_p.'.$ext);
							$img->destroy();
							
						} else { $error = $cont['tmp_name'].'   '.$uploaddir.$id_file.'.'.$ext;}
						
					} else { $error = 'Превышен размер '.$max_image_width.'x'.$max_image_height.'.'; }
				
				} else { $error = 'Файл имеет недопустимый формат! Разрешены только jpg, jpeg.';}
			}
		} else { $error = 'Файл пустой.';}
		
		if ($error) { $error = 'Ошибка загрузки файла '.$cont['name'].'! '.$error.'<br><br>';}
		
		return $error;
	}
	
function ArraySort($array, $on, $order='SORT_ASC') {
	$new_array = array();
	$sortable_array = array();
	
	if (count($array) > 0) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					if ($k2 == $on) {
						$sortable_array[$k] = $v2;
					}
				}
			} else {
				$sortable_array[$k] = $v;
			}
		}
		switch ($order) {
			case 'SORT_ASC':
				asort($sortable_array);
			break;
			case 'SORT_DESC':
				arsort($sortable_array);
			break;
		}
		foreach ($sortable_array as $k => $v) {
			$new_array[$k] = $array[$k];
		}
	}
	
	return $new_array;
}

function NoRights ($text = 'Недостаточно прав') {

	$smarty = new Smarty;
	Functions::InitializeAdminTemplate($smarty,'no_rights',$text);
	$smarty->display('admin.tpl.html');
	exit;
}

function CheckErrorObject() {
	$rights = User::GetUserRights($_SESSION['user_id']);
	if ($rights['profiles_agent'] || $rights['profiles_manager']) {
		$cnt_error_ar = Functions::GetErrorObject(2, $_SESSION['user_id']);
		$cnt_err = $cnt_error_ar['all'];
		if ($cnt_err > 1 && !$_GET['cont']) {
			require_once(APP_INC_PATH . "class.template.php");
			$smarty = new Template_API();
			Functions::InitializeAdminTemplate($smarty,'error_object','Доступ ограничен!');
			$smarty->setTemplate('admin.tpl.html');
			$smarty->displayTemplate();
			exit;
		}
	}
}

function SetSetting ($name, $value) {
	$link = dbh::connect();
	$sql = "UPDATE `settings` SET value='".$value."' WHERE setting='".$name."' limit 1";
	mysql_query($sql);
	dbh::disconnect($link);
}

}