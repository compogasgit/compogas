<?php if(!empty($Mow))return;$Mow=true;Whe(DIR_CACHE."lightning/");define('Wa',DIR_CACHE."lightning/".'b');if(file_exists(Wa))$Ma=unserialize(file_get_contents(Wa));else$Ma=array();$Mz=false;$Mve=false;$Mvs="3.26";$Mvq="";if(!empty($_SERVER["HTTP_USER_AGENT"]))$Mvq=$_SERVER["HTTP_USER_AGENT"];$Mu=preg_match("/bot|crawl|slurp|spider|hthou/i",$Mvq);$Mvs.=" ru";if(substr($_SERVER["REQUEST_URI"],-10)=="?lightning"){$Mve=true;require"zero.php";exit;}
$Mwo=!empty($_COOKIE["lix"]);if(!empty($_GET["li_op"])){$Mz=$_GET["li_op"];if($Mz=="cn"){require_once"special.php";Wgl();}
if($Mz=="bt"){require_once"special.php";Wht();}
if($Mz=="lg")if(!$Mwo)require_once"zero.php";else{require_once"special.php";Wes();}
if($Mz=="pt"){$Mks=DIR_IMAGE."cache/lightning";if(!file_exists($Mks))@mkdir($Mks,0777,true);file_put_contents($Mks."/push.css","a{color:black;}");$Mbm=HTTPS_SERVER."image/cache/lightning/push.css";$Mbm=substr($Mbm,strpos($Mbm,'/',10));header("Link: <$Mbm>; rel=preload; as=style");exit;}}
define("LIGHT_FRONTEND",true);register_shutdown_function('Web');if($Mwo){ini_set("display_errors","On");ini_set("display_startup_errors",TRUE);error_reporting(-1 ^ E_STRICT);}
if(!empty($Ma['fp']))foreach(explode(' ',$Ma['fp'])as$Mnx)if($Mnx=trim($Mnx)&&strpos($_SERVER["REQUEST_URI"],$Mnx)!==false)return;if(!empty($_SERVER["HTTP_X_REAL_HOST"]))$_SERVER["HTTP_HOST"]=$_SERVER["HTTP_X_REAL_HOST"];define('Wdr',$Mvq=="Lightning CRON Job");if(!empty($_GET["li_source"]))require"zero.php";$Mgf=DIR_LOGS.'cv';if(!Wdr and$Mz){header("X-Robots-Tag: none",true);if($Mz[0]=="t"){$Mnw=true;require_once"tetha.php";}
if($Mz=="by"){@unlink($Mgf);@unlink(DIR_CACHE."lightning/".'b');require_once"zero.php";}
if($Mz=="n"||$Mz=="s")require_once"zero.php";}
$Mgy=microtime(true);$Moj=DIR_CACHE."lightning/".'dg';if($Mam=isset($_GET["li_sql"])){$_SERVER["REQUEST_URI"]=str_replace("?li_sql=1","",$_SERVER["REQUEST_URI"]);$_SERVER["REQUEST_URI"]=str_replace("&li_sql=1","",$_SERVER["REQUEST_URI"]);unset($_GET["li_sql"]);unset($_REQUEST["li_sql"]);require_once"beta.php";require_once"special.php";Wfu("index.php");}
if($Mz=="gens"&&$_GET["cd"]>10000000&&file_exists($Mgf)){unlink($Mgf);Whu();}else if(file_exists($Mgf)and empty($_COOKIE['az'])){$Mhb=file_get_contents($Mgf);if((!strpos($Mhb,"comm")&&filemtime($Mgf)>time()-7200)or (filemtime($Mgf)>time()-10*60)or ($Mhb=='dd')){if($Mz)die("false");Wf();return;}
unlink($Mgf);}
define("LIGHT_ENABLED",true);require_once"zero.php";function Wf($Mox=false){global$Mz,$Mvq;if($Mz or!empty($_SERVER["HTTP_X_REQUESTED_WITH"])or(!empty($_SERVER["HTTP_ACCEPT"])&&substr($_SERVER["HTTP_ACCEPT"],0,5)=="image")or(!empty($_POST))or!empty($_COOKIE['az'])or($Mvq=="Lightning CRON Job"))return;global$Moj;if(file_exists($Moj)){if(filemtime($Moj)<time()-15)return;}
global$Moy,$Mgy;$Moy=rand(0,10000000);$Mbm['id']=$Moy;$Mbm['Mbt']=$Mgy;if(!empty($_SERVER["REMOTE_ADDR"]))$Mbm['ip']=$_SERVER["REMOTE_ADDR"];if($Mvq)$Mbm['Mon']=$Mvq;if($Mox)$Mbm['Mox']=$Mox;$Mbm['Mbe']="http".(($_SERVER["SERVER_PORT"]==443)?"s://":"://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];file_put_contents($Moj,serialize($Mbm)."\n",FILE_APPEND|LOCK_EX);}
function Web(){$Moz=false;if($Mo_=error_get_last())if($Mo_["type"]==1||$Mo_["type"]==4||$Mo_["type"]==16||$Mo_["type"]==64){if($Maf=strpos($Mo_["file"],"lightning/epsilon/"))unlink($Mo_["file"]);if(strpos($Mo_["message"],"annot declare class"))$Mo_=false;if(strpos($Mo_["message"],"annot redeclare class"))$Mo_=false;if(strpos($Mo_["message"],"server has gone away"))$Mo_=false;if(strpos($Mo_["message"],"trtotime"))$Mo_=false;if(strpos($Mo_["message"],"MySQLi->__construct"))$Mo_=false;if($Mo_){$Moz=str_replace("\n","<br/>",$Mo_["message"])." в ".str_replace(substr(DIR_SYSTEM,0,-7),'',$Mo_["file"]);$Moz=str_replace(substr(DIR_SYSTEM,0,-7),'',$Moz);if($Mo_["line"])$Moz.=":".$Mo_["line"];require_once"special.php";Wb("php_error",true,array("error"=>$Moz,"url"=>true,"lightning_disabled"=>!defined("LIGHT_ENABLED")));}}
global$Moj,$Moy;if($Moy){if(!$Moz){global$Mj;if($Mj)$Moz='+'.$Mj;}
file_put_contents($Moj,$Moy.'|'.microtime(true).'|'.$Moz."\n",FILE_APPEND|LOCK_EX);}}
function Wt(){static$Mlr;if($Mlr or!empty($_COOKIE['az']))return;global$Mam,$Mz;if($Mam and function_exists('Wck')){Wck();$Mlr=true;return true;}
if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]))return;if(!empty($_SERVER["HTTP_ACCEPT"])&&substr($_SERVER["HTTP_ACCEPT"],0,5)=="image")return;if($Mz)return;global$Mgy,$Mgf;if(!$Mgy)return;$Mpa=!file_exists($Mgf);$Mpb=microtime(true)-$Mgy;$Map=DIR_CACHE."lightning/".'de';if(file_exists($Map))$Mod=unserialize(file_get_contents($Map));else $Mod=array('do'=>0,'dp'=>0,'dm'=>0,'dn'=>0,'Moe'=>0);$Mod['Moe']=$Mpb;if($Mpa){if(empty($Mod['dp']))$Mod['dp']=0;if(empty($Mod['do']))$Mod['do']=0;$Mod['dp']+=$Mpb;$Mod['do']++;}else{if(empty($Mod['dn']))$Mod['dn']=0;if(empty($Mod['dm']))$Mod['dm']=0;$Mod['dn']+=$Mpb;$Mod['dm']++;}
if(!file_exists(DIR_CACHE."lightning"))mkdir(DIR_CACHE."lightning");Whr($Map,$Mod);$Mlr=true;}
function Whu(){$Map=DIR_CACHE."lightning/".'de';if(!file_exists($Map))return;global$Mgf;$Mpa=!file_exists($Mgf);$Mod=unserialize(file_get_contents($Map));if($Mpa){$Mod['dp']=0;$Mod['do']=0;}else{$Mod['dn']=0;$Mod['dm']=0;}
if(!file_exists(DIR_CACHE."lightning"))mkdir(DIR_CACHE."lightning");Whr($Map,$Mod);}
function Whe($Mks){if(file_exists($Mks))return false;@mkdir($Mks,0777,true);@chmod($Mks,0777);return true;}
function Whr($Map,$Mbq){file_put_contents($Map,serialize($Mbq),LOCK_EX);}