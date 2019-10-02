<?php if(!function_exists("http_response_code")){function http_response_code($Mdt=0){if(!$Mdt)return 0;$Mwe=(isset($_SERVER["SERVER_PROTOCOL"])?$_SERVER["SERVER_PROTOCOL"]:"HTTP/1.0");header($Mwe.' '.$Mdt." Lightning");return 0;}}
$Mtd=1;$Mb=500*1024*1024;if(function_exists("apache_setenv"))apache_setenv("no-gzip","1");if(strpos($_SERVER["REQUEST_URI"],'.')&&!lc('fl')){$Mbm=str_replace('?','&',$_SERVER["REQUEST_URI"]);if($Maf=strpos($Mbm,'&'))$Mbm=substr($Mbm,0,$Maf);if($Maf=strrpos($Mbm,'.')){$Mvx=strtolower(substr($Mbm,$Maf+1));if(strlen($Mvx)>1&&strlen($Mvx)<5&&!is_numeric($Mvx[0])&&!in_array($Mvx,explode(' ',"php html htm xml yml"))&&!strpos($Mbm,"index.php")){header("X-Lightning: fast not found answer");http_response_code(404);exit;}}}
$Mc=file_exists(DIR_CACHE."lightning/"."cron_working")&&(@filemtime(DIR_CACHE."lightning/"."cron_working")>time()-15*60);$Md=false;if(!empty($_SERVER["HTTPS"])&&(($_SERVER["HTTPS"]=="on")||($_SERVER["HTTPS"]=="1")))$Md=true;elseif(!empty($_SERVER["SERVER_PORT"])&&$_SERVER["SERVER_PORT"]==443)$Md=true;elseif(!empty($_SERVER["HTTP_X_FORWARDED_PROTO"])&&$_SERVER["HTTP_X_FORWARDED_PROTO"]=="https")$Md=true;elseif(!empty($_SERVER["HTTP_X_FORWARDED_PROTOCOL"])&&$_SERVER["HTTP_X_FORWARDED_PROTOCOL"]=="https")$Md=true;elseif(!empty($_SERVER["HTTP_X_HTTPS"]))$Md=true;elseif(!empty($_SERVER["HTTP_CF_VISITOR"])&&strpos($_SERVER["HTTP_CF_VISITOR"],"https"))$Md=true;if($Md){$_SERVER["HTTPS"]="on";$_SERVER["SERVER_PORT"]="443";}else{$_SERVER["HTTPS"]="";$_SERVER["SERVER_PORT"]="80";}
if($Mz=="s"){$Mve=true;}else if($Mz)require_once"alpha.php";if(!$Mve)if(!$Ma or filemtime(Wa)<time()-60*60*12){if(!We()){if($Mz){echo("false");$light_ob=false;exit;}
require_once"alpha.php";return;}
if($Mz=="by"){echo"OK";exit;}
}else We();Wfo();if(isset($_GET["limit"])&&$_GET["limit"]>100){$_SERVER["REQUEST_URI"]=str_replace("limit=".$_GET["limit"],"limit=100",$_SERVER["REQUEST_URI"]);$_GET["limit"]=100;}
if(empty($_SERVER["REQUEST_METHOD"]))if(empty($_POST))$_SERVER["REQUEST_METHOD"]="GET";else$_SERVER["REQUEST_METHOD"]="POST";if(!$Mve){if(!isset($Ma['e'])){$Ma=false;Wf();require_once"alpha.php";return;}
$light_modify_cart=$Ma['e'];if(!empty($_SERVER["SERVER_PROTOCOL"])&&empty($_SERVER["HTTP_X_REQUESTED_WITH"])&&$_SERVER["REQUEST_METHOD"]=="GET"){if(stripos($_SERVER["HTTP_HOST"],"www.")===false){if(stripos($Mh,"www.")!==false){$Mi=true;$_SERVER["HTTP_HOST"]="www.".$_SERVER["HTTP_HOST"];}
}else{if(stripos($Mh,"www.")===false){$Mi=true;$_SERVER["HTTP_HOST"]=str_ireplace("www.","",$_SERVER["HTTP_HOST"]);}}
if(stripos($Mh,"ttps://")&&$_SERVER["SERVER_PORT"]!=443){$Mi=true;$_SERVER["SERVER_PORT"]=443;}
if(substr($_SERVER["REQUEST_URI"],-9)=="index.php"||substr($_SERVER["REQUEST_URI"],-9)=="index.htm"||substr($_SERVER["REQUEST_URI"],-10)=="index.html"){$Mi=true;$_SERVER["REQUEST_URI"]=substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],'/'));}
if(!empty($Mi)){Wf();$Mj="Перенаправлено";$Mk="http".(($_SERVER["SERVER_PORT"]==443)?"s://":"://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];header($_SERVER["SERVER_PROTOCOL"]." 301 Moved Permanently");header("Location: ".$Mk);exit;}}
if(!$Ma['Mnt']&&$Mz!="lg"){if($Mz){if($Mz=="gen"and(!$Ma['Mnt']or!$Ma['n'])){echo json_encode(false);exit;}
$Mf["gen"]=false;$Mg=json_encode($Mf);echo($Mg);$light_ob=false;exit;}
if(empty($_GET["li_source"])){Wf();return Wg();}}
$Ml=time()-$Ma['k']*60;$Mm=time()-$Ma['l']*60;$Mn=false;$Mqx=false;}
if(!empty($_COOKIE['az'])){$_COOKIE["PHPSESSID"]="aaaaaaaaaaaaaaaaaaaaaaaaaa";$_COOKIE["OCSESSID"]="aaaaaaaaaaaaaaaaaaaaaaaaaa";}
if(isset($Mo)){$Mp=1;$_SESSION=$Mo;if((VERSION>"2.1")and isset($_SESSION["default"])and is_array($_SESSION["default"]))$Mq=&$_SESSION["default"];else$Mq=&$_SESSION;$Mr=!empty($Mq["user_id"]);if(empty($Mq["customer_id"]))$Ms=0;else$Ms=$Mq["customer_id"];$Mt=$Mq;$_COOKIE["PHPSESSID"]="aaaaaaaaaaaaaaaaaaaaaaaaaa";$_COOKIE["OCSESSID"]="aaaaaaaaaaaaaaaaaaaaaaaaaa";}else{if(VERSION>="3"){$Mq=array();$Mp=false;$Mqx=empty($_COOKIE["OCSESSID"]);if(!$Mqx){$Mp=$_COOKIE["OCSESSID"];Wfp();$Msa=$Mir->query("SELECT `data` FROM `".DB_PREFIX."session` WHERE session_id = '$Mp' AND expire > ".(int)time());if($Msa->row)$Mq=json_decode($Msa->row["data"],true);}
}else{$Mqx=empty($_COOKIE["PHPSESSID"]);if(!session_id()){if(VERSION<"2.1"){ini_set("session.use_only_cookies","On");ini_set("session.use_trans_sid","Off");ini_set("session.cookie_httponly","On");session_set_cookie_params(365*24*60*60,'/');}else{ini_set("session.use_only_cookies","Off");ini_set("session.use_cookies","On");ini_set("session.use_trans_sid","Off");ini_set("session.cookie_httponly","On");session_set_cookie_params(0,'/');}
session_start();}
$Mp=session_id();if(VERSION>="2.3"){$Mpf=$Mp;if(!empty($_COOKIE["default"])){$Mpf=$_COOKIE["default"];}else{$_COOKIE["default"]=$Mpf;}
if(empty($_SESSION[$Mpf])or!is_array($_SESSION[$Mpf])){$_SESSION[$Mpf]=array();}
$Mq=&$_SESSION[$Mpf];}elseif((VERSION>"2.1")and isset($_SESSION["default"])and is_array($_SESSION["default"])){$Mq=&$_SESSION["default"];}else{$Mq=&$_SESSION;}}
$Mr=!empty($Mq["user_id"]);if(empty($Mq["customer_id"])){$Ms=0;unset($Mq["customer_group_id"]);}
else$Ms=$Mq["customer_id"];$Mt=$Mq;}
if($Mz=="s"){Whr(DIR_IMAGE."cache/session.txt",$Mt);var_dump($Mt);exit;}
if($Mz=="lg"){if(!$Mr)exit;require_once"special.php";Wes();}
if($Mve){if(!$Mr)exit;$Mz="t";$Mnw=2;require_once"tetha.php";}
if(!empty($_GET["li_module"])||($Ma['o']and!$Mu and$Mqx))return Wg();if(Wdr)$Mr=true;if(!empty($_GET["li_source"])){if(!$Mr and!$Mwo)exit;require_once"special.php";Wek($_GET["li_source"]);}
if(empty($Mq['p']))Wf();else Wf($Mq['p']);$Mv=!empty($_SERVER["HTTP_X_REQUESTED_WITH"]);$Mw=Wh();$Mx=Wi();$My=$Mw;if($Ma['q']and$Ms)$My.='CU';if($Mz){$Mv=false;if(isset($_GET["cd"])){$M_=$_GET["cd"];if($M_>10000000)$M_=0;}
if(isset($_GET["md"]))$Mab=$_GET["md"];}
if(Wdr)$M_=1;if(empty($_GET["tracking"]))$Muv=false;else$Muv=$_GET["tracking"];if(empty($Ma['r']))$Ma['r']="sdsdfsdfe";$Ma['r'].=" li_sql li_module";if($Mz)$Ma['r'].=" li_op li_sql rd cd md";$Mrz=$_SERVER["REQUEST_URI"];$_SERVER["REQUEST_URI"]=str_replace('?','&',$_SERVER["REQUEST_URI"]);$Mvc=false;foreach(explode(' ',$Ma['r'])as$Mac)if(isset($_GET[$Mac])){if($Mac=="tracking")continue;$Mad=Wj('&'.$Mac.'=','',$_SERVER["REQUEST_URI"]);$Mae=Wj("","&",$Mad);if($Mae)$Mad=$Mae;$_SERVER["REQUEST_URI"]=str_replace('&'.$Mac.'='.$Mad,'',$_SERVER["REQUEST_URI"]);unset($_GET[$Mac]);unset($_REQUEST[$Mac]);$Mvc=true;}
if($Mvc){if(substr($_SERVER["REQUEST_URI"],-1)=="&")$_SERVER["REQUEST_URI"]=substr($_SERVER["REQUEST_URI"],0,-1);if($Maf=strpos($_SERVER["REQUEST_URI"],'&'))$_SERVER["REQUEST_URI"]=substr($_SERVER["REQUEST_URI"],0,$Maf).'?'.substr($_SERVER["REQUEST_URI"],$Maf+1);}else$_SERVER["REQUEST_URI"]=$Mrz;if($Mv or!$Ma['s']){$Mag=$My;$My.=$Mx;}
$Mr_=false;if($Ma['x']){foreach(explode(' ',$Ma['x'])as$Mai){if($Mai=="customer_group_id"){if(empty($Mt["customer_id"]))continue;$Mai="customer_group";if(empty($Mt["customer_group"])){Wfp();$Mfi=$Mir->query("SELECT customer_group_id FROM ".DB_PREFIX."customer WHERE customer_id = ".(int)$Mt["customer_id"])->row;if(!$Mfi)continue;$Mt["customer_group"]=$Mfi["customer_group_id"];$Mq["customer_group"]=$Mfi["customer_group_id"];}}
$Mqo=false;if($Maf=strpos($Mai,'(')){$Mqo=substr($Mai,$Maf+1,-1);$Mai=substr($Mai,0,$Maf);}
if(!empty($Mt[$Mai])&&$Mt[$Mai]!==$Mqo){$Maj=$Mt[$Mai];if(!is_string($Maj)and!is_numeric($Maj))$Maj=serialize($Maj);$My.=$Mai.$Maj;$Mr_=true;}
if(!empty($_COOKIE[$Mai])&&$_COOKIE[$Mai]!==$Mqo){$Maj=$_COOKIE[$Mai];if(!is_string($Maj)and!is_numeric($Maj))$Maj=serialize($Maj);$My.=$Mai.$Maj;$Mr_=true;}}}
if(session_id()and((VERSION<"2.0"&&ini_get("register_globals"))or($Mz and empty($Mab))))session_write_close();if($Mz){if(!$Ma['t']){if(empty($Mab))unset($Mq["user_id"],$Mt["user_id"]);}
}else if($Mr and!$Ma['u'])return Wg();$_SERVER["REQUEST_URI"]=Wk($_SERVER["REQUEST_URI"]);$_SERVER["QUERY_STRING"]="";$Mfi=str_replace("?","&",$_SERVER["REQUEST_URI"]);if($Maf=strpos($Mfi,"&"))$_SERVER["QUERY_STRING"]=substr($Mfi,$Maf+1);$_SERVER["argv"][0]=$_SERVER["QUERY_STRING"];$Mk="http".(($_SERVER["SERVER_PORT"]==443)?"s://":"://").$_SERVER["HTTP_HOST"].Wl($_SERVER["REQUEST_URI"]);if(!$Mvc)$_SERVER["REQUEST_URI"]=$Mrz;$Mah=Wm($Mk);if($Ma['t']and!$Mr and!strpos($Mk,"assets/"))$Mah=$My.'v';if($Ma['w'])foreach(explode(' ',$Ma['w'])as$Mai)if(!empty($Mt[$Mai])||!empty($_COOKIE[$Mai]))if($Mz)exit;else{$Mah=false;return Wg();}
if($Mk==$Mh)foreach($_COOKIE as$Mak=>$Mad)if(substr($Mak,0,6)=="popup-"||substr($Mak,0,14)=="header_notice-")$Mah.=$Mak.$Mad;if($Mz=="gen"){if($Mc&&!Wdr)exit;$Mte=file_exists(Wa.'ar');ignore_user_abort(true);$Mal=time();require_once"alpha.php";Wn();return Wg(true);}
if($Mv and!$Ma['y'])return Wg();if($Mam)return Wg();if($_SERVER["REQUEST_METHOD"]=="POST"){if($Mv){if(!empty($_POST))$Mah.=serialize($_POST);}else{$Mah=false;return Wg();}}
$Mah=str_replace(array("\n","\t","\r"),'',$Mah);if($Mv)$Man=DIR_CACHE."lightning/tetha";else$Man=DIR_CACHE."lightning/alpha";$Mg=false;$Mao=md5($Mah);$Map="/".substr($Mao,0,2)."/c".substr($Mao,2);if($Mz=="gens"){require_once"alpha.php";if($Ma['t']and!$Mr)exit;if(str_replace("http:","https:",$Mk)==$Mh)$Mk=$Mh;ignore_user_abort(true);$Mal=time();Wo();$Mte=file_exists(Wa.'ar');$Mf["gen"]=Wp();$Mf["md"]=array();if(empty($Mab)){if($Mf["gen"]or$Mte){Wn();return Wg(true);}
Wq();}
unset($Maq);unset($_GET["rd"]);return Wg(true);}
if(!empty($Mq["customer_id"])){if(!We($Mw)or!$Mn['z'])return Wg();}
if(!file_exists($Man.$Map)or!filesize($Man.$Map)){if(!empty($Ma['en'])&&!$Mv){$Mut=str_replace($Mh,'',$Mk);if($Maf=strpos($Mut,'?'))$Mut=substr($Mut,0,$Maf);if($Maf=strpos($Mut,'&'))$Mut=substr($Mut,0,$Maf);if($Maf=strrpos($Mut,'/'))$Mut=substr($Mut,$Maf+1);$Mut=Wm($Mh.$Mut);$Mao=md5($Mut);$Map="/".substr($Mao,0,2)."/c".substr($Mao,2);if(!file_exists($Man.$Map)or!filesize($Man.$Map)){return Wg(true);}
$Mg=@file_get_contents($Man.$Map);if(substr($Mg,-2)!="[`"){return Wg(true);}
header("X-Gen-Reason: serving from product page ".$Mut);}else{return Wg(true);}}
$Mar=0;$Mpu=0;$Mas=0;$Mpu=filemtime($Man.$Map);if($Mpu<$Mm){header("X-Gen-Reason: cache too old");return Wg(true);}
$Mat=$Ms||!empty($Mq['_'])||!empty($Mq["wishlist"])||!empty($Mq["compare"])||($Mx!=$Ma['aa']&&$Ma['s']);if($Mat){if($Ms&&!$Ma['ab'])return Wg();if(!empty($Mq['_'])&&!$Ma['e'])return Wg();if(!empty($Mq["wishlist"])&&!$Ma['ac'])return Wg();if(!empty($Mq["compare"])&&!$Ma['ad'])return Wg();}
if($Mr){$Mau=DIR_CACHE."lightning/gamma".$Map;if(file_exists($Mau)){@touch($Mau,$Ml);@touch($Man.$Map,$Ml);}}
Wr();if(VERSION>="2"&&$Muv){Wfp();$Mir->query("UPDATE `".DB_PREFIX."marketing` SET clicks = (clicks + 1) WHERE code = '".$Mir->escape($Muv)."'");}
if(lc('f')&&!$Mr_&&$_SERVER["REQUEST_METHOD"]!=="POST"&&Weq()==$Ma['ae']&&$Mx==$Ma['aa']&&($Mu or!$Mat)){Wfp();$Mav=$Mir->query("SELECT * FROM ".DB_PREFIX."lightning_modified WHERE page='".substr($Map,1)."'")->row;header("Cache-Control: must-revalidate");if($Mav){if($Mu)$Maw=$Mav["smd"];else$Maw=$Mav["md"];if(!empty($_SERVER["HTTP_IF_MODIFIED_SINCE"])){$Max=@strtotime(substr($_SERVER["HTTP_IF_MODIFIED_SINCE"],5));if($Max&&$Max>=$Maw){$Mj="Не изменилось";header($_SERVER["SERVER_PROTOCOL"]." 304 Not Modified");Wt();$light_ob=false;exit;}}
header("Last-Modified: ".gmdate("D, d M Y H:i:s \G\M\T",$Maw));}}
$Mg=@file_get_contents($Man.$Map);if(!$Mg)return Wg();$Mu_=false;if(substr($Mg,-2)=="[`"){$Maf=strrpos($Mg,"`]");$Mu_=substr($Mg,$Maf+2,-2);$Mg=substr($Mg,0,$Maf);}
if($Mu_){if(file_exists(DIR_SYSTEM."journal2")or file_exists(DIR_SYSTEM."library/journal3")){/*recently viewed*/$Mva=isset($_COOKIE["jrv"])&&$_COOKIE["jrv"]? explode(',',$_COOKIE["jrv"]): array();$Mva=array_diff($Mva,array($Mu_));array_unshift($Mva,$Mu_);$Mva=array_splice($Mva,0,10);setcookie("jrv",implode(',',$Mva),time()+60*60*24*30,'/',$_SERVER["HTTP_HOST"]);}}
if(!$Mv)if($Mat){if(!We($Mw))return Wg();if(!empty($Mq["customer_id"])and!$Mn['z'])return Wg();Wu($Mg);if($Mx!=$Ma['aa']&&$Ma['s']){Wx($Ma['aa'],$Mx,$Mg);if(!empty($Mn['ag'][$Mx])){$May=$Mn['ag'][$Mx];Wv('',"http".(($_SERVER["SERVER_PORT"]==443)?"s://":"://")."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'name="redirect" value="','"',$May);Ww($Mn['af'],$May,$Mg);}}
if(!empty($Mq['_'])){if(!$Mn['ah']||($Mq['_']===true))return Wg();Ww($Mn['ah'],$Mq['_'],$Mg);}
if(!empty($Mq["customer_id"])){if(empty($Mq['ai']))$Maz="Customer";else$Maz=$Mq['ai'];if(empty($Mq['aj']))$Ma_="";else$Ma_=$Mq['aj'];if(empty($Mq['p']))$Mba=$Maz;else$Mba=$Mq['p'];$Mbb=$Mn['ak'];foreach($Mbb as&$Mbc){$Mbc=str_replace('<Wal>',$Maz,$Mbc);$Mbc=str_replace('<Wam>',$Ma_,$Mbc);$Mbc=str_replace('<Wan>',$Mba,$Mbc);}
Ww($Mn['z'],$Mbb,$Mg);}
if(!empty($Mq["wishlist"]))Ww($Mn['ao'],count($Mq["wishlist"]),$Mg);if(!empty($Mq["compare"]))Ww($Mn['ap'],count($Mq["compare"]),$Mg);}
if(strpos($Mah,"journal2/assets/css")){header("Content-Type: text/css");}elseif(strpos($Mah,"journal2/assets/js")){header("Content-Type: application/javascript");}else header("Content-Type: text/html; charset=utf-8");$Mar=$Mpu;Wz($Mg,true);$light_ob=false;exit;function Wr($Me="gens"){global$Mv,$Mc;if(Wdr or!$Mc or$Mv)return false;if(strpos($_SERVER["REQUEST_URI"],"journal2/assets"))return false;if(file_exists(Wa.'aq')and file_exists(Wa.'ar'))return true;$Mbe=array();if(isset($_SERVER["HTTPS"]))$Mbe['as']=$_SERVER["HTTPS"];else$Mbe['as']=false;$Mbe['at']=$_SERVER["HTTP_HOST"];$Mbe['au']=$_SERVER["REQUEST_URI"];$Mbe['av']=$_GET;global$Mq;$Mbe['aw']=$Mq;$Mbe['ax']=$_SERVER["HTTP_USER_AGENT"];$Mbe['av']["li_op"]=$Me;Whr(Wa.'aq',$Mbe);Wo();return true;}
function Wo(){static$Mkb;if($Mkb)return;$Mkb=1;global$Mir,$Ma,$Mk,$Ms,$Mc;$Mp_=Wa.'cb';if(file_exists($Mp_))$Mqa=filemtime($Mp_)<time()-5*60;else$Mqa=true;if((!$Ma['ay']or$Mc)and!$Mqa)return;Wfp();if($Mqa){$Mqd=$Mir->query("SELECT NOW()")->row;$Mqd=reset($Mqd);$Mqd=substr($Mqd,0,strpos($Mqd,' '));if(!file_exists($Mp_))file_put_contents($Mp_,$Mqd,LOCK_EX);else{$Mdw=file_get_contents($Mp_);if($Mdw!=$Mqd){require_once"beta.php";Wep($Mir->query("SELECT product_id FROM ".DB_PREFIX."product_discount WHERE date_start = '$Mqd' OR date_end = '$Mqd'")->rows);Wep($Mir->query("SELECT product_id FROM ".DB_PREFIX."product_special WHERE date_start = '$Mqd' OR date_end = '$Mqd'")->rows);file_put_contents($Mp_,$Mqd,LOCK_EX);}}}
if(!$Ma['ay']or$Mc)return;if(isset($_SERVER["REMOTE_ADDR"])){$Mbg=$_SERVER["REMOTE_ADDR"];}else{$Mbg='';}
$Mbe=$Mk;$Mbh='';$Mir->query("DELETE FROM `".DB_PREFIX."customer_online` WHERE (UNIX_TIMESTAMP(`date_added`) + 3600) < UNIX_TIMESTAMP(NOW())");$Mir->query("REPLACE INTO `".DB_PREFIX."customer_online` SET `ip` = '".$Mir->escape($Mbg)."', `customer_id` = '".(int)$Ms."', `url` = '".$Mir->escape($Mbe)."', `referer` = '".$Mir->escape($Mbh)."', `date_added` = NOW()");}
function Wfo(){$Msq=Wa.'ee';if(file_exists($Msq)&&filemtime($Msq)>time()-60*5)return;Wfp();global$Mir;if(!file_exists($Msq)){$Mdw=$Mir->query("SELECT date_modified FROM ".DB_PREFIX."product ORDER BY date_modified DESC LIMIT 1")->row["date_modified"];file_put_contents($Msq,$Mdw,LOCK_EX);return;}
touch($Msq);$Mdw=file_get_contents($Msq);$Mij=$Mir->query("SELECT product_id, date_modified FROM ".DB_PREFIX."product WHERE date_modified > '$Mdw' ORDER BY date_modified ASC LIMIT 1000")->rows;if(!$Mij)return;$Mvy=end($Mij);$Mvy=$Mvy["date_modified"];if(count($Mij)==1000){$Mvz=$Mir->query("SELECT product_id FROM ".DB_PREFIX."product WHERE date_modified = '$Mvy'")->rows;if(count($Mvz)>1)$Mij=array_merge($Mij,$Mvz);}
$Mdw=$Mvy;require_once"beta.php";Wep($Mij);file_put_contents($Msq,$Mdw,LOCK_EX);if(count($Mij)>=1000)touch($Msq,time()-60*5);}
function Wfp(){global$Mir;if($Mir)return$Mir;if((!defined("VERSION")||VERSION<"2.0")&&defined("DIR_DATABASE")){$Map=DIR_DATABASE.DB_DRIVER.".php";$Mqr=substr(DIR_SYSTEM,0,-7)."vqmod/vqcache/vq2-system_".str_replace('/','_',str_replace(DIR_SYSTEM,'',$Map));if(file_exists($Mqr))$Map=$Mqr;if(file_exists($Map)){require_once($Map);$Mjy="DB".DB_DRIVER;if(!class_exists($Mjy))$Mjy=DB_DRIVER;$Mir=new$Mjy(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);}else{exit("Lightning: Could not load database driver type ".DB_DRIVER.'!');}
}else{$Map=DIR_SYSTEM."library/db/".DB_DRIVER.".php";if(!file_exists($Map))$Map=DIR_SYSTEM."library/db/".strtolower(DB_DRIVER).".php";require_once($Map);$Mjy="DB\\".DB_DRIVER;if(class_exists($Mjy)){$Mir=new$Mjy(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);}else{exit("Lightning: Could not load database driver ".DB_DRIVER.'!');}}
return$Mir;}
function Wbh(){global$Ma,$Mh,$Mz;if(!$Ma){if($Mz){echo"{\"gen\":false,\"md\":[]}";global$light_ob;$light_ob=false;exit;}
return;}
$Ma['bf']=0;if($Ma['bo']){if(isset($_SERVER["HTTPS"])&&(($_SERVER["HTTPS"]=="on")||($_SERVER["HTTPS"]=="1")))$Mbe="https://";else$Mbe="http://";$Mbe.=str_replace('www.','',$_SERVER['HTTP_HOST']).rtrim(dirname($_SERVER['PHP_SELF']),'/.\\').'/';foreach($Ma['bo']as$Mfs=>$Mft)if(str_replace("/www.","/",$Mft['bp'])==$Mbe ||str_replace("/www.","/",$Mft['bq'])==$Mbe ){if(str_replace("/www.","/",$Mft['bq'])==$Mbe)$Mh=$Mft['bq'];else$Mh=$Mft['bp'];$Ma['ae']=$Mft['ae'];$Ma['aa']=$Mft['aa'];$Ma['q']=$Mft['q'];$Ma['bf']=$Mfs;break;}}}
function We($Mfu=''){global$Ma,$Mn,$Mh;if($Mfu and$Mn)return true;global$Mb;$Mfv=@disk_free_space(DIR_CACHE);if($Mfv&&$Mfv<$Mb){Wbi("Not enough free disk space");return false;}
if(!$Mh)if(isset($_SERVER["HTTPS"])&&(($_SERVER["HTTPS"]=="on")||($_SERVER["HTTPS"]=="1")))$Mh=HTTPS_SERVER;else$Mh=HTTP_SERVER;if($Mfu and$Ma['bf'])$Mfu.=$Ma['bf'];if(file_exists(Wa.$Mfu)){if($Mfu){$Mn=unserialize(file_get_contents(Wa.$Mfu));if(!$Mn)header("X-D: ".Wa.$Mfu);return true;}else{Wbh();if(!$Ma['bf']&&filemtime(Wa)<time()-60*60*12){unlink(Wa);}else return true;}}
require_once"alpha.php";return Wfq($Mfu);}
function Wbi($Mhb){global$Mgf,$Mw,$Ma;file_put_contents($Mgf,$Mhb,LOCK_EX);if(file_exists(Wa))unlink(Wa);if(file_exists(Wa.$Mw))unlink(Wa.$Mw);$Ma=false;}
function Wg($Mci=false){global$Mu,$Mk;if($Mu&&lc('Mnt')&&!lc('fm')&&!strpos($Mk,"itemap")){global$Mvq;$Mwb=preg_match("/google|yandex|bing|hthou/i",$Mvq);$Mwr=$Mwb;if(!$Mwb){header("X-Lightning: non-cached pages access deny for minor bots");header("Retry-After: 86400");global$Mj;$Mj="Нет в кеше, проверьте позже";if($Mwr){$Mj="Поддельный робот!";}
http_response_code(503);exit;}}
require_once"alpha.php";global$light_ob;static$Mqq;if(!$Mqq){$Mqq=true;register_shutdown_function('Wax');ob_start();}
$light_ob=1+$Mci;return true;}
function Wh(){global$Ma,$Mp,$Mq;if(!$Ma){if($Mp and isset($Mq['language'])and is_string($Mq['language']))return$Mq['language'];return"en";}
if(lc('di'))return"en";$languages=$Ma['by'];$Mgg='';if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])&&$_SERVER['HTTP_ACCEPT_LANGUAGE']){$Mgh=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);foreach($Mgh as$Mgi){foreach($languages as$Mgj=>$Mad){if($Mad['status']){$Mgk=explode(',',$Mad['locale']);if(in_array($Mgi,$Mgk)){$Mgg=$Mgj;}}}}}
if($Mp and isset($Mq['language'])and!is_string($Mq['language']))unset($Mq['language']);if($Mp&&isset($Mq['language'])&&array_key_exists($Mq['language'],$languages)&&$languages[$Mq['language']]['status']){$Mdt=$Mq['language'];}elseif(isset($_COOKIE['language'])&&array_key_exists($_COOKIE['language'],$languages)&&$languages[$_COOKIE['language']]['status']){$Mdt=$_COOKIE['language'];}elseif($Mgg){$Mdt=$Mgg;}else{$Mdt=$Ma['ae'];}
if(!isset($Mq['language'])||$Mq['language']!=$Mdt){$Mq['language']=$Mdt;}
if(!isset($_COOKIE['language'])||$_COOKIE['language']!=$Mdt){if(!Wdr)setcookie('language',$Mdt,time()+60*60*24*30,'/',$_SERVER['HTTP_HOST']);$_COOKIE['language']=$Mdt;}
return Wbj($Mdt);}
function lc($Mgj){if(!isset($GLOBALS['Ma'][$Mgj]))return false;return$GLOBALS['Ma'][$Mgj];}
function light_mobile(){return light_device()=="mobile";}
function light_tablet(){return light_device()=="tablet";}
class Light_Mobile_Detect {public function isMobile(){return light_tablet()or light_mobile();}
public function isTablet(){return light_tablet();}}
function Wbk(){if(!isset($_SERVER['HTTP_USER_AGENT']))return false;$Mgl=$_SERVER['HTTP_USER_AGENT'];$Mgm="desktop";if((preg_match('/GoogleTV|SmartTV|Internet.TV|NetCast|NETTV|AppleTV|boxee|Kylo|Roku|DLNADOC|CE\-HTML/i',$Mgl))){$Mgm="tv";}
else if((preg_match('/Xbox|PLAYSTATION.3|Wii/i',$Mgl))){$Mgm="tv";}
else if((preg_match('/iP(a|ro)d/i',$Mgl))||(preg_match('/tablet/i',$Mgl))&&(!preg_match('/RX-34/i',$Mgl))||(preg_match('/FOLIO/i',$Mgl))){$Mgm="tablet";}
else if((preg_match('/Linux/i',$Mgl))&&(preg_match('/Android/i',$Mgl))&&(!preg_match('/Fennec|mobi|HTC.Magic|HTCX06HT|Nexus.One|SC-02B|fone.945/i',$Mgl))){$Mgm="tablet";}
else if((preg_match('/Kindle/i',$Mgl))||(preg_match('/Mac.OS/i',$Mgl))&&(preg_match('/Silk/i',$Mgl))){$Mgm="tablet";}
else if((preg_match('/GT-P10|SC-01C|SHW-M180S|SGH-T849|SCH-I800|SHW-M180L|SPH-P100|SGH-I987|zt180|HTC(.Flyer|\_Flyer)|Sprint.ATP51|ViewPad7|pandigital(sprnova|nova)|Ideos.S7|Dell.Streak.7|Advent.Vega|A101IT|A70BHT|MID7015|Next2|nook/i',$Mgl))||(preg_match('/MB511/i',$Mgl))&&(preg_match('/RUTEM/i',$Mgl))){$Mgm="tablet";}
else if((preg_match('/BOLT|Fennec|Iris|Maemo|Minimo|Mobi|mowser|NetFront|Novarra|Prism|RX-34|Skyfire|Tear|XV6875|XV6975|Google.Wireless.Transcoder/i',$Mgl))){$Mgm="mobile";}
else if((preg_match('/Opera/i',$Mgl))&&(preg_match('/Windows.NT.5/i',$Mgl))&&(preg_match('/HTC|Xda|Mini|Vario|SAMSUNG\-GT\-i8000|SAMSUNG\-SGH\-i9/i',$Mgl))){$Mgm="mobile";}
else if((preg_match('/Windows.(NT|XP|ME|9)/',$Mgl))&&(!preg_match('/Phone/i',$Mgl))||(preg_match('/Win(9|.9|NT)/i',$Mgl))){$Mgm="desktop";}
else if((preg_match('/Macintosh|PowerPC/i',$Mgl))&&(!preg_match('/Silk/i',$Mgl))){$Mgm="desktop";}
else if((preg_match('/Linux/i',$Mgl))&&(preg_match('/X11/i',$Mgl))){$Mgm="desktop";}
else if((preg_match('/Solaris|SunOS|BSD/i',$Mgl))){$Mgm="desktop";}
else if((preg_match('/Bot|Crawler|Spider|Yahoo|ia_archiver|Covario-IDS|findlinks|DataparkSearch|larbin|Mediapartners-Google|NG-Search|Snappy|Teoma|Jeeves|TinEye/i',$Mgl))&&(!preg_match('/Mobile/i',$Mgl))){$Mgm="desktop";}
else{$Mgm="mobile";}
if($Mgm=="tv")$Mgm="tablet";if($Mgm=="desktop")$Mgm=false;return$Mgm;}
final class light_Device {public$Mgn;public function __construct(){$this->mobile_agents=array('iPod','iPhone','webOS','BlackBerry','windows phone','symbian','vodafone','opera mini','windows ce','smartphone','palm','midp');$this->exclude_mobile_agents=array();$this->tablet_agents=array('iPad','RIM Tablet','hp-tablet','Kindle Fire','Android');$this->exclude_tablet_agents=array();if(isset($_GET['change_device'])){$Mgo=$_GET['device_name'];$_SESSION['set_device']=$Mgo;}
if(!isset($_SESSION['set_device'])){if((!isset($_SESSION['device']))||(!isset($_COOKIE['device']))){if($this->isTablet()){$this->set("tablet");}else if($this->isMobile()){$this->set("mobile");}else{$this->set("desktop");}}
}elseif(isset($_GET['change_device'])){if($Mgo=='mobile_desktop'||$Mgo=='tablet_desktop'){$_SESSION['device']='desktop';}elseif($Mgo=='mobile'){$_SESSION['device']='mobile';}elseif($Mgo=='tablet'){$_SESSION['device']='tablet';}}}
public function set($Mgn){$_SESSION['device']=$Mgn;}
public function isMobile(){$Mgp=false;if(isset($_SERVER['HTTP_USER_AGENT'])){foreach($this->mobile_agents as$Mgq){if(stripos($_SERVER['HTTP_USER_AGENT'],$Mgq)){$Mgp=true;}}
if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")&&stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){$Mgp=true;}
foreach($this->exclude_mobile_agents as$Mgr){if(stripos($_SERVER['HTTP_USER_AGENT'],$Mgr)){echo'exclude';$Mgp=false;}}}
return$Mgp;}
public function isTablet(){$Mgs=false;if(isset($_SERVER['HTTP_USER_AGENT'])){foreach($this->tablet_agents as$Mgt){if(stripos($_SERVER['HTTP_USER_AGENT'],$Mgt)){$Mgs=true;}}
if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")&&stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){$Mgs=false;}
foreach($this->exclude_tablet_agents as$Mgu){if(stripos($_SERVER['HTTP_USER_AGENT'],$Mgu)){$Mgs=false;}}}
return$Mgs;}}
function light_device(){global$Mtl;static$Mgm;if(!is_null($Mgm)and!$Mtl)return$Mgm;$Mgm='';if(lc('bz')){if($Mtl)$Mgm="mobile";else$Mgm=Wbk();global$Mvi,$Mvq;$Mvi=false;if(stripos($Mvq,"iphone"))$Mvi="iphone";if(stripos($Mvq,"ipad"))$Mvi="ipad";if($Mgm=="tablet"&&!lc('eq'))$Mgm="mobile";if($Mgm=="desktop"||$Mgm=="pc")$Mgm='';if($Mgm=="mobile"){if(file_exists(DIR_SYSTEM."library/journal3"))$_SERVER["HTTP_USER_AGENT"]="Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Mobile Safari/537.36";else$_SERVER["HTTP_USER_AGENT"]="Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1";}
elseif($Mgm=="tablet")$_SERVER["HTTP_USER_AGENT"]="Mozilla/5.0 (iPad; CPU OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1";else$_SERVER["HTTP_USER_AGENT"]="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36";}
return$Mgm;}
function Wbj($Mfu){$Mgm=light_device();if($Mgm)$Mfu.="_".$Mgm;return$Mfu;}
function Wi(){global$Ma,$Mp,$Mq;$Mgv=$Ma['Mgv'];if(isset($_GET["currency"])&&(array_key_exists($_GET["currency"],$Mgv))){$Mfe=$_GET["currency"];}elseif($Mp&&(isset($Mq["currency"]))&&(array_key_exists($Mq["currency"],$Mgv))){$Mfe=$Mq["currency"];}elseif((isset($_COOKIE["currency"]))&&(array_key_exists($_COOKIE["currency"],$Mgv))){$Mfe=$_COOKIE["currency"];$Mq["currency"]=$Mfe;}else{$Mfe=$Ma['aa'];$Mq["currency"]=$Mfe;}
return$Mfe;}
function Wk($Mbe){if(substr($Mbe,-27)=="index.php?route=common/home")$Mbe=substr($Mbe,0,-27);elseif(substr($Mbe,-9)=="index.php")$Mbe=substr($Mbe,0,-9);$Mbe=urldecode($Mbe);$Mbe=str_replace("&amp;","&",$Mbe);$Mbn=substr($Mbe,-1);if($Mbn=="?"||$Mbn=="&")$Mbe=substr($Mbe,0,-1);return$Mbe;}
function Wl($Mbe){if(!lc('r'))return$Mbe;$Mbe=str_replace('?','&',$Mbe);foreach(explode(' ',lc('r'))as$Mac)if(isset($_GET[$Mac])){$Mbe=str_replace('&'.$Mac.'='.$_GET[$Mac],'',$Mbe);}
$Mbn=substr($Mbe,-1);if($Mbn=="&")$Mbe=substr($Mbe,0,-1);$Maf=strpos($Mbe,'&');if($Maf)$Mbe=substr($Mbe,0,$Maf).'?'.substr($Mbe,$Maf+1);if(substr($Mbe,-27)=="index.php?route=common/home")$Mbe=substr($Mbe,0,-27);elseif(substr($Mbe,-9)=="index.php")$Mbe=substr($Mbe,0,-9);return$Mbe;}
function Wm($Mbe){global$My;return$My.str_replace('?','&',$Mbe);}
function Weq(){global$Mw;$Mlm=$Mw;if($Maf=strpos($Mlm,"_"))$Mlm=substr($Mlm,0,$Maf);return$Mlm;}
function Wz($Mg,$Mwf=false){if(substr($Mg,-2)=="[`")$Mg=substr($Mg,0,strrpos($Mg,"`]"));if(lc('bz')&&file_exists(DIR_SYSTEM."library/journal3")){global$Mvi;if($Mvi){Wu($Mg);$Mel=substr($Mg,0,1000);$Mel=str_replace(" touchevents "," touchevents ".$Mvi." ",$Mel);$Mg=$Mel.substr($Mg,1000);}}
if(Whj())Whk($Mg,$Mwf);global$Mvq;if($Mg&&!headers_sent()&&(empty($_COOKIE["res_pushed"])and!preg_match("/^((?!chrome|android).)*safari/i",$Mvq))){$Mvh=$Mg;Wu($Mvh);global$Mh;$Mlm=array();preg_match_all("/[^:\"',\s]+\.(css|js)[^:\"',\s]*/i",$Mvh,$Mlm);$Mlm=$Mlm[0];$Mwt=substr($Mh,0,strpos($Mh,':')+1);foreach($Mlm as$Mbm){if(substr($Mbm,0,2)=="//"){$Mbm=$Mwt.$Mbm;if(substr($Mbm,0,strlen($Mh))!==$Mh)continue;}else$Mbm=$Mh.$Mbm;$Mkb=substr(DIR_SYSTEM,0,-7).substr($Mbm,strlen($Mh));if($Maf=strpos(str_replace('?','&',$Mkb),'&'))$Mkb=substr($Mkb,0,$Maf);if(!file_exists($Mkb))continue;$Mbm=substr($Mbm,strpos($Mbm,'/',10));if(substr($Mbm,-3)=="css")$Mjp="style";else$Mjp="script";header("Link: <$Mbm>; rel=preload; as=$Mjp",false);}
setcookie("res_pushed","1",time()+(60*60*24*30),"/");if(preg_match("/hthou/i",$Mvq)&&strpos($Mvh,"|spider|")){$Mg=$Mvh;$Mg=str_replace("|spider|","|ome|",$Mg);}}
if($Mg)Wam($Mg);Waz();echo$Mg;Wt();}
function Wu(&$Mg){if(!$Mg||(ord($Mg[0])!=31))return;if(function_exists("gzdecode"))$Mg=gzdecode($Mg);else$Mg=gzinflate(substr($Mg,10,-8));}
function Whi(&$Mg,$Mgw=2){if(!extension_loaded("zlib")or!function_exists("gzencode"))return;$Mg=gzencode($Mg,$Mgw);}
function Wam(&$Mg,$Mgw=2){if(headers_sent()or lc('ct')){Wu($Mg);return;}
if(!empty($_SERVER["HTTP_ACCEPT"])){if(substr($_SERVER["HTTP_ACCEPT"],0,5)=="image"&&!strpos($_SERVER["HTTP_ACCEPT"],"xml")){Wu($Mg);return;}}
if(isset($_SERVER["HTTP_ACCEPT_ENCODING"])&&(strpos($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")!==false)){$Mgx="gzip";}
if(isset($_SERVER["HTTP_ACCEPT_ENCODING"])&&(strpos($_SERVER["HTTP_ACCEPT_ENCODING"],"x-gzip")!==false)){$Mgx="x-gzip";}
if(!empty($_SERVER["HTTP_USER_AGENT"]))if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 7")||strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 8"))$Mgx=false;if(empty($Mgx)){Wu($Mg);return;}
if(!extension_loaded("zlib")||ini_get("zlib.output_compression")){Wu($Mg);return;}
if(headers_sent()){Wu($Mg);return;}
if(connection_status()){Wu($Mg);return;}
header("Content-Encoding: ".$Mgx);if(ord($Mg[0])!=31)$Mg=gzencode($Mg,$Mgw);return true;}
function Waz(){global$Mgy,$Mar,$Mgz,$Mg_;$Mha=0;if($Mar){$Mhb="Served in ".Wbl(microtime(true)-$Mgy)." sec from page cache written ".Wbm(microtime(true)-$Mar)." ago. ";}else{$Mha=microtime(true)-$Mgy;$Mhb="Generated in ".Wbl($Mha)." sec. ";}
$Mhc=false;if($Mg_){$Mhb.=$Mg_." cached queries, ";$Mhc="real ";}
if($Mgz==1)$Mhb.="1 ".$Mhc."DB query used.";else if($Mgz)$Mhb.=$Mgz." ".$Mhc."DB queries used.";global$Mhd,$Mhe;if($Mha){$Mhf=$Mha-$Mhd;$Mhg=$Mhd-$Mhe;$Mhh=round($Mhf/$Mha*100);$Mhi=round($Mhg/$Mha*100);$Mhj=round($Mhe/$Mha*100);$Mbp=array();if($Mhh)$Mbp[]=$Mhh."% PHP";if($Mhi)$Mbp[]=$Mhi."% Lightning";if($Mhj)$Mbp[]=$Mhj."% SQL";$Mhb.=" [".implode(", ",$Mbp)."]";}
if(!headers_sent())header("X-OpenCart-Lightning: ".$Mhb);}
function Wbl($Mhk){if($Mhk<1)$Mhk=round($Mhk,3);elseif($Mhk<2)$Mhk=round($Mhk,2);elseif($Mhk<6)$Mhk=round($Mhk,1);else$Mhk=round($Mhk);return($Mhk);}
function Wbm($Mbt){if($Mbt<60)return Wbl($Mbt)." sec";$Mbt=round($Mbt/60);if($Mbt<60)return$Mbt." min";$Mbt=round($Mbt/60);if($Mbt==1)return"1 hour";if($Mbt<60)return$Mbt." hours";$Mbt=round($Mbt/24);if($Mbt==1)return"1 day";return$Mbt." days";}
function Wbp($Mcu){global$Mhy,$Mhz;static$Mh_,$Mia,$Mib,$Mic,$Mid,$Mie;if($Mh_!=$Mhz){global$Ma,$Mn;$Mh_=$Mhz;$Mia=$Ma['Mgv'][$Mhz]['Mia'];if(!$Mia&&$Mia!=="0")$Mia=2;$Mia=(int)$Mia;$Mib=$Ma['Mgv'][$Mhz]['Mib'];$Mic=$Ma['Mgv'][$Mhz]['Mic'];$Mid=$Mn['Mid'];$Mie=$Mn['Mie'];}
$Mad=Wbq($Mcu[1])*$Mhy;$Mif=$Mib.number_format(round($Mad,$Mia),$Mia,$Mid,$Mie).$Mic;return$Mif;}
function Wx($Mig,$Mh_,&$Mg){global$Ma,$Mn,$Mhy,$Mhz;if(isset($Ma['Mgv'][$Mig])){$Mih=$Ma['Mgv'][$Mig]['Mad'];}else{return;}
if(isset($Ma['Mgv'][$Mh_])){$Mii=$Ma['Mgv'][$Mh_]['Mad'];}else{return;}
$Mhy=$Mii/$Mih;$Mhz=$Mh_;$Mg=preg_replace_callback("/".preg_quote($Ma['Mgv'][$Mig]['Mib'])."([0-9][0-9".preg_quote($Mn['Mie']).preg_quote($Mn['Mid'])."]*)".preg_quote($Ma['Mgv'][$Mig]['Mic'])."/u",'Wbp',$Mg);$Mg=str_replace(">".$Ma['Mgv'][$Mig]['Mib'].$Ma['Mgv'][$Mig]['Mic']."<",">".$Ma['Mgv'][$Mh_]['Mib'].$Ma['Mgv'][$Mh_]['Mic']."<",$Mg);}
function Ww($Mee,$Meu,&$Mdk){if(!$Mee)return;if(is_array($Meu)){foreach($Meu as$Mcb=>$Mbc){$Maf=Ww($Mee[$Mcb],$Mbc,$Mdk);if(!$Maf)$Maf=Ww($Mee[$Mcb],$Mbc,$Mdk,0);if($Maf)$Mdl=$Maf;}
return;}
foreach($Mee as$Mei)Wgs($Mei,$Meu,$Mdk);}
function Wgs($Mee,$Meu,&$Mdk,$Mdl=0){if(!$Mee)return;$Mev=explode("%*%",$Mee['Mev']);$Mei=$Mdl;foreach($Mev as$Mew){$Mqf=$Mei;if(!trim($Mew))continue;$Mei=strpos($Mdk,$Mew,$Mei);if($Mei===false){$Mew=trim($Mew);$Mei=strpos($Mdk,$Mew,$Mqf);}
if($Mei===false)return;$Mei+=strlen($Mew);}
$Maf=$Mei;$Mex=explode("%*%",$Mee['Mex']);foreach($Mex as$Mew){if(!trim($Mew))continue;$Mew=trim($Mew);$Mey=strpos($Mdk,$Mew,$Maf);if($Mey===false){$Mew=trim($Mew);$Mey=strpos($Mdk,$Mew,$Maf);}
if($Mey===false)return;$Maf=$Mey+strlen($Mew);}
$Mdk=substr($Mdk,0,$Mei).$Meu.substr($Mdk,$Mey);return$Mey;}
function Wv($Mez,$Meu,$Mdl,$Mbn,&$Mdk){$Mei=0;while(($Mei=stripos($Mdk,$Mdl,$Mei))!==false){$Mei+=strlen($Mdl);if($Mbn)$Mey=stripos($Mdk,$Mbn,$Mei);else$Mey=strlen($Mdk);if($Mey){$Me_=substr($Mdk,0,$Mei);$Mfa=substr($Mdk,$Mey);$Mfb=substr($Mdk,$Mei,$Mey-$Mei);$Mfc=strlen($Mfb);if($Mez!=='')$Mfb=str_ireplace($Mez,$Meu,$Mfb);else$Mfb=$Meu;$Mdk=$Me_.$Mfb.$Mfa;$Mey=$Mey+strlen($Mfb)-$Mfc;$Mei=$Mey+strlen($Mbn);if($Mei>strlen($Mdk))break;}}}
function Wbq($Mil){$Mhk=preg_replace("/[^0-9.,]/i",'',$Mil);if(substr($Mhk,-3,1)==","){$Mhk=substr($Mhk,0,-3).".".substr($Mhk,-2);$Mhk=str_replace(",","",$Mhk);}
$Mhk=str_replace(",","",$Mhk);$Mim=round(abs($Mhk),2);return$Mim;}
function Wj($Mdl,$Mbn="",$Mdk){$Mbm='';if($Mdl)$Mei=stripos($Mdk,$Mdl);else$Mei=0;if($Mei!==false){$Mei+=strlen($Mdl);if($Mbn)$Mey=stripos($Mdk,$Mbn,$Mei);else$Mey=strlen($Mdk);if($Mey!==false)$Mbm=trim(substr($Mdk,$Mei,$Mey-$Mei));}
return$Mbm;}
function Wfy($Map){if(!is_file($Map))return;unlink($Map);}
function Whj(){return lc('Mvo')&&!empty($_SERVER["HTTP_ACCEPT"])&&strpos($_SERVER["HTTP_ACCEPT"],"image/webp");}
function Whk(&$Mg,$Mwg=false){if(!lc('Mvo'))return;Wu($Mg);global$Mwh;$Mwh=$Mwg;$Mg=preg_replace_callback("/[^\"',;\s]+\.(jpe?g|png)/i","light_image_to_webp",$Mg);}
function light_image_to_webp($Mvm,$Mwg=false){if(is_array($Mvm)){$Mvm=$Mvm[0];global$Mwh;$Mwg=$Mwh;}
if(strpos($Mvm,"/")===false)return$Mvm;$Mcj="";if(substr($Mvm,0,4)=="url("){$Mcj="url(";$Mvm=substr($Mvm,4);}
$Moa="@-@";$Mdg=$Mvm;if(strpos($Mdg,"\/")!==false){$Moa="/";$Mdg=str_replace("\/","/",$Mdg);}
global$Mh;if(strpos($Mdg,"//")===false&&$Mdg[0]!='/')$Mwg=false;$Mdg=str_replace($Mh,'',$Mdg);if(strpos($Mdg,"//")!==false)return$Mvm;$Mdg=urldecode($Mdg);$Mvo=substr($Mdg,0,strrpos($Mdg,'.')).".webp";if(substr($Mvo,0,6)=="image/")$Mvo=substr($Mvo,6);if(substr($Mvo,0,6)=="cache/")$Mvo=substr($Mvo,6);$Mvo="image/cache/webp/".$Mvo;if($Mwg)return str_replace($Moa,"\/",$Mcj.$Mh.Whp($Mvo));$Mvn=substr(DIR_SYSTEM,0,-7);if(is_file($Mvn.$Mvo))if(!is_file($Mvn.$Mdg)||filemtime($Mvn.$Mdg)<=filemtime($Mvn.$Mvo))return str_replace($Moa,"\/",$Mcj.$Mh.Whp($Mvo));if(!is_file($Mvn.$Mdg))return$Mvm;if(strtolower(substr($Mdg,-4))==".png")$Mvp=imagecreatefrompng($Mvn.$Mdg);else$Mvp=imagecreatefromjpeg($Mvn.$Mdg);if(!$Mvp)return$Mvm;Whm($Mvp,$Mvn.$Mvo);imagedestroy($Mvp);return str_replace($Moa,"\/",$Mcj.$Mh.Whp($Mvo));}
function Whp($Mbe){$Mbe=str_replace(' ',"%20",$Mbe);return$Mbe;}
function Whm($Mvp,$Map){$Mks=substr($Map,0,strrpos($Map,'/'));Whe($Mks);$Mwi=is_file($Map);imagepalettetotruecolor($Mvp);imagewebp($Mvp,$Map);if($Mwi)return;$Mwj=array(0,0);$Mwk=DIR_IMAGE."cache/webp/lightning_webp_data";if(is_file($Mwk))$Mwj=explode('|',file_get_contents($Mwk));if(empty($Mwj[1]))$Mwj=array(0,0);$Mwj[0]++;$Mwj[1]+=filesize($Map);file_put_contents($Mwk,implode('|',$Mwj),LOCK_EX);}