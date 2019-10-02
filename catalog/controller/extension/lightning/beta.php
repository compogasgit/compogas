<?php global$Ma,$Mfv,$Mso,$Mti,$Mio,$Mip;define('Wa2',DIR_CACHE."lightning/".'b');$Mso=array("design/translation/getTranslations"=>1,"catalog/category/getCategory"=>1,"catalog/category/getCategories"=>1,"design/theme/getTheme"=>1,"catalog/information/getInformations"=>1);$Mti=array("common/footer"=>1,"common/menu"=>1);if(defined("VERSION"))define("LVERSION",VERSION);else{$Maj=Wak("define('VERSION', '","'",file_get_contents(substr(DIR_SYSTEM,0,-7)."index.php"));if(!$Maj)$Maj="3";define("LVERSION",$Maj);}
$Mti=array();if(empty($Ma)){if(file_exists(Wa2))$Ma=unserialize(file_get_contents(Wa2));else$Ma=array('ci'=>1440,'cj'=>true,'ck'=>true,'cl'=>0,'cm'=>true,'cn'=>false);}
if(!empty($Ma['bz']))$Mti=array();$Mfv=@disk_free_space(DIR_CACHE);if($Mfv<10)$Mfv=2*500*1024*1024;$Mip=-1;$Mus=substr(DIR_SYSTEM,0,-7)."vqmod/checked.cache";if(file_exists($Mus)&&filesize($Mus)>500000)@unlink($Mus);function Wbu(){return new light_db(Ws());}
class light_db{public$driver;public function __construct($driver){global$Mwx;$Mwx["beta"]=$GLOBALS['Ma']['ci']*60;$this->driver=$driver;}
private function Wbw($Mis,$Mit=false){$Mis=str_replace('`','',$Mis);if(!$Mis)return 0;if($Mis!=strtolower($Mis))return 0;static$Miu;if(!$Mit and!empty($Miu[$Mis]))return$Miu[$Mis];$Miv=DIR_CACHE."lightning/beta/qwert";$Map=$Miv."/$Mis";if($Mit){Waw($Miv);if(!file_exists($Map))file_put_contents($Map,'',LOCK_EX);touch($Map);$Miu[$Mis]=time();return$Miu[$Mis];}
if(!file_exists($Map))$Miu[$Mis]=0;else$Miu[$Mis]=filemtime($Map);return$Miu[$Mis];}
private function Wbx($Mhj,$Mbt){$Miu=array_merge(Wby("FROM "," ",$Mhj),Wby("JOIN "," ",$Mhj));foreach($Miu as$Mis){if($this->Wbw(trim($Mis))>=$Mbt)return false;}
return true;}
private function Wbz($Mhj,$Miw=false){if(stripos($Mhj,"SET viewed")&&substr_count($Mhj,"=")<3)return;$Miu=array_merge(Wby("FROM "," ",$Mhj),Wby("INTO "," ",$Mhj),Wby("UPDATE "," ",$Mhj));if(!$Miw){foreach($Miu as$Mis){$Mis=trim($Mis);if(!preg_match("/^[`a-z0-9_-]+$/",$Mis))continue;$this->Wbw($Mis,true);}}
if($Miu&&$Miu[0]==DB_PREFIX."product"){$Mjn=strtoupper(substr(trim($Mhj),0,6));if($Mjn=="INSERT")return;$Mqt=true;$Maf=strripos($Mhj,"WHERE ");if(!$Maf)return;$Mix=substr($Mhj,$Maf+6);if(substr($Mix,-1)==")"&&!strpos($Mix,"("))$Mix=substr($Mix,0,-1);$Mqg=false;$Mqh=strtoupper(substr($Mhj,0,$Maf-1));$Maf=strripos($Mqh,"SET ");if($Maf){$Mqi=trim(substr($Mqh,$Maf+4));if(substr($Mqi,0,1)=="`")$Mqi=substr($Mqi,0,1);if(substr($Mqi,0,8)=="QUANTITY"&&substr_count($Mqi,"=")==1){$Mqg=true;$Mqj=trim(substr($Mqi,strpos($Mqi,"=")+1));if(substr($Mqj,0,1)=="("&&substr($Mqj,-1)==")")$Mqj=substr($Mqj,1,-1);$Mqj=trim(str_replace("QUANTITY","",$Mqj));$Mqk=substr($Mqj,0,1);if($Mqk=="+"||$Mqk=="-")$Mqj=trim(substr($Mqj,1));else$Mqk=false;if(substr($Mqj,0,1)=="'"&&substr($Mqj,-1)=="'")$Mqj=substr($Mqj,1,-1);if(!is_numeric($Mqj))$Mqg=false;}}
$Miy=array();if($Mqg){if(!$Mqt)$Mix=str_replace(" AND subtract = '1'",'',$Mix);$Mql=$this->driver->query("SELECT product_id, quantity FROM ".DB_PREFIX."product WHERE ".$Mix)->rows;foreach($Mql as$Mqm){$Mfi=$Mqm["quantity"];if($Mqt){if($Mqk=="+")$Mne=$Mfi+$Mqj;elseif($Mqk=="-")$Mne=$Mfi-$Mqj;else$Mne=$Mqj;if($Mfi>0&&$Mne>0)continue;if($Mfi<=0&&$Mne<=0)continue;}
$Miy[]=$Mqm["product_id"];}
}else{$Miz=Wak("product_id",'',$Mix);if($Miz){$Miz=Wak('=','',str_replace("'","",$Miz));if(is_numeric($Miz))$Miy=array($Miz);}
if(!$Miy)$Miy=$this->driver->query("SELECT product_id FROM ".DB_PREFIX."product WHERE ".$Mix)->rows;}
if(!$Miy)return;touch(Wa2.'ee',time()-10*60);Wep($Miy);}}
public function Whs($Mhj){static$Mwq;if(!$Mwq)$Mwq="'".date("Y-m-d H").":00:00'";return$this->driver->query(str_replace(array("<= NOW()","< NOW()","> NOW()"),array("<= $Mwq","< $Mwq","> $Mwq"),$Mhj));}
public function query($Mhj){$Mhj=(string)$Mhj;if($Mhj=="SELECT * FROM oc_setting WHERE `code` = 'module_timezone'"){static$Mwu;if(!$Mwu)$Mwu=$this->driver->query($Mhj);return$Mwu;}
if(strpos($Mhj,"ET time_zone")){static$Mwv;if($Mwv)return;$Mwv=1;}
if(stripos($Mhj,"SET viewed")&&substr_count($Mhj,"=")<3&&empty($GLOBALS['Ma']['fy']))return false;static$Mjb;global$Mhd,$Mhe,$Mjc,$Mam,$Mjd;if(!$Mjb){$Mjb=microtime(true);$Mje=true;}else$Mje=false;static$Mjf;if($Mjf++>10000){$Mjf=0;global$Mgy;if($Mgy){$Mjg=microtime(true)-$Mgy;$Mjg/=60;if($Mjg>60*10){$Mjh=debug_backtrace();$Mbp="";foreach($Mjh as$Mcb=>$Mhs){$Mji=$Mhs["function"];if($Mji=="call_user_func_array")break;if(!empty($Mhs["class"]))$Mji=$Mhs["class"].":".$Mji;if(!empty($Mhs["line"]))$Mji.=" [".$Mhs["line"]."]";if($Mbp)$Mbp=$Mji." -> ".$Mbp;else$Mbp=$Mji;}
global$Mk;file_put_contents(DIR_CACHE."lightning/bad","\n\n\nPID: ".getmypid()." URI:".$_SERVER["REQUEST_URI"]." $Mk \n\n SQL: $Mhj \n\n Trace: $Mbp",FILE_APPEND);}
if($Mjg>60*11)die("Hanged, killed by Lightning");}}
global$Mal,$Mz,$Mgz,$Mg_,$Mjj;if($Mal&&time()-$Mal>30){global$log,$Mk;if($log)$log->write("Page generation was killed (took more then 30 sec) - ".$Mk);exit;}
$Mjl=0.05;$Mjm=false;$Mjn=strtoupper(substr(trim($Mhj),0,6));if($GLOBALS['Ma']['cm']&&$Mjn=="SELECT"&&strlen($Mhj)<3*1024)if((!$Mjj or$Mz)and($GLOBALS['Ma']['cn']or defined("LIGHT_FRONTEND"))){Wca($Mhj);$Mjm=true;light_optimize_query($Mhj);if(is_array($Mhj)){$Mhp=Wcb($Mhj);if($Mjc)Wcc($Mhp);if($Mje){$Mhd+=microtime(true)-$Mjb;$Mjb=false;}
Wcd($Mhp);return$Mhp;}}
if(!$GLOBALS['Ma']['cj']or$Mjj){$Mgz++;$Mbt=microtime(true);$Mhp=$this->Whs($Mhj);$Mjo=microtime(true)-$Mbt;$Mhe+=$Mjo;if($Mam)$Mjd[]=array('Mhj'=>$Mhj,'cr'=>false,'Mbt'=>$Mjo,'Mjv'=>Wce());else if(!$Mjj and($Mjo>2)and!defined("HTTP_CATALOG")and(!empty($_SERVER["REQUEST_URI"])and!strpos($_SERVER["REQUEST_URI"],"feed"))){require_once"special.php";$Mwp="slow_query";if($Mjn=="DELETE"||$Mjn=="INSERT"||$Mjn=="UPDATE"||$Mjn=="REPLAC")$Mwp="slow_update_query";Wb("slow_query",true,array("key"=>Wcf($Mhj),"sql"=>$Mhj,"score"=>$Mjo,"url"=>true,"origin"=>Wce()));}
if($Mjc&&stripos($Mhj,"product_id) AS total"))Wcc($Mhp);if($Mje){$Mhd+=microtime(true)-$Mjb;$Mjb=false;}
if(!$Mjj)Wcd($Mhp);return$Mhp;}
$Mg_++;if($Mjn=="SELECT"){if(!$Mjm&&strlen($Mhj)<3*1024){Wca($Mhj);}
global$Mip;if(stripos($Mhj,"FOUND_ROWS()")){if($Mip<0)return$this->Whs($Mhj);$Mjp=Wak("as ","",$Mhj);$Mjp=str_replace("`","",$Mjp);$Mjp=str_replace("'","",$Mjp);if(!$Mjp)$Mjp="FOUND_ROWS()";$Mhp=Wcb(array(array($Mjp=>$Mip)));if($Mjc&&stripos($Mhj,"product_id) AS total"))Wcc($Mhp);if($Mje){$Mhd+=microtime(true)-$Mjb;$Mjb=false;}
return$Mhp;}
$Mpo=false;if(strpos($Mhj,"bf_tmp_"))$Mpo=true;else if(substr($Mhj,0,12)!="SELECT COUNT"&&!stripos($Mhj,"distinct ")){$Mix=Wcz("WHERE ",'',$Mhj);if($Mix){if(!stripos($Mix,"NOW()")and!stripos($Mix,"limit ")and!stripos($Mix,"order ")and!stripos($Mix,"group ")){$Mee=str_replace(array("`",".","_"),'',Wak('','=',$Mix));if(ctype_alnum($Mee))$Mpo=true;}}}
global$Mjq;if(!$Mpo)$Mhp=Wbd("beta",$Mhj);else$Mjq=false;if($Mjq and ((defined("LIGHT_FRONTEND")and($Mjq>time()-$GLOBALS['Ma']['cl']))or$this->Wbx($Mhj,$Mjq))){if($Mhp!==false){$Mip=-1;$Mhp=Wcb(unserialize($Mhp));if($Mam)$Mjd[]=array('Mhj'=>$Mhj,'cr'=>$Mjq,'Mbt'=>0,'Mjv'=>Wce());if($Mjc&&stripos($Mhj,"product_id) AS total"))Wcc($Mhp);if($Mje){$Mhd+=microtime(true)-$Mjb;$Mjb=false;}
Wcd($Mhp);return$Mhp;}}
}elseif($Mjn=="DELETE"||$Mjn=="INSERT"||$Mjn=="UPDATE"||$Mjn=="REPLAC"){$this->Wbz($Mhj);}elseif($Mjn=="CREATE")$this->Wbw(Wak("CREATE TEMPORARY TABLE "," ",Wau("IF NOT EXISTS ","",$Mhj)),true);$Mbt=microtime(true);$Mhp=$this->Whs($Mhj);$Mgz++;$Mg_--;$Mjo=microtime(true)-$Mbt;$Mhe+=$Mjo;if($Mam){$Mpp=false;if(!empty($Mpo))$Mpp=-1;$Mjd[]=array('Mhj'=>$Mhj,'cr'=>$Mpp,'Mbt'=>$Mjo,'Mjv'=>Wce());}
elseif(($Mjo>2)and!defined("HTTP_CATALOG")and(!empty($_SERVER["REQUEST_URI"])and!strpos($_SERVER["REQUEST_URI"],"feed"))){require_once"special.php";$Mwp="slow_query";if($Mjn=="DELETE"||$Mjn=="INSERT"||$Mjn=="UPDATE"||$Mjn=="REPLAC")$Mwp="slow_update_query";Wb($Mwp,true,array("key"=>Wcf($Mhj),"sql"=>$Mhj,"score"=>$Mjo,"url"=>true,"origin"=>Wce()));}
if($Mjn=="SELECT"){if(!$GLOBALS['Ma']['ck']||$Mjo>$Mjl){if(stripos($Mhj,"SQL_CALC_FOUND_ROWS")){$Mbt=microtime(true);$Mjr=$this->driver->query("SELECT FOUND_ROWS()")->row;$Mjo=microtime(true)-$Mbt;$Mhe+=$Mjo;$Mjr=reset($Mjr);$Mhp->rows['Mjr']=$Mjr;global$Mip;$Mip=$Mjr;}
$Mjs=false;if($Mhp->num_rows>5000)$Mjs=true;else if($Mhp->num_rows>500){if(strlen(serialize($Mhp->row))*$Mhp->num_rows>10000000)$Mjs=true;}
if($Mjs and!defined("HTTP_CATALOG")and(!empty($_SERVER["REQUEST_URI"])and!strpos($_SERVER["REQUEST_URI"],"feed"))){require_once"special.php";Wb("big_query",true,array("key"=>Wcf($Mhj),"sql"=>$Mhj,"score"=>strlen(serialize($Mhp->row))*$Mhp->num_rows,"url"=>true,"origin"=>Wce()));}
if(!$Mjs){Wbb("beta",$Mhj,serialize($Mhp->rows));if(stripos($Mhj," RAND()")){global$Mwx;Wbe("beta",$Mhj,time()-$Mwx["beta"]+60);}}}}
if($Mje){$Mhd+=microtime(true)-$Mjb;$Mjb=false;}
if($Mjc&&stripos($Mhj,"product_id) AS total"))Wcc($Mhp);if(isset($Mhp->rows['Mjr']))unset($Mhp->rows['Mjr']);Wcd($Mhp);return$Mhp;}
public function Wch($Mjt){if(is_array($Mjt))return array_map(__METHOD__,$Mjt);if(!empty($Mjt)&&is_string($Mjt)){return str_replace(array('\\',"\0","\n","\r","'",'"',"\x1a"),array('\\\\','\\0','\\n','\\r',"\\'",'\\"','\\Z'),$Mjt);}
return$Mjt;}
public function escape($Mad){if(!$this->driver)return$this->Wch($Mad);return$this->driver->escape($Mad);}
public function countAffected(){return$this->driver->countAffected();}
public function getLastId(){return$this->driver->getLastId();}
public function __destruct(){Wci('Mer','Wc_');unset($this->driver);}}
function Wep($Miy){if(!$Miy)return false;if(is_array(reset($Miy))){$Msv=array();foreach($Miy as$Miz)$Msv[]=$Miz["product_id"];$Miy=$Msv;}
$Miy=array_unique($Miy);while(count($Miy)>100){Wep(array_slice($Miy,0,100));$Miy=array_slice($Miy,100);}
global$Mir;$Mi_=$Mir->query("SELECT page FROM ".DB_PREFIX."lightning_product_to_page WHERE product_id IN(".implode(",",$Miy).")")->rows;if(!$Mi_)return false;global$Man;$Mja=$Man;foreach($Mi_ as$Mfd){$Mfd=$Mfd["page"];Wb_("alpha",$Mfd);Wb_("tetha",$Mfd);Wb_("gamma",$Mfd);}
$Mir->query("DELETE FROM ".DB_PREFIX."lightning_product_to_page WHERE product_id IN(".implode(",",$Miy).")");$Man=$Mja;return true;}
function Wcd(&$Mhp){if(!defined("LIGHT_FRONTEND")or empty($Mhp->row["product_id"]))return;if($Mhp->num_rows>100)return;global$Mfh;foreach($Mhp->rows as$Mik){$Mfh[$Mik["product_id"]]=1;}}
function Ws(){global$Mir;if($Mir)return$Mir;if(LVERSION<"2.0"&&defined("DIR_DATABASE")){$Map=DIR_DATABASE.DB_DRIVER.".php";$Mqr=substr(DIR_SYSTEM,0,-7)."vqmod/vqcache/vq2-system_".str_replace('/','_',str_replace(DIR_SYSTEM,'',$Map));if(file_exists($Mqr))$Map=$Mqr;if(file_exists($Map)){require_once($Map);$Mjy="DB".DB_DRIVER;if(!class_exists($Mjy))$Mjy=DB_DRIVER;$Mir=new$Mjy(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);}else{exit("Lightning: Could not load database driver type ".DB_DRIVER.'!');}
}else{$Map=DIR_SYSTEM."library/db/".DB_DRIVER.".php";if(!file_exists($Map))$Map=DIR_SYSTEM."library/db/".strtolower(DB_DRIVER).".php";require_once($Map);$Mjy="DB\\".DB_DRIVER;if(class_exists($Mjy)){$Mir=new$Mjy(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);}else{exit("Lightning: Could not load database driver ".DB_DRIVER.'!');}}
return$Mir;}
function Wcm(&$Mhj){global$Mio;if(stripos($Mhj,"ELECT COUNT")and stripos($Mhj,"product_id) AS total"))return true;if(stripos($Mhj,"'information_id="))return true;$Mis=Wcg("(\*|xseo) FROM "," ",$Mhj);if(!$Mis)return false;if(substr($Mis,0,1)=="`")$Mis=substr($Mis,1,-1);$Mis=substr($Mis,strlen(DB_PREFIX));if(!in_array($Mis,$Mio))return false;return true;}
global$Mjj;$Mjj=file_exists(DIR_LOGS.'cv');function Wcb($Mjz){$Mga=new stdClass();if(!$Mjz){$Mga->num_rows=0;$Mga->row=array();$Mga->rows=array();return$Mga;}
if(isset($Mjz['Mjr'])){global$Mip;$Mip=$Mjz['Mjr'];unset($Mjz['Mjr']);}
$Mga->num_rows=count($Mjz);if($Mga->num_rows)$Mga->row=reset($Mjz);else$Mga->row=array();$Mga->rows=$Mjz;return$Mga;}
function Wcn(){foreach(glob(DIR_CACHE."lightning/".'b'."*")as$Mj_)if(is_file($Mj_))unlink($Mj_);foreach(glob(DIR_CACHE."lightning/epsilon/*")as$Mj_)if(!is_dir($Mj_))unlink($Mj_);}
function Wco($Mce){if(substr_count($Mce,'/')==2)$Mce=substr($Mce,0,strrpos($Mce,'/'));if(!in_array($Mce,array("setting/setting","localisation/language","localisation/currency","catalog/category","catalog/information")))return;Wcn();}
function Wcp(){global$Mir;if($Mir->query("SHOW tables like '".DB_PREFIX."lightning_product_to_page'")->row)return;$Mir->query("CREATE TABLE ".DB_PREFIX."lightning_product_to_page(product_id int(11), page varchar(36), KEY (product_id), KEY(page))");$Mir->query("CREATE TABLE ".DB_PREFIX."lightning_modified (page varchar(36), cs bigint(13), md int(11), scs bigint(13), smd int(11), KEY (page))");}
function Wft($Mcl,$Msw=false){if(substr($Mcl,-1)!="/")$Mcl.="/";if(!file_exists($Mcl))return true;$Map=$Mcl.".htaccess";if(file_exists($Map)){$Mdd=file_get_contents($Map);return strpos($Mdd,"speed.devs.mx");}
$Msx="# REAL SITE SPEED .htaccess\n# Visit http://speed.devs.mx for more info\n# ----------------------------------------\n\nFileETag none\n\n\n<IfModule mod_expires.c>\n	ExpiresActive On\n	ExpiresDefault \"access plus 99 days\"\n</IfModule>\n\n<ifmodule mod_deflate.c>\n	AddType application/x-font-woff .woff\n	AddType image/svg+xml .svg\n	AddOutputFilterByType DEFLATE text/text text/html text/plain text/xml text/css application/x-javascript application/javascript text/javascript image/svg+xml application/x-font-woff\n</ifmodule>";if($Msw)$Msx=str_replace("99 days","1 day",$Msx);@file_put_contents($Map,$Msx);return file_exists($Map);}
function Wbv(){global$Mir;if($Mir){Wcp();Wcq('product_to_category','category_id','product_id');if(LVERSION<"3"){Wcq('url_alias','query');Wcq('url_alias','keyword');}
Wcq('product_option_value','product_id');Wcq('product_special','product_id');Wcq('product','model');Wcq('product','date_modified');Wcq('product_image','product_id');Wcq('product_option','product_id');Wcq('order_product','order_id');}
Wab();if(!Wft(DIR_APPLICATION."view")){Wft(DIR_APPLICATION."view/javascript");Wft(DIR_APPLICATION."view/theme");}
Wft(DIR_APPLICATION."language");if(!Wft(DIR_IMAGE)){Wft(DIR_IMAGE."catalog");Wft(DIR_IMAGE."cache");}
foreach(glob(substr(DIR_SYSTEM,0,-7)."*",GLOB_ONLYDIR)as$Mks)if(!Wft($Mks."/view",true)){Wft($Mks."/view/image",true);Wft($Mks."/view/javascript",true);Wft($Mks."/view/stylesheet",true);}
require_once"special.php";$Mbs=substr(DIR_SYSTEM,0,-7)."index.php";$Mkb="extension/lightning/gamma.php";$Mdt=file_get_contents($Mbs);if(!strpos($Mdt,$Mkb)){$Maf=strpos($Mdt,"index.php");$Maf=strpos($Mdt,"}",$Maf+1);if($Maf){$Maf++;$Mdt=substr($Mdt,0,$Maf)."\n\nif (file_exists(".'$'."li"." = DIR_APPLICATION.'/controller/extension/lightning/gamma.php')) require_once(".'$'."li"."); //Lightning".substr($Mdt,$Maf);$Mdt=str_replace("\nif (file_exists(DIR_SYSTEM.'lightning/gamma.php')) require(DIR_SYSTEM.'lightning/gamma.php'); //Lightning",'',$Mdt);file_put_contents($Mbs,$Mdt);}
$Mdt=file_get_contents($Mbs);if(strpos($Mdt,$Mkb))Wb("index_php",false);}else Wb("index_php",false);require_once"omega.php";$Mdt=Whh();if($Mdt){$Mtq=substr(DIR_SYSTEM,0,-7)."robots.txt";file_put_contents($Mtq,$Mdt);if(file_get_contents($Mtq)==$Mdt)Wb("robots_txt",false);}
$Mwc=glob(DIR_LOGS."*");$Mvt=10*1024*1024;Wbg();}
function Wca(&$Mhj){if(strpos($Mhj,"\n',"))return;if(strpos($Mhj,"#"))return;$Mhj=preg_replace('/\s+/',' ',$Mhj);}
function Wcq($Mis,$Mkc,$Mkd=false){global$Mir;$Maz=$Mkc;if($Mkd)$Maz.="_".$Mkd;if($Mir->query("SHOW KEYS FROM `".DB_PREFIX."$Mis` WHERE Key_name='$Maz'")->row)return;if(!$Mkd)$Mir->query("ALTER TABLE `".DB_PREFIX."$Mis` ADD INDEX `$Mkc` (`$Mkc`)");else$Mir->query("ALTER TABLE `".DB_PREFIX."$Mis` ADD INDEX `$Maz` (`$Mkc`,`$Mkd`)");}
define('Wcw',"/meters");function Wad($Man,$Mkg=false,$Mkh=false){$Map=$Man.Wcw;if(file_exists($Map)){$Mbq=explode(',',Wct($Map));if(!$Mkg and!$Mkh)return$Mbq;$Mki=$Mbq[0];$Mbj=$Mbq[1];}else{$Mki=0;$Mbj=0;}
$Mki+=$Mkg;if($Mki<0)$Mki=0;$Mbj+=$Mkh;if($Mbj<0)$Mbj=0;file_put_contents($Map,$Mki.','.$Mbj,LOCK_EX);}
function Wct($Map){$Mkj=fopen($Map,'rt');flock($Mkj,LOCK_SH);$Mhp=file_get_contents($Map);fclose($Mkj);return$Mhp;}
function Wbb($Mwy,$Mgj,$Mbq,$Mkk=false){$Man=DIR_CACHE."lightning/$Mwy";if(Waw($Man))file_put_contents($Man.Wcw,"0,0",LOCK_EX);global$Mfv,$Mb;if($Mfv<$Mb)return;$Mao=md5($Mgj);$Miv=$Man."/".substr($Mao,0,2);if($Mkk){global$Mwx;Wcu($Miv,$Mwx[$Mwy]);}
$Map=$Miv."/c".substr($Mao,2);$Mki=0;if(file_exists($Map))$Mbj=strlen($Mbq)-filesize($Map);else{$Mbj=strlen($Mbq);$Mki=1;}
Wad($Man,$Mki,$Mbj);Waw($Miv);file_put_contents($Map,$Mbq,LOCK_EX);return$Map;}
function Whg($Map){Wcv($Map);return file_exists($Map);}
function Wcv($Map){static$Mkl;if(!$Mkl)$Mkl=phpversion();if($Mkl<"5.3")clearstatcache();else clearstatcache(true,$Map);}
function Wae($Mwy,$Mgj){$Man=DIR_CACHE."lightning/$Mwy";$Mao=md5($Mgj);$Miv=$Man."/".substr($Mao,0,2);$Map=$Miv."/c".substr($Mao,2);if(!Whg($Map))return false;return(filemtime($Map));}
function Wbe($Mwy,$Mgj,$Mbt,$Mkm=false){$Man=DIR_CACHE."lightning/$Mwy";$Mao=md5($Mgj);$Miv=$Man."/".substr($Mao,0,2);Waw($Miv);$Map=$Miv."/c".substr($Mao,2);if(!Whg($Map)){if(!$Mkm)return;file_put_contents($Map,'');Wad($Man,1,0);}
@touch($Map,$Mbt);}
function Wbd($Mwy,$Mgj,$Mkn=false){global$Mjq,$Mwx;$Mfg=$Mwx[$Mwy];$Man=DIR_CACHE."lightning/$Mwy";$Mjq=false;$Mao=md5($Mgj);$Miv=$Man."/".substr($Mao,0,2);$Map=$Miv."/c".substr($Mao,2);if(!$Mkn)Wcu($Miv,$Mfg);if(!Whg($Map))return false;$Mjq=filemtime($Map);static$Mmk;if(!$Mmk)$Mmk=time();$Mbt=$Mmk-$Mfg;if(!$Mkn and$Mjq<$Mbt){unlink($Map);Whv("DELETED: $Map too old (single)");return false;}
$Mbq=file_get_contents($Map);return$Mbq;}
function Wcu($Miv,$Mfg){if(!$Mfg)return;static$Mka;if(!empty($Mka[$Miv]))return;$Mka[$Miv]=true;if(!Whg($Miv))return;$Mbt=time();if(filemtime($Miv)<$Mbt-10*60){touch($Miv);$Mbt-=$Mfg;$Mki=0;$Mbj=0;if(substr($Miv,-1)=="/")$Miv=substr($Miv,0,-1);$Mko=glob($Miv."/c*");$Mfj=true;if($Mko)foreach($Mko as$Mj_)if(Whg($Mj_)&&filemtime($Mj_)<$Mbt){$Mbj+=filesize($Mj_);@unlink($Mj_);$Mki++;}
Wad($Miv,-$Mki,-$Mbj);}}
function Wag($Mwy,$Mgj=false){$Man=DIR_CACHE."lightning/$Mwy";if($Mgj){$Mao=md5($Mgj);$Miv=$Man."/".substr($Mao,0,2);$Map=$Miv."/c".substr($Mao,2);if(Whg($Map)){Wad($Man,-1,filesize($Map));unlink($Map);}
return;}
foreach(glob($Man."/*")as$Miv){if(is_dir($Miv)){foreach(glob($Miv."/*")as$Mj_)if(Whg($Mj_))@unlink($Mj_);rmdir($Miv);}else if(Whg($Miv))@unlink($Miv);}
if(Whg($Man.Wcw))@unlink($Man.Wcw);}
function Wb_($Mwy,$Mkp){$Man=DIR_CACHE."lightning/$Mwy";$Map=$Man."/".$Mkp;if(Whg($Map)){Wad($Man,-1,-filesize($Map));unlink($Map);}}
function Wby($Mdl,$Min,$Mdk){preg_match_all("/$Mdl([^$Min]*)/i",$Mdk,$Mcu);return($Mcu[1]);}
function Wcg($Mdl,$Min,&$Mdk){preg_match("/$Mdl([^$Min]*)/i",$Mdk,$Mcu);if(isset($Mcu[1]))return($Mcu[1]);else return"";}
function Wcw($Mdl,$Mbn="",$Mdk){$Mbm=array();$Mei=0;while(($Mei=strpos($Mdk,$Mdl,$Mei))!==false){$Mei+=strlen($Mdl);$Mey=strpos($Mdk,$Mbn,$Mei);if($Mey!==false){$Mbm[]=trim(substr($Mdk,$Mei,$Mey-$Mei));$Mei=$Mey+strlen($Mbn);}}
return$Mbm;}
function Wat($Mdl,$Mbn="",$Mdk){$Mbm=array();$Mei=0;while(($Mei=strpos($Mdk,$Mdl,$Mei))!==false){$Mei+=strlen($Mdl);$Mey=strpos($Mdk,$Mbn,$Mei);if($Mey!==false){$Mbm[]=substr($Mdk,$Mei-strlen($Mdl),$Mey-$Mei+strlen($Mdl)+strlen($Mbn));$Mei=$Mey+strlen($Mbn);}}
return$Mbm;}
function Wau($Mkq=false,$Meu='',$Mdk=false){if(($Maf=strpos($Mkq,'*'))!==false){$Mev=substr($Mkq,0,$Maf);$Mex=substr($Mkq,$Maf+1);$Mbm=Wcx("","",$Mev,$Mex,$Mdk);$Mbm=str_ireplace($Mev.$Mex,$Meu,$Mbm);}else $Mbm=str_ireplace($Mkq,$Meu,$Mdk);return$Mbm;}
function Wcx($Mez,$Meu,$Mdl,$Mbn,$Mdk){$Mei=0;while(($Mei=stripos($Mdk,$Mdl,$Mei))!==false){$Mei+=strlen($Mdl);if($Mbn)$Mey=stripos($Mdk,$Mbn,$Mei);else$Mey=strlen($Mdk);if($Mey){$Me_=substr($Mdk,0,$Mei);$Mfa=substr($Mdk,$Mey);$Mfb=substr($Mdk,$Mei,$Mey-$Mei);$Mfc=strlen($Mfb);if($Mez!=='')$Mfb=str_ireplace($Mez,$Meu,$Mfb);else$Mfb=$Meu;$Mdk=$Me_.$Mfb.$Mfa;$Mey=$Mey+strlen($Mfb)-$Mfc;$Mei=$Mey+strlen($Mbn);if($Mei>strlen($Mdk))break;}}
return$Mdk;}
function Wcy($Mdl,$Mbn="",$Mdk){$Mkr=Wak($Mdl,$Mbn,$Mdk);if($Mkr)return$Mkr;return$Mdk;}
function Wak($Mdl,$Mbn="",$Mdk){$Mbm='';if($Mdl)$Mei=stripos($Mdk,$Mdl);else$Mei=0;if($Mei!==false){$Mei+=strlen($Mdl);if($Mbn)$Mey=stripos($Mdk,$Mbn,$Mei);else$Mey=strlen($Mdk);if($Mey!==false)$Mbm=trim(substr($Mdk,$Mei,$Mey-$Mei));}
return$Mbm;}
function Wcz($Mdl,$Mbn="",$Mdk){$Mbm='';if($Mdl)$Mei=strripos($Mdk,$Mdl);else$Mei=0;if($Mei!==false){$Mei+=strlen($Mdl);if($Mbn){if($Mdl)$Mey=stripos($Mdk,$Mbn,$Mei);else$Mey=strripos($Mdk,$Mbn,$Mei);}
else$Mey=strlen($Mdk);if($Mey!==false)$Mbm=trim(substr($Mdk,$Mei,$Mey-$Mei));}
return$Mbm;}
function Waw($Mks){if(file_exists($Mks))return false;Wab();mkdir($Mks,0777,true);@chmod($Mks,0777);Wbg();return true;}
function Wab(){global$Mbd,$Mkt,$Mku;if($Mbd)return;$config=Wc_("config");if(!$config)return;$Mbd=true;$Mkt=$config->get("config_error_display");$Mku=$config->get("config_error_log");$config->set("config_error_display",0);$config->set("config_error_log",0);}
function Wbg(){global$Mbd,$Mkt,$Mku;if(!$Mbd)return;$config=Wc_("config");if(!$config)return;$Mbd=false;$config->set("config_error_display",$Mkt);$config->set("config_error_log",$Mku);}
function Wda($Mhj){if(stripos($Mhj,"ORDER BY p.date_added")){if(!stripos($Mhj,DB_PREFIX."product p"))return false;$Mhj=Wau("ORDER BY p.date_added*DESC ","ORDER BY p.product_id DESC ",$Mhj." ");$Mhj=Wau("ORDER BY p.date_added*ASC ","ORDER BY p.product_id ASC ",$Mhj);return trim($Mhj);}
if(!stripos($Mhj,"FROM ".DB_PREFIX."product_to_category p2c"))return false;$Mhj=Wau("ORDER BY p.sort_order*ASC ","ORDER BY p2c.product_id DESC ",$Mhj." ");return trim($Mhj);}
function Wdb($Mhj){return array(array("total"=>0));}
function Wdc($Mhj){static$Mkv;if(isset($Mkv[$Mhj]))return$Mkv[$Mhj];$Mhp=Wdd($Mhj);$Mkv[$Mhj]=$Mhp;return$Mhp;}
function Wde($Mhj){global$Mkw;if($Mkw)return false;$Mga=Wcg("`query` = '","'",$Mhj);global$Mkx;if(!isset($Mkx[$Mga])){$Mhp=Wdd($Mhj);if($Mhp){$Mfj=reset($Mhp);if(count($Mhp)==1&&count($Mfj)<4)$Mkx[$Mga]=$Mfj["keyword"];else$Mkx[$Mga]=$Mhp;}else$Mkx[$Mga]=false;return$Mhp;}
$Mky=$Mkx[$Mga];if($Mky===false)return array();if(is_array($Mky))return$Mky;return array(array("query"=>$Mga,"keyword"=>$Mky));}
function Wdf($Mhj){static$Mkz,$Mij;if(!stripos($Mhj,"ELECT DISTINCT *, pd.name")){$Mhp=Wdd($Mhj);$Mkz=array();if(count($Mhp)<1024)foreach($Mhp as$Mik)if(!empty($Mik["product_id"]))$Mkz[]=$Mik["product_id"];return$Mhp;}
$Mfk=Wcg("p.product_id = '","'",$Mhj);if(!$Mfk)return false;if(!empty($Mij[$Mfk]))return array($Mij[$Mfk]);if(empty($Mkz)or!in_array($Mfk,$Mkz)){if($Mij and count($Mij)>1024)return false;$Mhp=Wdd($Mhj);if(count($Mhp)<1024)foreach($Mhp as$Mik){$Mij[$Mik["product_id"]]=$Mik;if(isset($Mik["xseo"]))$Mkx["product_id=".$Mik["product_id"]]=$Mik["xseo"];}
return$Mhp;};if($Mij and count($Mij)>1024)return false;$Mhj=str_ireplace("p.product_id = '$Mfk'","p.product_id IN (".implode(",",$Mkz).")",$Mhj);$Mfq=Wc_("config")->get("config_seo_url");if($Mfq){$Mk_=Wdg();if(LVERSION>="3")$Msp=DB_PREFIX."seo_url";else$Msp=DB_PREFIX."url_alias";$Mhj=str_ireplace("AS discount,","AS discount, "."(SELECT `keyword` FROM $Msp WHERE `query` = CONCAT('product_id=', p.product_id) $Mk_) as xseo,",$Mhj);}
$Mhp=Wdd($Mhj);$Mkz=false;global$Mkx;foreach($Mhp as$Mik){$Mij[$Mik["product_id"]]=$Mik;if($Mfq){if(empty($Mik["xseo"]))$Mik["xseo"]=false;$Mkx["product_id=".$Mik["product_id"]]=$Mik["xseo"];}}
if(empty($Mij[$Mfk]))return false;return array($Mij[$Mfk]);}
function Wdh($Mhj){$Mfs=Wc_("config")->get("config_store_id");if(!$Mfs)$Mfs="0";$Mhj=str_ireplace(" AND p2s.store_id = '".$Mfs."'","",$Mhj);$Mhj=str_ireplace(" AND p2s.store_id = ".$Mfs,"",$Mhj);if(stripos($Mhj,"store_id"))return false;return Wau(" LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (*)","",$Mhj);}
function Wdi($Mhj){$Mhj=str_ireplace("LCASE(name)","LCASE(pd.name)",$Mhj);if(!stripos($Mhj,"pd.name")or!stripos($Mhj,"product p "))return false;if(stripos($Mhj,"as innertable"))return false;$Mhj=str_ireplace("LCASE(pd.name) ASC, LCASE(pd.name)","LCASE(pd.name)",$Mhj);$Mhj=str_ireplace("LCASE(pd.name) DESC, LCASE(pd.name)","LCASE(pd.name)",$Mhj);$Mhj=str_ireplace("LCASE(p.model) ASC, LCASE(pd.name)","LCASE(pd.name)",$Mhj);$Mhj=str_ireplace("LCASE(p.model) DESC, LCASE(pd.name)","LCASE(pd.name)",$Mhj);$Mhj=str_ireplace("ORDER BY pd.name","ORDER BY p.model",$Mhj);$Mhj=str_ireplace("LCASE(pd.name) ASC","p.model ASC",$Mhj);$Mhj=str_ireplace("LCASE(pd.name) DESC","p.model DESC",$Mhj);if(!stripos($Mhj,"pd.")and!stripos($Mhj,"name")){$Mhj=Wau(" LEFT JOIN ".DB_PREFIX."product_description pd ON (*)","",$Mhj);}
return$Mhj;}
function Wdj($Mhj){if(stripos($Mhj,"*")or stripos($Mhj,"name"))return false;$Mhj=Wau(" pd.language_id = '*' AND","",$Mhj);$Mhj=Wau(" WHERE pd.language_id = '*'","",$Mhj);$Mhj=Wau(" LEFT JOIN ".DB_PREFIX."product_description pd ON (*)","",$Mhj);if(stripos($Mhj,"pd."))return false;return$Mhj;}
function Wfz($Mhj){$Mhj=Wau(" AND p.date_available <= NOW()","",$Mhj);return$Mhj;}
function Wdk($Mhj){if(stripos($Mhj,"MIN(")or stripos($Mhj,"MAX(")or stripos($Mhj,"SUM("))return false;$Mhj=Wau(" GROUP BY p.product_id","",$Mhj);return$Mhj;}
function Wdl($Mhj){if(strpos($Mhj,"SELECT",10))return false;$Mis=Wcz("FROM "," ",$Mhj);if(!$Mis)return false;$Mla=Wak("FROM $Mis "," ",$Mhj);if(!$Mla||strlen($Mla>3)||$Mla=="WHERE")return false;$Mcj=Wcz("","WHERE",$Mhj);if(!$Mcj)return false;$Mlb=(int)Wcy(",","",Wcz("LIMIT","",$Mhj));if(!$Mlb)return false;$Mlc=Wa2.'cx';if(Whg($Mlc)&&filemtime($Mlc)>time()-60*60)$Miu=unserialize(file_get_contents($Mlc));else$Miu=array();if(empty($Miu[$Mis])){$Mfi=Wdd("SHOW KEYS FROM $Mis WHERE Key_name = 'PRIMARY'");if(!$Mfi)return false;$Mld=$Mfi[0]["Column_name"];$Mjf=Wdd("SELECT count(*) FROM $Mis");$Mjf=$Mjf[0];$Mjf=reset($Mjf);if(!$Mjf)return false;$Mle=Wdd("SELECT $Mld FROM $Mis ORDER BY $Mld LIMIT 1");$Mle=$Mle[0][$Mld];$Mlf=Wdd("SELECT $Mld FROM $Mis ORDER BY $Mld DESC LIMIT 1");$Mlf=$Mlf[0][$Mld];$Miu[$Mis]=array('id'=>$Mld,'Mjf'=>$Mjf,'Mle'=>$Mle,'Mlf'=>$Mlf);file_put_contents($Mlc,serialize($Miu),LOCK_EX);}
$Mlg=$Miu[$Mis];if($Mlg['Mjf']<$Mlb*5)return false;$Mlh=Wcz(""," ORDER ",$Mhj);$Mix=Wcy("","GROUP BY",Wcz("WHERE","",$Mlh));$Mhp=array();$Mli=array();$Mlj=0;while(count($Mhp)<$Mlb&&$Mlj++<3){$Mlk=array();for($Mcb=0;$Mcb<$Mlb;$Mcb++){$Mll=0;while(in_array($Mld=rand($Mlg['Mle'],$Mlg['Mlf']),$Mli)&&$Mll++<10);$Mlk[]=$Mld;$Mli[]=$Mld;}
$Mlm=Wdd("$Mcj WHERE $Mla.".$Mlg['id']." IN (".implode(',',$Mlk).") AND ($Mix)");$Mhp=array_merge($Mhp,$Mlm);}
$Mhp=array_slice($Mhp,0,$Mlb);if(count($Mhp)<$Mlb)return false;return$Mhp;}
function Wdm($Mln){static$Mcr;if(!$Mcr){$Mlo=Wdd("SELECT category_id, parent_id FROM ".DB_PREFIX."category");$Mlp=array();$Mcr=array();foreach($Mlo as$Mlq){$Mlp[$Mlq["category_id"]]=$Mlq["parent_id"];$Mcr[$Mlq["parent_id"]][]=$Mlq["category_id"];}
$Mlr=array();foreach($Mlp as$Mls=>$Mlt)if(empty($Mcr[$Mls]))$Mlr[$Mls]=true;$Mlu=true;while($Mlu){$Mlv=array();$Mlu=false;foreach($Mlp as$Mls=>$Mlt)if(!empty($Mlr[$Mls])and empty($Mlr[$Mlt])){if(!empty($Mcr[$Mls]))$Mcr[$Mlt]=array_unique(array_merge($Mcr[$Mlt],$Mcr[$Mls]));if(!in_array($Mlt,$Mlv))$Mlv[]=$Mlt;$Mlu=true;}
foreach($Mlv as$Mlq)$Mlr[$Mlq]=true;}}
if(empty($Mcr[$Mln]))return$Mln;return$Mln.','.implode(',',$Mcr[$Mln]);}
function Wdn($Mbq){global$Ma;if(!empty($Ma['fr']))return-1;if(!empty($_REQUEST["ajaxfilter"]))return-1;static$Mlw;if(!$Mlw){global$Mjj;if($Mjj and empty($_GET["li_op"]))$Mlw=-1;else$Mlw=1;}
if($Mlw==-1)return-1;global$Mjc,$Mjq;if(!empty($_POST["mfp"]))$Mbq["mfp"]=$_POST["mfp"];elseif(!empty($_GET["mfp"]))$Mbq["mfp"]=$_GET["mfp"];$Mjc=serialize($Mbq);if(($Mbm=Wci($Mjc))!==-1)return$Mbm;global$Mcq;if(!$Mcq){global$Mcq,$request;if(!empty($request->get["route"]))$Mcq=$request->get["route"];}
static$Mlx;if(!$Mlx)$Mlx=time()-60*60;static$Mer;if(isset($Mer[$Mjc]))return$Mer[$Mjc];$Mhp=Wbd("beta",$Mjc,true);if($Mjq<$Mlx+rand(0,10*60))return-1;if(!is_numeric($Mhp))return-1;$Mer[$Mjc]=$Mhp;Wci($Mjc,$Mhp);$Mjc=false;return$Mhp;}
function Wcc($Mhp){global$Mjc;if(!isset($Mhp->row["total"])||count($Mhp->rows)>1){$Mjc=false;return;}
Wbb("beta",$Mjc,$Mhp->row["total"]);Wci($Mjc,$Mhp->row["total"]);$Mjc=false;}
function Wdo($Mhj){$Mly=Wcg("AND cp.path_id = '","'",$Mhj);if(!$Mly)return false;$Mhj=str_ireplace(" FROM ".DB_PREFIX."category_path cp LEFT JOIN ".DB_PREFIX."product_to_category p2c ON (cp.category_id = p2c.category_id)"," FROM ".DB_PREFIX."product_to_category p2c",$Mhj);$Mlo=Wdm($Mly);if(strpos($Mlo,','))$Mlo="IN ($Mlo)";else$Mlo="= $Mlo";if(!stripos($Mhj,"p2c.category_id"))return false;$Mhj=str_ireplace(" AND cp.path_id = '".$Mly."'"," AND p2c.category_id ".$Mlo,$Mhj);$Mhj=str_ireplace(" AND `cp`.`path_id` = '".$Mly."'"," AND p2c.category_id ".$Mlo,$Mhj);if(stripos($Mhj,"path_id"))return false;return$Mhj;}
function Wdg(){static$Mhp;if($Mhp)return$Mhp;if(LVERSION>="3")$Mhp="AND language_id='".Wc_("config")->get("config_language_id")."' AND store_id='".Wc_("config")->get("config_store_id")."' LIMIT 1";elseif(Wdd("SHOW COLUMNS FROM ".DB_PREFIX."url_alias LIKE 'language_id'"))$Mhp="AND language_id='".Wc_("config")->get("config_language_id")."' LIMIT 1";elseif(Wdd("SHOW COLUMNS FROM ".DB_PREFIX."url_alias LIKE 'lang'"))$Mhp="AND lang='".Wc_("config")->get("config_language_id")."' LIMIT 1";else$Mhp="LIMIT 1";return$Mhp;}
function Wdp($Mhj){if(!defined("LIGHT_FRONTEND")or!stripos($Mhj,"FROM ".DB_PREFIX."category c LEFT JOIN"))return false;static$Mlo;if(!$Mlo){$Mga="SELECT * FROM ".DB_PREFIX."category c LEFT JOIN ".DB_PREFIX."category_description cd ON (c.category_id=cd.category_id) LEFT JOIN ".DB_PREFIX."category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '".(int)Wc_("config")->get("config_language_id")."' AND c2s.store_id = '".(int)Wc_("config")->get("config_store_id")."'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";$Mfq=Wc_("config")->get("config_seo_url");if($Mfq){$Mk_=Wdg();if(LVERSION>="3")$Msp=DB_PREFIX."seo_url";else$Msp=DB_PREFIX."url_alias";$Mga=str_ireplace("* FROM","*, (SELECT `keyword` FROM $Msp WHERE `query` = CONCAT('category_id=', c.category_id) $Mk_) as xseo FROM",$Mga);}
$Mlz=Wdd($Mga);$Mlo=array();foreach($Mlz as$Mik){$Mik["children"]=0;$Mlo[$Mik["category_id"]]=$Mik;}
$Mlo[0]["children"]=0;global$Mkx;foreach($Mlo as$Mld=>$Mik){if(empty($Mik["category_id"]))continue;if($Mfq){if(empty($Mik["xseo"]))$Mik["xseo"]=false;$Mkx["category_id=".$Mik["category_id"]]=$Mik["xseo"];}
if(!$Mik["parent_id"])$Mik["parent_id"]=0;if(!isset($Mlo[$Mik["parent_id"]]["children"]))$Mlo[$Mik["parent_id"]]["children"]=0;$Mlo[$Mik["parent_id"]]["children"]++;$Mlo[$Mik["parent_id"]]["childs_id"][]=$Mik["category_id"];}}
if(!$Mlo)return false;$Ml_=Wcg("c.parent_id = '","'",$Mhj);if($Ml_!==''){if(empty($Mlo[$Ml_]["childs_id"]))return array();$Mhp=array();$Mlb=Wak(" LIMIT ","",$Mhj);if(!$Mlb)$Mlb=10000;foreach($Mlo[$Ml_]["childs_id"]as$Mld){$Mhp[]=$Mlo[$Mld];if(count($Mhp)==$Mlb)break;}
return$Mhp;}
$Mln=Wcg("c.category_id = '","'",$Mhj);if($Mln==="")return false;if(!$Mln or empty($Mlo[$Mln]["category_id"]))return array();return array($Mlo[$Mln]);}
function Wdq($Mkq=false){$Mjh=debug_backtrace();$Mbp="";foreach($Mjh as$Mcb=>$Mhs){if($Mcb<3)continue;$Mji=$Mhs["function"];if($Mji=="call_user_func_array")break;if(!empty($Mhs["class"]))$Mji=$Mhs["class"].":".$Mji;if($Mbp)$Mbp=$Mji." -> ".$Mbp;else$Mbp=$Mji;}
if(!$Mkq)return$Mbp;$Mkq=explode(',',$Mkq);foreach($Mkq as$Mma)if(strpos($Mbp,$Mma)!==false)return true;return false;}
function Wdr($Mmb){$Mmb=explode(", ",$Mmb);$Mjh=debug_backtrace(false);foreach($Mjh as$Mcb=>$Mhs){if(empty($Mhs["class"]))continue;if(in_array($Mhs["class"],$Mmb))return true;}
return false;}
function Wdd($Mhj){global$db,$Ma;$Mer=$Ma['cm'];$Ma['cm']=false;$Mhp=$db->query($Mhj)->rows;$Ma['cm']=$Mer;return$Mhp;}
function Wcf($Mhj){return preg_replace("/[\d\s,]|'[^']*'/",'',str_replace('\\'."'","",$Mhj));}
function light_optimize_query(&$Mhj){static$Mmc;if(!$Mmc){$Mmd=DIR_CACHE."lightning/".'b'.'Mgd';if(!file_exists($Mmd)){global$Ma;$Ma['cm']=false;return;}
$Mmc=unserialize(file_get_contents($Mmd));foreach($Mmc as&$Mme)$Mme=explode(" | ",$Mme);}
static$Mmf;$Mgj=Wcf($Mhj);if(isset($Mmf[$Mgj])){foreach($Mmf[$Mgj]as$Maz){$Mlh=$Maz($Mhj);if(is_array($Mlh)){$Mhj=$Mlh;return;}
if($Mlh)$Mhj=$Mlh;}
return;}
$Mmg=array();foreach($Mmc as$Maz=>$Mkq)if(true){$Met=false;foreach($Mkq as$Mew)if(stripos($Mhj,$Mew)!==false){$Met=true;break;}
if(!$Met)continue;$Mlh=$Maz($Mhj);$Mmg[]=$Maz;if(is_array($Mlh)){$Mmf[$Mgj]=$Mmg;$Mhj=$Mlh;return;}
if(!$Mlh)continue;$Mhj=$Mlh;}
$Mmf[$Mgj]=$Mmg;}
function Wds($Mgj,$Mad){global$Mml,$Mkx,$Mkw;if($Mgj=="config_language_id"and($Mml or$Mkx))$Mkw=2;}
function Wci($Mgj,$Mad='x'){if(empty($GLOBALS['Ma']['cz']))return-1;global$Mkw;static$Mfu;if($Mkw){if($Mkw==1&&$Mfu==$_SESSION["language"])$Mkw=false;else return-1;}
if(!empty($Mq["language"])){if(!$Mfu)$Mfu=$Mq["language"];else if($Mfu!=$Mq["language"]){$Mkw=1;return-1;}}
static$storage,$Mmm,$Map,$Mvf;global$Mmn,$Mml;if(!$Mvf){if($Mgj=='Mer')return-1;if(!defined("LIGHT_FRONTEND")or!empty($GLOBALS['Mab']))return-1;global$Mcq;if(!$Mcq){if(!empty(Wc_("request")->get["route"]))$Mcq=Wc_("request")->get["route"];}
if(strpos($Mcq,"language"))return-1;$Mce=str_replace('/','',$Mcq);if($Maf=strpos($Mce,' ')||$Maf=strpos($Mce,"'")||$Maf=strpos($Mce,"&"))$Mce=substr($Mce,0,$Maf);if($Mce=="commonhome")$Mce="";$config=Wc_("config");if(!$config)return-1;$Map=DIR_CACHE."lightning/epsilon/".$config->get("config_store_id").$config->get("config_language_id").$Mce.".inc";Wcv($Map);if(!$Mce and!empty($GLOBALS['Ma']['Mnt'])and!empty($GLOBALS['Ma']['n'])and empty($GLOBALS['Mz']))$Mmn=!file_exists($Map);else$Mmn=!(file_exists($Map)&&filemtime($Map)>time()-3600);$Mvf=true;if(!$Mmn){require_once$Map;$Mml=true;}else if($Mce){$Mcc=DIR_CACHE."lightning/epsilon/".$config->get("config_store_id").$config->get("config_language_id").".inc";if(file_exists($Mcc)){require_once$Mcc;$Mmm=$storage;$storage=array();}}}
if(!is_string($Mgj))$Mgj=(string)$Mgj;if($Mad!=='x'){if($Mmn){if(stripos($Mgj,"ELECT COUNT"))return 0;if(stripos($Mgj,"/not_found"))return 0;if(strlen(serialize($Mad))>20000)return 0;$storage[$Mgj]=$Mad;if($Mgj=='Mer'&&count($storage)>5){Waw(DIR_CACHE."lightning/epsilon/");file_put_contents($Map,"<?php\n $"."storage = ".var_export($storage,true).";",LOCK_EX);}}
return 0;}
if(!empty($Mmm)and isset($Mmm[$Mgj]))$storage[$Mgj]=$Mmm[$Mgj];if(isset($storage[$Mgj]))if(is_object($storage[$Mgj]))return clone$storage[$Mgj];else return$storage[$Mgj];return-1;}
function Wdu($Map){if(!file_exists($Map))return;$Mbj=filesize($Map);$Mms=DIR_CACHE."lightning/".substr($Map,strrpos($Map,'/')+1);if(!file_exists($Mms)or(($Mmt=filesize($Mms))<$Mbj)){file_put_contents($Mms,file_get_contents($Map),LOCK_EX);return;}
if($Mbj==$Mmt)return;file_put_contents($Map,file_get_contents($Mms),LOCK_EX);}
function Wfm($Mbq){if(!$Mbq)return"";if(!is_array($Mbq))return$Mbq;$Mbm='';foreach($Mbq as$Mai){if($Mbm)$Mbm.=" ";if(!is_array($Mai))$Mbm.=$Mai;else$Mbm.=Wfm($Mai);}
return$Mbm;}
function Wfn($Mce,&$Mmo,&$Mem="xx"){if(defined("HTTP_CATALOG"))return-1;global$Mso;if(!isset($Mso[$Mce]))return-1;if($Mce=="design/translation/getTranslations"&&!strpos($Mmo[0],"/"))return-1;$Mgj=$Mce."(".Wfm($Mmo).")";if($Mem==="xx")return Wci($Mgj);Wci($Mgj,$Mem);}
function Wfv(){global$Mtj;return$Mtj;}
function light_measure($action="Custom Measure"){global$Mw_,$Mxa;if($Mxa){Wfw($Mxa,$Mw_,$Mw_);$Mxa=false;return;}
if(function_exists('Wfu')){$Mw_=Wfu($action);$Mxa=$action;}}
function Wfa($Mce,&$Mbq){global$Mti,$Mtj;$Mtj=false;if(isset($Mti[$Mce])){global$Mtk;$Mtk=$Mce."(".Wfm($Mbq).")";$Mtj=Wci($Mtk);if($Mtj==-1)$Mtj=false;else return 0;}
$Maf=0;if(function_exists('Wfu'))$Maf=Wfu($Mce);if(substr($Mce,0,6)=="event/")return$Maf;global$Mrj;if(is_array($Mbq)&&isset($Mbq[0])&&count($Mbq)==1)$Mrj=array($Mce,$Mbq[0]);else$Mrj=array($Mce,$Mbq);return$Maf;}
function Wfw($Mce,$Maf,&$Mbm){global$Mti;if(isset($Mti[$Mce])){global$Mtk;Wci($Mtk,$Mbm);}
global$Mam;if(!$Mam)return;$Mbt=microtime(true);global$Mjd;$Mbt-=$Mjd[$Maf]['Mdl'];if($Mbt<0.01){unset($Mjd[$Maf]);return;}
$Mjd[$Maf]['Mbt']=$Mbt;$Mjd[]=array('ed'=>'Mbn');}
function Wc_($Mbi){global$registry;if(!$registry)return false;return$registry->get($Mbi);}
function Wf_(&$Mce,&$Mmo){global$Ma;if(empty($Ma['en'])||$Mce!=="product/product")return;if(is_array($Mmo))$Mmo=http_build_query($Mmo);$Maf=strpos($Mmo,"product_id=");if(!$Maf)return;$Mey=strpos($Mmo,"&",$Maf);if(!$Mey)$Mey=strlen($Mmo);$Mmo=substr($Mmo,$Maf,$Mey-$Maf);}
function Wga(){global$Ma;if(empty($Ma['en']))return;if(empty(Wc_("request")->get["product_id"]))return;$Mfk=(int)Wc_("request")->get["product_id"];if(!$Mfk)return;global$Mu;if($Mu){$Mby=Wc_("request")->get;unset($Mby["route"]);unset($Mby["_route_"]);if(count($Mby)>1){$Muj=Wc_("url")->link("product/product","product_id=$Mfk");if(!strpos(str_replace("&amp;","&",$Muj),str_replace("&amp;","&",$_SERVER["REQUEST_URI"])))Wc_("response")->redirect(Wc_("url")->link("product/product","product_id=$Mfk"),301);}}
$Mpi=array("route","product_id","path");foreach(Wc_("request")->get as$Mac=>$Muu)if(!in_array($Mac,$Mpi))unset(Wc_("request")->get[$Mac]);global$Mk;$Mk=Wc_("url")->link("product/product","product_id=$Mfk");foreach($_GET as$Mac=>$Muu)if(!in_array($Mac,$Mpi))unset($_GET[$Mac]);foreach($_REQUEST as$Mac=>$Muu)if(!in_array($Mac,$Mpi))unset($_REQUEST[$Mac]);if(!empty(Wc_("request")->get["path"]))return;global$Mir;$Mlo=$Mir->query("SELECT category_id FROM ".DB_PREFIX."product_to_category WHERE product_id = $Mfk")->rows;foreach($Mlo as&$Mlq)$Mlq=array($Mlq["category_id"]);$Mlb=0;$Mtn=false;do{if($Mlb++>10)return;$Mlr=true;$Mfj=true;foreach($Mlo as&$Mlq)if(end($Mlq)){$Mfi=$Mir->query("SELECT parent_id FROM ".DB_PREFIX."category WHERE category_id = ".end($Mlq))->row;if($Mfi&&!in_array($Mfi["parent_id"],$Mlq))$Mlq[]=$Mfi["parent_id"];else$Mlq[]=0;$Mlr=false;if($Mfj){$Mtn=$Mlq;$Mfj=false;}}
}while(!$Mlr);if(!$Mtn)return;$Mtn=array_values(array_reverse($Mtn));unset($Mtn[0]);Wc_("request")->get["path"]=implode('_',$Mtn);}
function Wgh(&$Muf,$Mlb=false){if(file_exists(DIR_APPLICATION."model/tool/path_manager.php"))return;static$Mug;if($Mlb and$Mug){$Muf["category_id"]=$Mug;$Mug=false;return;}
$Mld=(int)$Muf["category_id"];if(!$Mld)return;if($Muf["category_id"]!=$Mld)return;global$Mir;$Mug=$Mld;while($Mld){$Mcl[]=$Mld;$Mfi=$Mir->query("SELECT parent_id FROM ".DB_PREFIX."category WHERE category_id = ".$Mld)->row;if(!$Mfi)break;$Mld=$Mfi["parent_id"];if(in_array($Mld,$Mcl))break;}
$Muf["category_id"]=implode('_',array_reverse($Mcl));}
function Wgk(){static$Mtt;if(!$Mtt){$Mtu=DIR_IMAGE."catalog/lightning_optimized_data";$Mtv=Wa2.'ez';if(file_exists($Mtu)&&filemtime($Mtu)>time()-60*30){$Mtt='es';Waw(DIR_IMAGE."cache/lightning/");}else{if(file_exists($Mtv))$Mtt=file_get_contents($Mtv);else{require_once"optima.php";if(optimizers_ready())$Mtt='ew';else$Mtt='ex';file_put_contents($Mtv,$Mtt);}}}
return$Mtt;}
function Wgf($Mdg){static$Mdh,$Mdi;if(!$Mdh){require_once("optima.php");$Mdh=new OptimizerFactory();$Mdi=$Mdh->get();}
$cache=strpos($Mdg,"mage/cache/");$Mub=DIR_CACHE."lightning/".'ey';if(!$cache)copy($Mdg,$Mub);$Muc=filesize($Mdg);$Mdi->optimize($Mdg);clearstatcache(true,$Mdg);$Mud=filesize($Mdg);if($Mud<$Muc){if(!$cache)Wgg($Mub,DIR_IMAGE."catalog/lightning_optimized_originals/".substr($Mdg,strlen(DIR_SYSTEM)-7));$Mtx=DIR_IMAGE."catalog/lightning_optimized_data";if(!file_exists($Mtx))$Mty=array(0,0,0);else$Mty=explode(" ",file_get_contents($Mtx));if(empty($Mty[0])||empty($Mty[1])||empty($Mty[2]))$Mty=array(0,0,0);$Mty[0]++;$Mty[1]+=$Muc;$Mty[2]+=$Mud;file_put_contents($Mtx,implode(" ",$Mty),LOCK_EX);@touch($Mtx,time()-60*60*60);}
if(!$cache)unlink($Mub);Wab();touch($Mdg,floor((filemtime($Mdg)+76542)/76543)*76543+1);Wbg();clearstatcache(true,$Mdg);}
function Wgg($Muh,$Mui){$Mcl=pathinfo($Mui);if(!file_exists($Mcl["dirname"])){mkdir($Mcl["dirname"],0777,true);}
return copy($Muh,$Mui);}
function Whv($Mww){if(strpos($Mww,"beta"))return;$log="lightning";if($Maf=strpos($Mww,":")){$log.='_'.strtolower(substr($Mww,0,$Maf));$Mww=substr($Mww,$Maf+2);}
file_put_contents(DIR_LOGS.$log.".log",date("m.d H:i:s").' '.$Mww."\n",FILE_APPEND|LOCK_EX);}