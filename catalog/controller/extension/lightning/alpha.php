<?php if(!empty($Mz)){if($Mz=="n"){require_once"omega.php";if(!empty($_GET["op"])){$Me=$_GET["op"];header("Access-Control-Allow-Origin: *");if($Me=="hide")Wa($_GET["id"]);elseif($Me=="show")Wa($_GET["id"],true);elseif($Me=="delete")Wb($_GET["id"],false);elseif($Me=="test"){$Mlm=Wc($_GET["id"]);if($Mlm===2)echo"UPDATE";elseif($Mlm)echo"OK";}
}else Wd();exit;}
if($Mz=="io"){require"optima.php";OptimizeTest();}
if($Mu){exit;}
if(isset($_SERVER["HTTPS"])&&(($_SERVER["HTTPS"]=="on")||($_SERVER["HTTPS"]=="1"))&&substr(HTTP_SERVER,0,5)!="https"){$_SERVER["HTTPS"]="";$_SERVER["SERVER_PORT"]=80;}
if(!empty($_SERVER["HTTP_USER_AGENT"])&&$_SERVER["HTTP_USER_AGENT"]=="Lightning CRON Job"){if(file_exists(Wa.'aq')){$Mbe=unserialize(file_get_contents(Wa.'aq'));$_SERVER["HTTPS"]=$Mbe['as'];$_SERVER["HTTP_HOST"]=$Mbe['at'];$_SERVER["REQUEST_URI"]=$Mbe['au'];$_SERVER["HTTP_USER_AGENT"]=$Mbe['ax'];$_SERVER["SERVER_NAME"]=$Mbe['at'];$_SERVER["REQUEST_METHOD"]="GET";$_GET=$Mbe['av'];$Mo=$Mbe['aw'];if($Mbe['av']["li_op"]=="gens"){$Mbe['av']["li_op"]="gen";Whr(Wa.'aq',$Mbe);}
$Mz=$_GET["li_op"];}else{$Mpd=substr(HTTP_SERVER,strpos(HTTP_SERVER,"//")+2);if(strpos(HTTP_SERVER,"ttps:"))$_SERVER["HTTPS"]="on";$_SERVER["HTTP_HOST"]=substr($Mpd,0,strpos($Mpd,'/'));$_SERVER["REQUEST_URI"]=substr($Mpd,strpos($Mpd,'/'));$_SERVER["SERVER_NAME"]=$_SERVER["HTTP_HOST"];$Mes=array("Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36","Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1");$_SERVER["HTTP_USER_AGENT"]=$Mes[array_rand($Mes)];if(!file_exists(Wa))$_SERVER["HTTP_USER_AGENT"]=$Mes[0];$Mz="gen";$Mo=array("dummy"=>1);if(!file_exists(Wa.'bb'))$Mz.="s";$Mbe=array();if(isset($_SERVER["HTTPS"]))$Mbe['as']=$_SERVER["HTTPS"];else$Mbe['as']=false;$Mbe['at']=$_SERVER["HTTP_HOST"];$Mbe['au']=$_SERVER["REQUEST_URI"];$Mbe['av']=$_GET;$Mbe['av']["li_op"]="gen";$Mbe['aw']=$Mo;$Mbe['ax']=$_SERVER["HTTP_USER_AGENT"];Whr(Wa.'aq',$Mbe);}
$_SERVER["QUERY_STRING"]="";$Mfi=str_replace("?","&",$_SERVER["REQUEST_URI"]);if($Maf=strpos($Mfi,"&"))$_SERVER["QUERY_STRING"]=substr($Mfi,$Maf+1);$_SERVER["argv"][0]=$_SERVER["QUERY_STRING"];$_REQUEST=array_merge($_GET,$Mo);$Md=($_SERVER["HTTPS"]=="on")||($_SERVER["HTTPS"]=="1");if($Md){$_SERVER["HTTPS"]="on";$_SERVER["SERVER_PORT"]="443";}else{$_SERVER["HTTPS"]="";$_SERVER["SERVER_PORT"]="80";}}}
function W_($Mbi){global$registry;if(!$registry)return false;return$registry->get($Mbi);}
function Waa(){if(!empty($_COOKIE['az'])or Wdr)W_("config")->set("config_maintenance",0);global$Mz,$Mt,$Mbd;if($Mbd==2){$Mbd=false;Wab();}
if($Mpe=W_("session"))return$Mpe->data;if($Mz!="gen"&&$Mz!="gens"&&!$Mbd)return array();if(session_id())session_write_close();return$Mt;}
function Wac($Mbj){$Mbk=0;while($Mbj>=1024){$Mbj/=1024;$Mbk++;}
if($Mbj<10)$Mbj=round($Mbj,2);else$Mbj=round($Mbj);$Mbl=array("bytes","Kb","Mb","Gb","Tb");$Mbm=$Mbj." ".$Mbl[$Mbk];return$Mbm;}
function Wq($Mbo=false){global$Mf,$Mz,$Maq;if(!Wdr){header("HTTP/1.1 200 OK",true,200);header("Status: 200");if(function_exists("header_remove")){header_remove("Content-Encoding");header_remove("Set-Cookie");header_remove("Location");}else{header("Content-Encoding: none");header("Set-Cookie:");}
if(function_exists("http_response_code"))http_response_code(200);}else if(!$Mbo)if(!file_exists(Wa.'ar'))Wfy(Wa.'aq');if(!$Mf){if($Mbo and!Wdr){if($Mz=="gen"&&!$Maq['id']){$Mbp=Wad(DIR_CACHE."lightning/alpha");$Mbq["pages"]=$Mbp[0];$Mbq["psize"]=Wac($Mbp[1]);global$Mtf;if(!$Mtf){$Mbq["width"]=round(($Maq['Mha']-count($Maq['Mbz']))/$Maq['Mha']*200);$Mbq["width"].="px";$Mbq["percent"]=round(($Maq['Mha']-count($Maq['Mbz']))/$Maq['Mha']*100);if(!empty($Maq["subgen"])){$Mbq["width"]="200px";$Mbq["percent"]=100;}}
$Mg=json_encode($Mbq);echo$Mg;}else echo"OK";}else{if(!Wdr){if(!$Maq['id'])Wfy(Wa.'ar');echo json_encode(false);}}
global$light_ob;$light_ob=false;if(Wdr)echo"OXX";exit;}
global$Mte;if($Mbo and!$Mte and!$Maq['id']and!file_exists(Wa.'ar')){file_put_contents(Wa.'ar','1',LOCK_EX);Wr("gen");}
if(!$Mbo and$Maq and!$Maq['id'])Wfy(Wa.'ar');$Mf["gen"]=$Mbo;$Mg=json_encode($Mf);if(!Wdr)echo($Mg);else echo"OXX";global$light_ob;$light_ob=false;exit;}
function Wn($Mtm=false){if(!file_exists(Wa.'bb')){Wq();}
global$Mtd,$Maq,$M_,$Mk,$My,$Ml,$Mbr,$Ma,$Mtf,$Mte;$Mtf='';while(file_exists(Wa.'bb'.$Mtf))$Mtf++;$Mtf--;if(!$Mtf)$Mtf='';$Maq=unserialize(file_get_contents(Wa.'bb'.$Mtf));if(!Wdr&&$Maq['id']!=$M_){$Mbq["gen"]=false;$Mbq["md"]=array();global$Mz;if($Mz=="gens")die(json_encode($Mbq));else die("false");}
require_once"beta.php";if(!$Mbr)$Mbr=$Mk;$Mk=false;foreach($Maq['Mbz']as$Mbs=>$Mbe){$Mbe=str_replace("&amp;","&",$Mbe);if($Mte and!$Maq['id']and!$Mtf){$Mbv=round(($Maq['Mha']-count($Maq['Mbz']))/$Maq['Mha']*100);file_put_contents(Wa.'ar',$Mbv,LOCK_EX);}
unset($Maq['Mbz'][$Mbs]);$Mbt=Wae("alpha",Wm($Mbe))-Wy(Wm($Mbe));$Mk=$Mbe;if($Mbt<=$Ml)break;if(!empty($Maq['ek'])&&$Mtf<$Mtd){$Mao=md5(Wm($Mk));$Mcd=DIR_CACHE."lightning/gamma"."/".substr($Mao,0,2)."/c".substr($Mao,2);if(Waf($Mcd,$Mk)){Whr(Wa.'bb'.$Mtf,$Maq);$Mer=$Maq;if(!Wn(true))return;$Maq=$Mer;}}
$Mk=false;if($Maq['Mca']>$Mbt)$Maq['Mca']=$Mbt;}
$Mbu=time()-Wy(Wm($Mk));if($Maq['Mca']>$Mbu)$Maq['Mca']=$Mbu;if(!$Maq['Mbz']and$Maq['Map']and file_exists($Maq['Map'])){if($Mtf<$Mtd and empty($Maq['ek'])){$Maq['ek']=1;$Maq['Mbz']=unserialize(file_get_contents($Maq['Map']));if($Maq['Mbz'])unset($Maq['Mbz'][0]);$Maq['Mha']=count($Maq['Mbz']);Wfy(Wa.'ar');global$Mte;$Mte=false;}}
if(!$Maq['Mbz']){if(!$Mtf and$Maq['Map'])touch($Maq['Map'],$Maq['Mca']);Wfy(Wa.'bb'.$Mtf);}
if(!$Mk){if($Mtf){if($Mtm){$Mtf--;if(!$Mtf)$Mtf='';return true;}
Wn();return;}
if(!$Maq['id'])Wfy(Wa.'ar');if(Wp($Mbr)){Wn();return;}
Wq();}
if($Maq['Mbz']or!$Mtf)Whr(Wa.'bb'.$Mtf,$Maq);global$Mah,$Mh;header("X-OpenCart-Lightning-Gen: $Mk");$Mah=Wm($Mk);$Mbw=str_replace("//",'',$Mk);$Maf=strpos($Mbw,'/');$_SERVER["REQUEST_URI"]=substr($Mbw,$Maf);$Mbe=str_replace('?','&',str_replace(Wgx($Mh),"",Wgx($Mk)));$Mbx=explode('&',$Mbe);$_GET=array();if(!$Mbx)$Mbx[0]='';if($Mbx[0]=="index.php")$Mbx[0]='';if($Mbx[0])$_GET["_route_"]=$Mbx[0];unset($Mbx[0]);$_SERVER["HTTP_X_REQUESTED_WITH"]="Get";foreach($Mbx as$Mby){if(!$Mby)continue;$Mby=explode('=',$Mby);if(!$Mby[1])$Mby[1]='';$_GET[$Mby[0]]=$Mby[1];}
foreach($_GET as$Mgj=>$Muu)$_REQUEST[$Mgj]=$Muu;global$Md;$Md=substr($Mk,0,5)=="https";if($Md){$_SERVER["HTTPS"]="on";$_SERVER["SERVER_PORT"]="443";}else{$_SERVER["HTTPS"]="";$_SERVER["SERVER_PORT"]="80";}
Wg();}
function Wgx($Mbe){$Mbe=str_replace("http:",'',$Mbe);$Mbe=str_replace("https:",'',$Mbe);return$Mbe;}
function Waf($Map,$Mtg=false){global$Maq,$M_,$Ml,$Mm,$Mtf,$Mtd;if($Mtg&&$Mtf>=$Mtd)return;require_once"beta.php";if($Mtg)$Mth=$Maq;$Maq['Map']=$Map;$Maq['id']=$M_;$Maq['Mca']=time();$Maq['Mig']=$Mtg;if(strpos($Map,"//")!==false){$Maq['Map']=false;$Mbz=array($Map);}else if(file_exists($Map))$Mbz=unserialize(file_get_contents($Map));if(empty($Mbz))$Mbz=array();$Mb_=array();$Mca=time();foreach($Mbz as$Mcb=>$Mbe){$Mbt=Wae("alpha",Wm($Mbe));if($Mbt<$Mm){$Mb_[]=$Mbe;unset($Mbz[$Mcb]);continue;}
if($Mbe==$Map){Wag("alpha",Wm($Mbe));continue;}
$Mbt=$Mbt-Wy(Wm($Mbe));if($Mtg&&$Mbt>$Ml){$Mto=Wae("gamma",Wm($Mbe));if($Mto>$Ml){unset($Mbz[$Mcb]);}}
if($Mbt<$Mca)$Mca=$Mbt;}
if(!Wdr){shuffle($Mb_);shuffle($Mbz);}
$Mbz=array_merge($Mb_,$Mbz);if(!$Mbz){if($Maq['Map']&&is_file($Map))touch($Map,$Mca);if($Mtg){$Maq=$Mth;return false;}
Wfy(Wa.'bb'.$Mtf);return false;}
$Maq['Mbz']=$Mbz;$Maq['Mha']=count($Mbz);if($Mtg)$Mtf++;Whr(Wa.'bb'.$Mtf,$Maq);if($Mtg){$Maq=$Mth;$Mtf--;if(!$Mtf)$Mtf="";}else{$Mqu=1;clearstatcache();while(file_exists(Wa.'bb'.$Mqu)){unlink(Wa.'bb'.$Mqu);$Mqu++;}}
return true;}
function Wp($Mbr=false){if(!lc('n'))return false;global$Mh,$Mk,$Ml,$Mm,$Mte;if($Mte)return false;if(!$Mbr)$Mbr=$Mk;if(!$Mbr)$Mbr=$Mh;if(file_exists(Wa.'bb')&&filemtime(Wa.'bb')>(time()-180)){$Maq=unserialize(file_get_contents(Wa.'bb'));if($Mte and!$Maq['id']and$Maq['Mbz'])return false;}
$Mao=md5(Wm($Mh));$Mcc=DIR_CACHE."lightning/gamma"."/".substr($Mao,0,2)."/c".substr($Mao,2);clearstatcache();$Mao=md5(Wm($Mbr));$Mcd=DIR_CACHE."lightning/gamma"."/".substr($Mao,0,2)."/c".substr($Mao,2);if(!file_exists($Mcd)and Waf($Mbr)){return true;}
if(file_exists($Mcc)&&filemtime($Mcc)<=$Mm&&Waf($Mcc))return true;if(file_exists($Mcd)&&filemtime($Mcd)<=$Mm&&Waf($Mcd))return true;if(file_exists($Mcc)&&filemtime($Mcc)<=$Ml&&Waf($Mcc))return true;if(file_exists($Mcd)&&filemtime($Mcd)<=$Ml&&Waf($Mcd))return true;Wfy(Wa.'bb');Wfy(Wa.'ar');return false;}
function Wah($Mce,$Mbe){if(!lc('Mnt')or!lc('n'))return;if(strpos($Mbe,'@')or strpos($Mbe,'#'))return;static$Mcg;if(!isset($Mcg[$Mce]))$Mcg[$Mce]=Wai($Mce);if($Mcg[$Mce])return;if(Wai())return;if(strpos($Mbe,"filter="))return;global$Mch;$Mbe=str_replace("&amp;","&",$Mbe);$Mbe=str_replace("page={page}","",$Mbe);$Mbe=preg_replace("/page=[0-9]+/","",$Mbe);$Mbe=preg_replace("/page-[0-9]+/","",$Mbe);if(!strpos(str_replace("//",'',$Mbe),"/"))return;$Mch[$Mbe]=1;}
function Wew(&$Mem){if(!W_("journal2"))return;$Mhr=W_("journal2")->settings->get("product_dummy_image");$Mem=str_replace("src=\"" . $Mhr . "\""." data-src","src",$Mem);$Mem=str_replace("src=\"" . $Mhr . "\""."  data-src","src",$Mem);}
function Wal(){global$Mcq;$request=W_("request");if(!empty($request->get["route"]))$Mcq=$request->get["route"];global$Mz,$light_ob,$Mab;if($light_ob)W_("config")->set("config_compression",0);if($Mz=="gens"&&$Mab){global$Mf,$registry,$Mab;$Mab=gzuncompress(hex2bin($Mab));$Mcr=explode("~|~",$Mab);if(!empty($request->get["route"]))$Mce=$request->get["route"];else$Mce="common/home";if(VERSION>"2.0"){foreach($Mcr as$Mcb=>$Mcs){$Mmo=array();if($Maf=strpos($Mcs,"=")){$Mmo=unserialize(substr($Mcs,$Maf+1));$Mcs=substr($Mcs,0,$Maf);}
$Mem=W_("load")->controller($Mcs,$Mmo);Wew($Mem);$Mf["md"]["#liaj".($Mcb+1)]=$Mem;}
}else{W_("load")->model("setting/extension");class ControllerFake extends Controller {}
$Mct=new ControllerFake($registry);foreach($Mcr as$Mcb=>$Mcs){$Mmo=array();if($Maf=strpos($Mcs,"=")){$Mmo=unserialize(substr($Mcs,$Maf+1));$Mcs=substr($Mcs,0,$Maf);}
$Mem=$Mct->getChildHtml($Mcs,$Mmo);Wew($Mem);$Mf["md"]["#liaj".($Mcb+1)]=$Mem;}}
$Mg=json_encode($Mf);echo($Mg);global$light_ob;$light_ob=false;exit;}}
function Wej(&$Mul){$Mdt=preg_replace('#/\*.*?\*/#s','',$Mul);$Mum=false;$Maf=0;$Mgw=0;$Mpk=0;while($Maf<strlen($Mdt)){$Med=strpos($Mdt,'{',$Maf);$Mej=strpos($Mdt,'}',$Maf);if($Med!==false&&$Med<$Mej){$Mgw++;$Maf=$Med;}elseif($Mej!==false){if(!$Mgw){$Mdt=substr($Mdt,0,$Mpk).substr($Mdt,$Mej+1);$Mum=true;$Maf=$Mpk-1;}else{$Mgw--;$Maf=$Mej;$Mpk=$Mej+1;}}
$Maf++;}
while($Mgw){$Mum=true;$Mdt.='}';$Mgw--;}
if($Mum)$Mul=$Mdt;}
function Wao($Mcy,$Mcz=true,$Mdo=false){global$Mh,$Md;$Mcy=str_replace("&amp;","&",$Mcy);$Mc_=Wak("//",'',$Mh);if(strpos($Mcy,"//")===0){if($Md)$Mcy="https:".$Mcy;else$Mcy="http:".$Mcy;}
if(strpos($Mcy,"://")===false){if($Mcy[0]=="/")$Mcy=substr($Mh,0,strpos($Mh,"/",9)).$Mcy;else$Mcy=$Mh.$Mcy;if($Md)$Mcy=str_replace("http:","https:",$Mcy);}
$Mda=abs(crc32(Wem($Mcy)));if($Mdo)$Mda='d'.$Mda;if(!$Mcz)$Mda=lc('bf').$Mda;$Mdb=DIR_IMAGE."cache/lightning/".$Mda;if($Mcz)$Mdb.=".x_js";else$Mdb.=".x_css";if($Mcz===-1)$Mdb.="x";$Mdc=Wak("//",'',$Mcy);$Mdd=false;if(strpos($Mdc,$Mc_)===0){$Mdc=Wak($Mc_,'',$Mdc);if(strpos($Mdc,'?'))$Mdc=Wak('','?',$Mdc);$Mdc=str_replace('\\','/',$Mdc);$Mde=substr(DIR_SYSTEM,0,-7).$Mdc;if((substr($Mdc,-3)==".js"||substr($Mdc,-4)==".css")&&file_exists($Mde)){if(file_exists($Mdb)&&filemtime($Mdb)>filemtime($Mde))return file_get_contents($Mdb);$Mdd=file_get_contents($Mde);}}
if(!$Mdd){$Mdb=str_replace(".x_","e.",$Mdb);if(light_device())$Mdb=str_replace("e.","e_".light_device().".",$Mdb);if(file_exists($Mdb)&&filemtime($Mdb)>time()-60*60*4)return file_get_contents($Mdb);if(light_device())$Mdd=Wap($Mcy,false,$_SERVER["HTTP_USER_AGENT"]);else$Mdd=Wap($Mcy);if(!$Mdd){sleep(1);if(light_device())$Mdd=Wap($Mcy,false,$_SERVER["HTTP_USER_AGENT"]);else$Mdd=Wap($Mcy);}
global$Mdf;if($Mdf<200||$Mdf>399){require_once"special.php";Wb("missing_resource",true,array("url"=>true,"resource"=>$Mcy));$Mdd="";}}
if(!$Mdd)return false;if(substr($Mdd,0,3)==pack("CCC",0xef,0xbb,0xbf))$Mdd=substr($Mdd,3);if($Mcz)$Mdd="try {\n".$Mdd."\n} catch (e) { console.log('Error at ".$Mcy.": '+e.message); }";if($Mcz===-1)return$Mdd;require_once"delta.php";if($Mcz)light_minify_js($Mdd);else{Wej($Mdd);light_minify_css($Mdd,$Mcy);$Maf=0;while(($Maf=strpos($Mdd,"@import",$Maf))!==false){$Mei=$Maf;$Mey=strpos($Mdd,';',$Mei);$Maf=$Mey;$Mol=substr($Mdd,$Mei,$Mey-$Mei);$Mbe=Wj("url(",")",$Mol);if(!$Mbe)$Mbe=Wj('"','"',$Mol);else{if($Mbe[0]=='"'||$Mbe[0]=="'")$Mbe=substr($Mbe,1,-1);}
if(!$Mbe)$Mbe=Wj("'","'",$Mol);if(!$Mbe)continue;$Mqy=Wao($Mbe,false,$Mdo);if(!$Mqy)continue;if($Mdo)$Mqy="\n\n/* ------------------\n  Imported CSS: ".$Mbe."\n  ------------------*/\n\n".$Mqy;else$Mqy="\n/* */\n".$Mqy;$Mdd=substr($Mdd,0,$Mei).$Mqy.substr($Mdd,$Mey+1);}}
file_put_contents($Mdb,$Mdd,LOCK_EX);return$Mdd;}
function War(&$Mdj,&$Mdk){$Mdl=strpos($Mdk,$Mdj);if($Mdl==false)return;$Mbn=$Mdl+strlen($Mdj);while($Mdl&&ctype_space($Mdk[$Mdl-1]))$Mdl--;$Mpl=strlen($Mdk);while($Mbn+1<$Mpl&&ctype_space($Mdk[$Mbn+1]))$Mbn++;$Mdk=substr($Mdk,0,$Mdl).substr($Mdk,$Mbn);}
function Wem($Mcy){if(strpos($Mcy,'?'))$Mcy=Wak('','?',$Mcy).'?'.preg_replace('/\d/','',Wak('?','',$Mcy));return$Mcy;}
function Wen($Mds){foreach($Mds as&$Mdk)$Mdk=Wem($Mdk);$Mda=abs(crc32(implode(';',$Mds)));return$Mda;}
function Wey($Mes,$Mce=false){if(empty($GLOBALS['Ma'][$Mes]))return false;$Mri=explode(" ",$GLOBALS['Ma'][$Mes]);if(!$Mce){if(!empty(W_("request")->get["route"]))$Mce=W_("request")->get["route"];else$Mce="common/home";}
global$Mk;$Met=false;foreach($Mri as$Mnx){if($Mnx[0]=='"'){if(stripos($Mk,substr($Mnx,1,-1))!==false){$Met=true;break;}
}else if(stripos($Mce,$Mnx)!==false){$Met=true;break;}}
return$Met;}
function Wfr($Mds){global$Msr;$Mda=Wen($Mds);$Mdu=DIR_IMAGE."cache/lightning/".$Mda.$Msr.".js";if(!file_exists($Mdu)){$Mdo=lc('dh');Waw(DIR_IMAGE."cache/lightning");$Mdv="";$Mqu="";foreach($Mds as$Mcy){if($Mcy=='Mqu'){$Mdv.="\nvar arr=document.body.getElementsByTagName(\"script\");var sc=[];for(var x=0;x<arr.length;x++)if(arr[x].type==\"text/tier".$Mqu."\")sc.push(arr[x].text);for(var x=0;x<sc.length;x++)try{eval(sc[x])}catch(e){console.log('Error at HTML \"tier".$Mqu."\" script #'+(x+1)+': '+e.message)}";$Mqu++;continue;}
if($Mdo){$Mdd="\n\n/* ------------------\n   JS: ".$Mcy."\n// ------------------*/\n\n".Wao($Mcy,-1)."\nconsole.log('".$Mcy." passed');\n";}else$Mdd=Wao($Mcy);$Mdw=';';if($Mdv)$Mdw=substr($Mdv,-1);if($Mdw!=';'&&$Mdw!='}')$Mdv.=';';$Mdv.=$Mdd;}
$Mdw=';';if($Mdv)$Mdw=substr($Mdv,-1);if($Mdw!=';')$Mdv.=';';file_put_contents($Mdu,$Mdv,LOCK_EX);}
return"image/cache/lightning/".$Mda.$Msr.".js";}
function Wfs($Mds){if(!$Mds)return"";global$Mh,$Md;$Mmi=$Mh;if($Md)$Mmi=str_replace("http://","https://",$Mmi);return"<script src='".$Mmi.Wfr($Mds)."'></script>";}
function Was(&$Mg){if(!lc('ef')and!lc('eg'))return;global$Mam,$Mv;if($Mam or$Mv)return;Wu($Mg);if(!strpos($Mg,"<body"))return;global$Mh,$Md;$Mmi=$Mh;if($Md){$Mmi=str_replace("http://","https://",$Mmi);}
$Mdo=lc('dh');$Mqp="";$Mdp="";$Mdq="";if(lc('ef')and!Wey('dw')){$Mgw=lc('ef');$Mdq.="<script>window.performance.mark('Start JS Execution');</script>";$Mdr=Wcw("<script","</script>",$Mg);foreach($Mdr as$Mcz){$Mcz=substr($Mcz,strpos($Mcz,'>')+1);if(substr(trim($Mcz),0,4)=="<!--"){$Mne=substr(trim($Mcz),4);if(substr($Mne,-5)=="//-->")$Mne=substr($Mne,0,-5);elseif(substr($Mne,-3)=="-->")$Mne=substr($Mne,0,-3);$Mne=Wau("<!--*-->",'',$Mne);$Mg=str_replace($Mcz,$Mne,$Mg);}}
$Mdr=Wat("<script","</script>",Wau("<!--*-->",'',$Mg));$Mds=array();$Mst=Wcy("www.",'',Wcy("//",'',$Mmi));global$Msr;$Msr='g';if($Md){$Msr.="s";}
if(light_device()){$Msr.="_".light_device();}
if($Mdo){$Msr.="_debug";}
$Mdn=false;foreach($Mdr as$Mcz){$Mcj=substr($Mcz,0,strpos($Mcz,">"));$Mcj=str_replace("type=\"\"",'',str_replace("type=''",'',$Mcj));if(stripos($Mcj,"type=")&&!stripos($Mcj,"text/javascript")){continue;}
if(!lc('bz')or light_mobile())if(Wav('fh',$Mcz)){$Maf=strpos($Mcz,">");$Mcj=substr($Mcz,0,$Maf);$Mvl=substr($Mcz,$Maf);if(stripos($Mcj,"text/javascript"))$Mcj=str_ireplace("text/javascript","text/li_js",$Mcj);else$Mcj=str_ireplace("<script","<script type='text/li_js'",$Mcj);$Mvl=$Mcj.$Mvl;$Mg=str_replace($Mcz,$Mvl,$Mg);$Mdn=true;continue;}
if(Wav('eh',$Mcz))continue;$Mdt=Wak(">","</script>",$Mcz);if($Mdt){War($Mcz,$Mg);if($Mds){$Mdq.=Wfs($Mds);$Mds=array();}
$Mdq.=$Mcz;continue;}
$Mcy=Wak("src='","'",str_replace('"',"'",$Mcz));if(!$Mcy)$Mcy=Wak("src='","'",str_replace('"',"'",str_replace(' ','',$Mcz)));if(!$Mcy)continue;$Mcy=str_replace("&amp;","&",$Mcy);War($Mcz,$Mg);if(strpos($Mcy,'#')){$Mcy=Wak('','#',$Mcy);}
if($Mgw=="safe"){$Mdq.=$Mcz;continue;}
if($Mgw!="aggressive"&&strpos($Mcy,"//")!==false&&!strpos($Mcy,$Mst)){$Mdq.=Wfs($Mds);$Mds=array();$Mdq.=$Mcz;continue;}
$Mds[]=$Mcy;}
if($Mds)$Mdq.=Wfs($Mds);$Mvk=false;if(!lc('bz')or light_mobile()){$Mvj=Wat("<iframe","</iframe>",$Mg);foreach($Mvj as$Mcz){if(lc('fn')){$Mcy=Wak("src='","'",str_replace('"',"'",$Mcz));if(!$Mcy)$Mcy=Wak("src='","'",str_replace('"',"'",str_replace(' ','',$Mcz)));if(!$Mcy)continue;if(!Wav('fh',$Mcy))continue;}
$Mvl=str_replace(" src"," data-src",$Mcz);if($Mvl==$Mcz)continue;$Mg=str_replace($Mcz,$Mvl,$Mg);$Mvk=true;}}
if($Mdn or$Mvk){$Mdq.="<script>function li_defer(event){ $('body').unbind('mouseover touchmove', li_defer);";if($Mdn)$Mdq.="$('script[type=\"text/li_js\"]').each(function(){ $('body').append(this.outerHTML.replace('text/li_js', 'text/javascript'))});";if($Mvk)$Mdq.="$('iframe').each(function(){ $(this).attr('src', $(this).data('src'))});";$Mdq.="}";if(!lc('bz'))$Mdq.="if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent))";$Mdq.="$('body').bind('mouseover touchmove', li_defer);";if(!lc('bz'))$Mdq.="else li_defer();";$Mdq.="</script>";}}
if(lc('eg')){$Mpj=lc('eg')!="safe";$Mdx=Wat("<link",">",Wau("<!--*-->",'',Wau("<noscript*</noscript>",'',$Mg)));$Mds=array();foreach($Mdx as$Mbc){$Mrx=strtolower($Mbc);if(!strpos($Mrx,"stylesheet")){continue;}
if(strpos($Mrx,"media=")){$Mry=Wj("media='","'",str_replace('"',"'",$Mrx));if($Mry!="all"&&$Mry!="screen"){continue;}}
War($Mbc,$Mg);$Mcy=Wak("href='","'",str_replace('"',"'",$Mbc));if(!$Mcy){continue;}
$Mcy=str_replace("&amp;","&",$Mcy);if(strpos($Mcy,'#')){$Mcy=Wak('','#',$Mcy);}
$Mds[]=$Mcy;}
$Mda=Wen($Mds);$Mda.='c';$Mda=lc('bf').$Mda;if(light_device()){$Mda.="_".light_device();}
if($Md){$Mda.="s";}
if($Mdo){$Mda.="_debug";}
$Mdy=DIR_IMAGE."cache/lightning/".$Mda.".css";global$Mcq;$Msu=str_replace("/","",$Mcq);if(!$Msu)$Msu="commonhome";$Msu.=lc('bf');if(light_device()){$Msu.="_".light_device();}
global$Mw;$Msu.=$Mw;if($Md){$Msu.="s";}
$Msu.=".css";$Mdz=DIR_IMAGE."cache/lightning/".$Msu;$Md_="";if(!file_exists($Mdy)or$Mqp){Waw(DIR_IMAGE."cache/lightning");foreach($Mds as$Mcy){$Mdd=Wao($Mcy,false,$Mdo);if($Mdo){$Mdd="\n\n/* ------------------\n  CSS: ".$Mcy."\n  ------------------*/\n\n".$Mdd;}else$Mdd="\n/* */\n\n".$Mdd;$Md_.=$Mdd;}
file_put_contents($Mdy,$Md_,LOCK_EX);}
if($Mpj and(!file_exists($Mdz)or$Mqp)){$Mea=$Mg;$Mcj='';$Meb='';$Maf=0;$Mec=false;if(!$Md_)$Md_=file_get_contents($Mdy);while($Maf<strlen($Md_)){$Med=strpos($Md_,'{',$Maf);if($Med==false){break;}
$Mee=trim(substr($Md_,$Maf,$Med-$Maf));if($Mqp&&strpos($Mee,$Mqp)){echo"Found CSS: $Mee";}
if(strpos($Mee,"@media")!==false||(strpos($Mee,"@")!==false&&strpos($Mee,"keyframes"))){$Mpm=strpos($Md_,'{',$Med+1);$Mpn=strpos($Md_,'}',$Med+1);if($Mpn<$Mpm){$Maf=$Mpn+1;continue;}
$Meb=$Mee.'{';$Mef=true;$Maf=$Med+1;continue;}
$Meh=true;$Meg=explode(',',$Mee);foreach($Meg as$Mee){$Mee=trim($Mee);if(!$Mee){continue;}
$Mph=explode(' ',str_replace(array('+','.','#','>',"\n"),' ',$Mee));$Mpi=true;foreach($Mph as$Mei){if(strpos($Mei,':')!==false){$Mei=Wak('',':',$Mei);}
if(strpos($Mei,'[')!==false){$Mei=Wak('','[',$Mei);}
if(!$Mei){continue;}
if(!strpos($Mea,$Mei)){$Mpi=false;break;}}
if($Mpi){$Meh=false;break;}}
$Mej=strpos($Md_,'}',$Med);if(strpos($Mee,"@")!==false||strpos($Mee,"*")!==false){$Mec=false;$Meh=false;}
if(!$Meh and!$Mec){$Mbw=substr($Md_,$Maf,$Mej-$Maf+1);if($Meb){$Meb.=$Mbw;$Mef=false;}else{$Mcj.=$Mbw;}}
if($Mej<strlen($Md_)-1&&$Md_[$Mej+1]=='}'){if(!$Mec){if($Meb){if(!$Mef){$Mcj.=$Meb.'}';}
$Meb='';}else{$Mcj.='}';}}
$Mec=false;$Mej++;}
$Maf=$Mej+1;}
$Mcj=str_replace("@font-face{","\n@font-face{font-display:swap;",$Mcj);file_put_contents($Mdz,$Mcj,LOCK_EX);}
if($Mpj and file_exists($Mdz)){$Mdp="<link id='li_df' rel='preload' as='style' href='".$Mmi."image/cache/lightning/".$Mda.".css'/>".$Mdp;if(!lc('am')){$Mdp="<style>".file_get_contents($Mdz)."</style>".$Mdp;}else{$Mdp="<link rel='stylesheet' href='".$Mmi.str_replace(DIR_IMAGE,"image/",$Mdz)."'/>".$Mdp;}
$Mdq="<script>document.getElementById('li_df').rel = 'stylesheet';</script>".$Mdq;}else{$Mdp="<link rel='stylesheet' href='".$Mmi."image/cache/lightning/".$Mda.".css'/>".$Mdp;}}
$Maf=strpos($Mg,"</head>");$Mek=strpos($Mg,"<style");if($Mek&&$Mek<$Maf){$Maf=$Mek;$Mel=strpos($Mg,"<head");$Mek=strpos($Mg,"<!-",$Mel);if($Mek&&$Mek<$Maf)$Maf=$Mek;}
$Mqs=strpos($Mg,"<base");if($Mqs>$Maf)$Maf=strpos($Mg,">",$Mqs)+1;$Mg=substr($Mg,0,$Maf).$Mdp.substr($Mg,$Maf);$Maf=strrpos($Mg,"</body>");if(!$Maf)$Maf=strlen($Mg);$Mg=substr($Mg,0,$Maf).$Mdq.substr($Mg,$Maf);}
function Wax(){global$Mz,$Mcq,$Mq;if($Mz=="gen"||$Mz=="gens")if(function_exists("http_response_code")){$Mdt=http_response_code();if($Mdt>300&&$Mdt<400){if(function_exists("header_remove"))header_remove("Location");else header("Location:");http_response_code(200);}}
if(!empty(W_("request")->get["route"])){if(!strpos(W_("request")->get["route"],'/'))W_("request")->get["route"]=$Mcq;$Mcq=W_("request")->get["route"];}
global$light_ob,$Mk,$Mam;if(!$light_ob)return;$Mem='';Wec();while($Maf=ob_get_level()){$Mem=ob_get_contents().$Mem;@ob_end_clean();if(ob_get_level()==$Maf)break;}
Whw();if(!trim($Mem)){global$Maq;if($Maq){Wa_(false);Wq(!empty($Maq['Mbz']));}
echo$Mem;return;}
Wu($Mem);if(!rand(0,100))Wgc($Mem);if(strpos($Mem,"//cdn.ampproject.org/v0.js")or(function_exists("http_response_code")and http_response_code()===404)){Wz($Mem);return;}
global$Mh;if(!lc('fa')and stripos($Mh,"ttps://")and stripos(HTTP_SERVER,"ttps://")and strpos($Mem,"<body")){$Maf=0;while($Maf=strpos($Mem,"http:",$Maf)){$Maf++;if($Mem[$Maf-2]!="'"&&$Mem[$Maf-2]!='"')continue;if($Maf>7){$Mcj=substr($Mem,$Maf-7,5);if($Mcj=="href="&&substr($Mem,$Maf+4,strlen($Mh)-6)!=substr($Mh,6))continue;}
$Mcw=substr($Mem,$Maf+6,strpos($Mem,$Mem[$Maf-2],$Maf)-$Maf-6);if($Mhk=strpos($Mcw,'/'))$Mcw=substr($Mcw,0,$Mhk);if($Mcw=="schema.org")continue;$Mem=substr($Mem,0,$Maf+3)."s".substr($Mem,$Maf+3);}}
Wew($Mem);$Mem=str_replace("index.php?route=common/home'","'",$Mem);$Mem=str_replace("index.php?route=common/home".'"','"',$Mem);if(!empty($_COOKIE['az'])){Was($Mem);if(lc('j')){require_once"delta.php";light_minify_html($Mem);}
echo(base64_encode($Mem));return;}
global$Mw,$Mn;$Mec=!lc('Mnt');if($Mo_=error_get_last())if($Mo_["type"]==1||$Mo_["type"]==4)$Mec=true;if(!$Mec){if($light_ob==1)$Mec=true;elseif(!empty($Mq['_'])&&!lc('e'))$Mec=true;elseif(!empty($Mq["customer_id"])&&!lc('ab'))$Mec=true;elseif(!empty($Mq["wishlist"])&&!lc('ac'))$Mec=true;elseif(!empty($Mq['_'])||!empty($Mq["customer_id"])||!empty($Mq["wishlist"])){if(We($Mw)){if(!empty($Mq['_'])and(!$Mn['ah']||$Mq['_']===true))$Mec=true;elseif(!empty($Mq["customer_id"])&&!$Mn['z'])$Mec=true;}else$Mec=true;}}
if($Mec||Wai()||$Mam){if(lc('h')){$Men='';if(lc('ch'))$Men="<script>var cookie_policy_link = \"".lc('ch')."\";</script>";$Men.='<script async src="//devs.mx/cookie/bar2.js"></script>';$Maf=strrpos($Mem,"</body>");if($Maf){if(strpos($Mem,"jquery",$Maf))$Maf=strpos($Mem,"</html>");if($Maf)$Mem=substr($Mem,0,$Maf).$Men.substr($Mem,$Maf);}}
Was($Mem);global$Maq;if($Maq)Wq(!empty($Maq['Mbz']));if(lc('bi')){require_once"delta.php";light_minify_html($Mem);}
if(lc('f'))Way($Mem);Waz();if(headers_sent())Wu($Mem);Wz($Mem);return;}
Wu($Mem);$Mbe=$Mk;if(strpos($Mbe,'?')or strpos($Mbe,'&'))$Mbe.='&';else$Mbe.='?';$Mbe.="li_op=gen";global$Meo;if($Meo)$Meo=bin2hex(gzcompress(implode("~|~",$Meo),9));global$Mc;$Men="";if(!$Mc or$Meo)$Men.="var li_cd=Math.floor(Math.random()*999999)+1;";$Mpv="";if(defined("JOURNAL_INSTALLED")&&empty(W_("request")->get["product_id"]))$Mpv.="if(!!$.prototype.die)$('.pagination .links a').on('click',function(){if(window.location.href.indexOf('#')>-1)return true;window.location.href=$(this).attr('href');return false;});";if(!$Mz)Wr();if($Mc){if($Meo)$Mpv.="if(!/bot|googlebot|crawler|spider|robot|crawling/i.test(navigator.userAgent))$.get('".$Mbe."s&md=".$Meo."&cd='+li_cd,false,function(data){ $.each(data['md'],function(m,html){ $(m).replaceWith(html);});},'json');";}elseif(lc('n')or$Meo){$Mpv.="if(!/bot|googlebot|crawler|spider|robot|crawling/i.test(navigator.userAgent))$.get('".$Mbe."s&md=".$Meo."&cd='+li_cd,false,function(data){ $.each(data['md'],function(m,html){ $(m).replaceWith(html);});if(data['gen'])li_gen();},'json');";$Men.="function li_gen(){ $.get('".$Mbe."&cd='+li_cd+'&rd='+Date.now(),false,function(data){if(data.trim()=='OK')li_gen();});}";}
if($Mpv)$Men.="$(window).load(function(){".$Mpv."});";if(lc('ch'))$Men.="var cookie_policy_link = \"".lc('ch')."\";";if($Men)$Men="<script>".$Men."</script>";if(lc('h'))$Men.='<script src="//devs.mx/cookie/bar2.js"></script>';$Maf=strrpos($Mem,"</body>");if($Maf){if(strpos($Mem,"jquery",$Maf))$Maf=strpos($Mem,"</html>");if($Maf)$Mem=substr($Mem,0,$Maf).$Men.substr($Mem,$Maf);}
Was($Mem);if(lc('j')){require_once"delta.php";light_minify_html($Mem);}
Whn($Mem);$Mep=Wa_($Mem);global$Maq;if($Maq){Waz();global$Mtf;Wq(!empty($Maq['Mbz'])or$Mtf);}
if(!$Mep)Wz($Mem,true);else Wz($Mep,true);}
function Wec(){ini_set("display_errors","Off");ini_set("log_errors","Off");global$Mbd,$Mkt,$Mku;if($Mbd)return;$config=W_("config");if(!$config)return;$Mbd=true;$Mkt=$config->get("config_error_display");$Mku=$config->get("config_error_log");$config->set("config_error_display",0);$config->set("config_error_log",0);}
function Whw(){global$Mbd,$Mkt,$Mku;if(!$Mbd)return;$config=Wc_("config");if(!$config)return;$Mbd=false;$config->set("config_error_display",$Mkt);$config->set("config_error_log",$Mku);}
function Wai($Mce=false){if(!$Mce){if(!empty(W_("request")->get["route"]))$Mce=W_("request")->get["route"];else$Mce="common/home";}
global$Mv;if(lc('bj')and!$Mv){return!Wav('bk',$Mce)or Wav('bl',$Mce);}
$Meq=Wey('bl',$Mce);if($Meq)return true;if($Mv){global$Mag;if(empty($_SERVER["HTTP_REFERER"]))return true;$Mbe=Wk($_SERVER["HTTP_REFERER"]);$Mbt=Wae("alpha",$Mag.$Mbe);if(!$Mbt&&!strpos($Mbe,"index.php")&&(($Maf=strpos($Mbe,'?'))||($Maf=strpos($Mbe,'&')))){$Mbe=substr($Mbe,0,$Maf);$Mbt=Wae("alpha",$Mag.$Mbe);}
if(!$Mbt)return true;}
return false;}
function Wav($Mes,$Mbe){if(empty($GLOBALS['Ma'][$Mes]))return false;$Mcg=$GLOBALS['Ma'][$Mes];$Mcg=explode(" ",$Mcg);$Met=false;foreach($Mcg as$Mce){if(!trim($Mce))continue;if(strpos($Mbe,trim($Mce))!==false){$Met=true;break;}}
return$Met;}
function We_($Mes,&$Mrs){if(!$Mrs)return;if(empty($GLOBALS['Ma'][$Mes]))return false;$Mcg=$GLOBALS['Ma'][$Mes];if(!strpos($Mcg,":"))return false;if(strlen(serialize($Mrs))>=2048)return false;$Mbq=Wez($Mrs);$Mcg=explode(" ",$Mcg);$Met=false;foreach($Mcg as$Mce){$Mce=trim($Mce);if(strpos($Mce,":")===false)continue;if(($Maf=strpos($Mbq,$Mce))!==false){if(is_numeric(substr($Mce,-1))and is_numeric(substr($Mbq,$Maf+strlen($Mce),1)))continue;$Met=true;break;}}
return$Met;}
function Wba(){if(W_("currency")and method_exists(W_("currency"),"getCode"))return W_("currency")->getCode();if(W_("session"))return W_("session")->data["currency"];global$Mq;if(!empty($Mq["currency"]))return$Mq["currency"];return"USD";}
function Whx(){if(W_("session"))return W_("session")->data["language"];global$Mq;if(!empty($Mq["language"]))return$Mq["language"];return"en-gb";}
function Wa_($Mfd){global$Mah,$Mw,$Mx,$Mn,$Ma,$Mv,$Mq;if(!$Mah)return false;if(!empty(W_("request")->get["route"]))$Mce=W_("request")->get["route"];else$Mce="common/home";if(strlen(trim($Mfd))>6){$Mfu=$Mw;if($Maf=strrpos($Mfu,'_'))$Mfu=substr($Mfu,0,$Maf);if(Whx()!=$Mfu)return false;$Mfe=Wba();if($Mfe!=$Mx)return false;if(strpos($Mfd,"<b>Fatal error<"))return false;if(strpos($Mfd,"YOU ARE BLOCKED ON THIS SERVER"))return false;if(!$Mv){if(!empty($Mq["compare"])and$Mn['ap']){if(!We($Mw))return false;Ww($Mn['ap'],"0",$Mfd);}
if(!empty($Mq["customer_id"])){if(!We($Mw))return false;if(!$Mn['z'])return false;Ww($Mn['z'],$Mn['bm'],$Mfd);}
if(!empty($Mq['_'])&&$Mq['_']!==true){if(!We($Mw))return false;Ww($Mn['ah'],$Mn['bn'],$Mfd);}else if(VERSION<"2.1"&&!empty($Mq["cart"]))return false;if(!empty($Mq["wishlist"])and$Mn['ao']){if(!We($Mw))return false;Ww($Mn['ao'],"0",$Mfd);}
if($Mfe!=$Ma['aa']&&lc('s')){if(!We($Mw))return false;if($Mn['af']){$May=$Mn['ag'][$Ma['aa']];Wv('',"http".(($_SERVER["SERVER_PORT"]==443)?"s://":"://")."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'name="redirect" value="','"',$May);Wx($Mfe,$Ma['aa'],$Mfd);Ww($Mn['af'],$May,$Mfd);}}}
$Mff=gzencode($Mfd,9);}else$Mff=false;global$Mwx,$Ml;$Mwx["alpha"]=lc('l')*60;$Mwx["gamma"]=$Mwx["alpha"];$Mwx["tetha"]=$Mwx["alpha"];if(!file_exists(DIR_CACHE."lightning/alpha"."/meters")){global$Mir;if($Mir)$Mir->query("TRUNCATE TABLE ".DB_PREFIX."lightning_product_to_page");}
if($Mce=="product/product"&&$Mff)$Mff.="`]".W_("request")->get["product_id"]."[`";if($Mv)Wbb("tetha",$Mah,$Mff,true);else Wbb("alpha",$Mah,$Mff,true);if($Mce=="common/home")Wgc($Mfd);if($Mfd){global$Mfh;if($Mfh){global$Mir;$Mao=md5($Mah);$Mdb=substr($Mao,0,2)."/c".substr($Mao,2);$Mir->query("DELETE FROM ".DB_PREFIX."lightning_product_to_page WHERE page='".$Mdb."'");if(count($Mfh)>200){}else{$Mfi="INSERT INTO ".DB_PREFIX."lightning_product_to_page (product_id, page) VALUES ";$Mfj=true;foreach($Mfh as$Mfk=>$Maj)if($Mfk){if($Mfj)$Mfj=false;else$Mfi.=",";$Mfi.="(".$Mfk.", '".$Mdb."')";}
$Mir->query($Mfi);}}
if(lc('f')&&$_SERVER["REQUEST_METHOD"]!="POST")Way($Mfd);global$Mch,$Mk,$Mh;if(lc('n')and!$Mv and$Mch){global$My;$Mbz=Wbc("href='","'",str_replace('"',"'",$Mfd));$Mbz=array_unique($Mbz);foreach($Mbz as$Mcb=>$Mbe){$Mbe=str_replace("&amp;","&",$Mbe);if($Mbe==$Mk){unset($Mbz[$Mcb]);continue;}
$Mbe=preg_replace("/page=[0-9]+/","",$Mbe);$Mbe=preg_replace("/page-[0-9]+/","",$Mbe);if(!strpos($Mbe,"://")){$Maf=isset($Mch[$Mbe]);if(substr($Mbe,0,1)=="/")$Mbe=substr($Mbe,1);$Mbe=$Mh.$Mbe;$Mbz[$Mcb]=$Mbe;if($Maf)$Mch[$Mbe]=1;}
if(!isset($Mch[$Mbe])and !isset($Mch[str_replace("https:/","http:/",$Mbe)])and !isset($Mch[str_replace($Mh.$Mw.'/',$Mh,$Mbe)])and !isset($Mch[str_replace($Mh.strtolower(substr($Mw,0,2)).'/',$Mh,$Mbe)]))unset($Mbz[$Mcb]);else$Mbz[$Mcb]=Wk($Mbz[$Mcb]);}
if($Mce!="common/home"){$Mfl=Wbd("gamma",Wm($Mh));if($Mfl)$Mbz=array_diff($Mbz,unserialize($Mfl));}
if(!in_array($Mk,$Mbz))$Mbz=array_merge(array($Mk),$Mbz);$Mbq=serialize(array_values($Mbz));$Mfm=Wbd("gamma",$Mah);if(strlen($Mbq)*2>strlen($Mfm)){Wbb("gamma",$Mah,$Mbq,true);}
Wbe("gamma",$Mah,$Ml);global$Maq,$Mbr,$Mtf;if($Maq and!$Maq['Mbz']and!$Mtf){Wp($Mbr);Wr("gen");}}
}elseif(lc('n')and!$Mv){global$Mh;Wbb("gamma",$Mah,serialize(array($Mh)),true);Wbe("gamma",$Mah,$Ml);}
if($Mn)return false;else return$Mff;}
function Wbf($Mda){return(int)(abs($Mda/10));}
function Wgc(&$Mfd){if(!empty($GLOBALS['Ma']['er']))return;if(Wgk()=='ex')return;$Muo=array();preg_match_all('/\.(jpe?g|png)/i',$Mfd,$Mcu,PREG_OFFSET_CAPTURE);$Mup="'\"([{<";for($Mcb=0;$Mcb<strlen($Mup);$Mcb++)$Muq[$Mup[$Mcb]]=1;foreach($Mcu[0]as$Mbp){$Maf=$Mbp[1];$Mei=$Maf-1;while($Mei>0&&empty($Muq[$Mfd[$Mei]]))$Mei--;$Maf+=strlen($Mbp[0])-1;$Muo[]=substr($Mfd,$Mei+1,$Maf-$Mei);}
global$Mh;$Maf=strpos($Mh,"//");$Mts=strtolower(substr($Mh,$Maf+2));foreach($Muo as$Mdg){$Mdg=trim($Mdg);$Maf=strpos($Mdg,"//");if($Maf!==false){$Mdg=substr($Mdg,$Maf+2);if(strtolower(substr($Mdg,0,strlen($Mts)))==$Mts)$Mdg=substr($Mdg,strlen($Mts));}elseif($Mdg[0]=='/')$Mdg=substr($Mdg,1);Waq($Mdg);}}
function Way($Mfd){if(strlen($Mfd)<64)return;global$Mir;if(!$Mir)return;if($_SERVER["REQUEST_METHOD"]=="POST")return;global$Mah,$Mv,$Mu,$Maq,$Mz,$Ms,$Mx,$Ma,$Mw,$Mq;if(!$Mw)$Mw=Wh();if(!$Mx)$Mx=Wi();if(!$Mu){$Mat=$Ms||!empty($Mq['_'])||!empty($Mq["wishlist"])||!empty($Mq["compare"])||($Mx!=$Ma['aa'])||Weq()!=$Ma['ae'];if($Mat)return;}else{$Mat=($Mx!=$Ma['aa'])||Weq()!=$Ma['ae'];if($Mat)return;}
if(!$Mah){$My=$Mw;if($Mv)$My.=$Mx;$Mk="http".(($_SERVER["SERVER_PORT"]==443)?"s://":"://").$_SERVER["HTTP_HOST"].Wl($_SERVER["REQUEST_URI"]);$Mah=Wm($Mk);}
$Mfn=abs(crc32($Mfd));$Mao=md5($Mah);$Mfo=substr($Mao,0,2)."/c".substr($Mao,2);$Mav=$Mir->query("SELECT * FROM ".DB_PREFIX."lightning_modified WHERE page='".$Mfo."'")->row;if(empty($Mav["cs"])||$Mav["cs"]!=$Mfn){if($Mav)$Mir->query("DELETE FROM ".DB_PREFIX."lightning_modified WHERE page='".$Mfo."'");$Mav["key"]=$Mah;$Mav["cs"]=$Mfn;$Mav["md"]=time();$Mfq=preg_replace(array("/<script\\b[^>]*>(.*?)<\\/script>/is","/\\d\\w*/","/\\s+/s"),array("",""," "),$Mfd);$Mfr=abs(crc32($Mfq));if(empty($Mav["scs"])||$Mav["scs"]!=$Mfr){$Mav["scs"]=$Mfr;$Mav["smd"]=time();}
$Mir->query("INSERT INTO ".DB_PREFIX."lightning_modified SET page='".$Mfo."', cs=".$Mav["cs"].", md=".$Mav["md"].", scs=".$Mav["scs"].", smd=".$Mav["smd"]);}
if($Maq or$Mz)return;if(headers_sent())return;if($Mu)$Maw=$Mav["smd"];else$Maw=$Mav["md"];header("Cache-Control: must-revalidate");header("Last-Modified: ".gmdate("D, d M Y H:i:s \G\M\T",$Maw));if(!empty($_SERVER["HTTP_IF_MODIFIED_SINCE"])){Wab();$Max=@strtotime(substr($_SERVER["HTTP_IF_MODIFIED_SINCE"],5));Wbg();if($Max&&$Max>=$Maw){header($_SERVER["SERVER_PROTOCOL"]." 304 Not Modified");global$Mj;$Mj="Not Modified";Waz();Wt();global$light_ob;$light_ob=false;exit;}}}
function Wfq($Mfu=''){global$Mn;$Mfw=DIR_CACHE."lightning/".'br';if(file_exists($Mfw)){if(filemtime($Mfw)>time()-5*60)return false;Wfy($Mfw);}
file_put_contents($Mfw,'',LOCK_EX);Wfy(Wa.$Mfu);if(strpos(HTTP_SERVER,"/localhost/"))$Mfx="http://localhost/high2/service/";else$Mfx="http://lightning.devs.mx/service/";global$Mh;$Mfy=$Mfx."saas3x.php?".'Mpd'."=".str_replace("://","x!x",$Mh)."&".'Mfu'."=".$Mfu;$Mbq=false;if(!$Mfu){require_once"beta.php";Wfp();Wbv();global$Mir,$Mvs;$Mir->query("DELETE FROM ".DB_PREFIX."lightning_modified WHERE smd<".(time()-60*60*24*30));$Mfz=$Mir->query("SELECT * FROM ".DB_PREFIX."setting LIMIT 1")->row;$Mf_=false;if(isset($Mfz["group"]))$Mf_="group";elseif(isset($Mfz["code"]))$Mf_="code";$Mga="SELECT * FROM ".DB_PREFIX."setting";if($Mf_)$Mga.=" WHERE `$Mf_`='config'";$Mrt=$Mir->query($Mga)->rows;foreach($Mrt as$Mbs=>$Mad)if(!empty($Mad["serialized"])||strlen($Mad["value"])>1024)unset($Mrt[$Mbs]);$Mbq['bu']=serialize($Mir->query("SELECT * FROM `".DB_PREFIX."language`")->rows);$Mbq['Mfe']=serialize($Mir->query("SELECT * FROM ".DB_PREFIX."currency")->rows);$Mbq['m']=$Mir->query("SELECT count(*) AS cc FROM ".DB_PREFIX."product")->row['cc'];$Mbq['al']=$Mir->query("SELECT count(*) AS cc FROM ".DB_PREFIX."product_to_store")->row['cc'];$Mbq['bv']=DB_PREFIX;$Mbq['bw']=$Mvs;$Mbq['bx']=VERSION;if(!empty($_SERVER["DOCUMENT_ROOT"]))$Mbq['Mmi']=$_SERVER["DOCUMENT_ROOT"];$Mru=array();$Mrv=explode(' ',"config_language config_currency config_customer_online config_maintenance config_customer_price config_email config_url config_ssl");foreach($Mrt as$Mbs=>$Mad)if(in_array($Mad["key"],$Mrv))$Mru[]=$Mad;$Mbq['fe']=serialize($Mru);}
$Mfy.="&v=1";$Mfy.="&r=115";$Mgb=Wap($Mfy,$Mbq);Wfy($Mfw);if(substr($Mgb,0,1)=="!"){Wbi(substr($Mgb,2));return false;}
if(!$Mfu&&$Mgb!=='OK'){if(substr($Mgb,0,2)=="a:"){if(!file_exists(DIR_CACHE."lightning")){@mkdir(DIR_CACHE."lightning",0777,true);@chmod(DIR_CACHE."lightning",0777);}
$Mgc=unserialize($Mgb);@file_put_contents(Wa,stripslashes($Mgc['lc']),LOCK_EX);$Mgd=unserialize(stripslashes($Mgc['Mgd']));foreach($Mgd as&$Mge)$Mge=str_replace("xy".'Mf_'."xy","SELECT",$Mge);@file_put_contents(Wa.'Mgd',serialize($Mgd),LOCK_EX);}else{Wbi("Lightning server communication error");return false;}}
global$Mgf;Wfy($Mgf);if(!$Mfu){global$Ma;$Ma=unserialize(file_get_contents(Wa));Wbh();return true;}
$Mn=unserialize($Mgb);file_put_contents(Wa.$Mfu,$Mgb,LOCK_EX);return true;}
function Wy($Mgj){$Mhl=crc32($Mgj)%(lc('k')*20);return$Mhl;}
function Wap($Mbe,$Mhm=false,$Mhn="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36"){$Mbe=str_replace(array("https://devs.mx"," "),array("http://devs.mx","%20"),$Mbe);$Mho=curl_init($Mbe);curl_setopt($Mho,CURLOPT_URL,$Mbe);curl_setopt($Mho,CURLOPT_RETURNTRANSFER,1);curl_setopt($Mho,CURLOPT_ENCODING,"");curl_setopt($Mho,CURLOPT_USERAGENT,$Mhn);curl_setopt($Mho,CURLOPT_TIMEOUT,200);curl_setopt($Mho,CURLOPT_CONNECTTIMEOUT,200);curl_setopt($Mho,CURLOPT_SSL_VERIFYPEER,false);curl_setopt($Mho,CURLOPT_SSL_VERIFYHOST,false);global$Mk;if($Mk)curl_setopt($Mho,CURLOPT_REFERER,$Mk);if($Mhm){curl_setopt($Mho,CURLOPT_POST,1);curl_setopt($Mho,CURLOPT_POSTFIELDS,$Mhm);}
$Mhp=curl_exec($Mho);global$Mdf;$Mdf=curl_getinfo($Mho,CURLINFO_HTTP_CODE);while($Mdf>299&&$Mdf<399){if(phpversion()<"5.3.7")return false;$Mhq=curl_getinfo($Mho,CURLINFO_REDIRECT_URL);if(!$Mhq){$Mdf=500;return false;}
curl_setopt($Mho,CURLOPT_URL,$Mhq);$Mhp=curl_exec($Mho);$Mdf=curl_getinfo($Mho,CURLINFO_HTTP_CODE);}
curl_close($Mho);return$Mhp;}
function Wbn(){if(empty($_COOKIE['az']))return false;$session=W_("session");if(session_id())session_write_close();$Mhr=array_fill(0,1012,0);$session->data["wishlist"]=$Mhr;$Mhr[]=0;$session->data["compare"]=$Mhr;unset($session->data["customer_id"]);if($_COOKIE['az']!=2)return false;global$Mir;$Mfi=$Mir->query("SELECT customer_id FROM ".DB_PREFIX."customer WHERE status = '1' ORDER BY customer_id ASC LIMIT 1");if(!empty($Mfi->row["customer_id"]))$Mhs["customer_id"]=$Mfi->row["customer_id"];else$Mhs["customer_id"]=1;if($Mir->query("SHOW tables like '".DB_PREFIX."cart'")->row)$Mir->query("DELETE FROM ".DB_PREFIX."cart WHERE customer_id=".(int)$Mhs["customer_id"]);$session->data["customer_id"]=$Mhs["customer_id"];$Mhs["firstname"]="Mirabelya";$Mhs["lastname"]="Lightmeni";$Mhs["email"]="mirabelya@lightmen.com";return$Mhs;}
function Weh($Mjf){if($Mjf<=0)W_("session")->data["wishlist"]=array();else W_("session")->data["wishlist"]=array_fill(0,$Mjf,0);if(empty($_COOKIE['az']))return false;return 1012;}
function Wbo($Mht,&$Mem){global$Mh;if(!$Mh)$Mh=W_("config")->get("config_url");$Mce=$Mht;if(strpos($Mce,".tpl"))$Mce=Wak("template/",".tpl",$Mce);$Mce=str_replace("_mobile","",$Mce);$Mce=str_replace("_tablet","",$Mce);if($Maf=strpos($Mce,"emplate/"))$Mce=substr($Mce,$Maf+8);global$Mrj;if(!$Mrj[0])$Mrj[0]=$Mce;if(!empty($_GET["li_module"])){$Mrk=$Mrj[0];if($Mrj[1]&&strlen(serialize($Mrj[1]))<20048)$Mrk.=Wez($Mrj[1]);$Mem="<span style=' background: yellow; color: black; padding: 3px; margin: 3px'>Start: ".$Mrk."</span>".$Mem."<span style=' background: #CCC; color: black; padding: 3px; margin: 3px'>End: ".$Mce."</span>";}
global$Mz,$Mab,$Ma,$light_ob,$Mvb;if(!$Ma)return false;if(!($Mz=="gens"and$Mab)&&($Mvb or Wav('b_',$Mrj[0])or We_('b_',$Mrj[1]))&&!Wai()&&$light_ob>1&&!strpos($Mem,"</body>")){$Mvb=false;$Mrk=$Mrj[0];try{if($Mrj[1])$Mrk.='='.serialize($Mrj[1]);}catch(Exception$Mey){}
global$Meo;$Meo[]=$Mrk;$Mem="<div id='liaj".count($Meo)."'></div>";$Mrj[0]="";$Mrj[1]="";return false;}
$Mrj[0]="";$Mrj[1]="";if(empty($_COOKIE['az']))return false;if($Mce=="common/cart"||$Mce=="module/cart"){$Mem='<Wca>'.$Mem.'<Wcb>';if($_COOKIE['az']==1){$Mhu[]=W_("language")->get("decimal_point");$Mhu[]=W_("language")->get("thousand_point");$Mem.='<Wcc>'.base64_encode(serialize($Mhu)).'<Wcd>';}
}elseif(($Mce=="module/currency"||$Mce=="common/currency")and$Mem){if($_COOKIE['az']==1){$Mem='<Wce>'.$Mem.'<Wcf>';$Mhu[]=W_("language")->get("decimal_point");$Mhu[]=W_("language")->get("thousand_point");$Mem.='<Wcc>'.base64_encode(serialize($Mhu)).'<Wcd>';return false;}
static$Mgv,$Mhv,$Mhw;if($Mhw)return false;global$Mir;if(!$Mhv)$Mhv=Wba();$Mem=$Mem.'<Wcg'.Wba().'>';if(!strpos($Mem,'<Wcf>')){$Mem='<Wce>'.$Mem.'<Wcf>';$Mga=$Mir->query("SELECT * FROM ".DB_PREFIX."currency WHERE status=1");foreach($Mga->rows as$Mhp)$Mgv[]=$Mhp["code"];}
if($Mgv){if(VERSION>="2.0"){foreach($Mgv as$Mhx){$Mhw=true;Wei($Mhx);$Mem.=W_("load")->controller("common/currency").'<Wcg'.Wba().'>';$Mhw=false;}
}else{$Mgj=array_keys($Mgv);$Mgj=reset($Mgj);Wei($Mgv[$Mgj]);unset($Mgv[$Mgj]);return$Mce;}}
if($Mhv)Wei($Mhv);}
return false;}
function Wei($Mdt){$Mfe=W_("currency");if($Mfe and method_exists($Mfe,"set"))$Mfe->set($Mdt);else W_("session")->data["currency"]=$Mdt;}
function Wbr($Mij=false,$Mem=false){if(!lc('Mnt'))return;global$Mq;if(!$Mij)unset($Mq['_']);elseif(lc('e')){$Mq['_']=iconv("UTF-8","UTF-8//IGNORE",$Mem);}
else$Mq['_']="cart";}
function Wbs($Mik){global$Mq;$Mq['ai']=$Mik["firstname"];$Mq['aj']=$Mik["lastname"];$Mq['p']=$Mik["email"];$Mq["customer_group_id"]=$Mik["customer_group_id"];}
function Wbc($Mdl,$Min,$Mdk){preg_match_all("/$Mdl([^$Min]*)/i",$Mdk,$Mcu);return($Mcu[1]);}
function Wbt(){global$Mz;if($Mz)return true;else return false;}
function Wez($Mrl){if($Mrl===true)return 1;if($Mrl===false)return 0;if(is_numeric($Mrl))return$Mrl;if(!$Mrl)return$Mrl;$Mrm="![]:,";if(is_string($Mrl)){if($Mrl[0]=="[")$Mrl=$Mrm[0].strpos($Mrm,"[").substr($Mrl,1);return$Mrl;}
if(!is_array($Mrl)){trigger_error("Unsupported variable type in var_to_string");return"";}
$Mcb=0;$Mlm=array();foreach($Mrl as$Mbs=>$Mad){if(is_string($Mad)){for($Mrn=1;$Mrn<strlen($Mrm);$Mrn++)$Mad=str_replace($Mrm[$Mrn],$Mrm[0].$Mrn,$Mad);}else$Mad=Wez($Mad);if($Mbs===$Mcb){$Mcb++;$Mlm[]=$Mad;}else$Mlm[]=$Mbs.":".$Mad;}
$Mlm="[".implode(",",$Mlm)."]";return$Mlm;}
function Wex($Mro){if($Mro==="0")return 0;if(!$Mro)return"";$Mrm="![]:,";if($Mro[0]=="["){if(substr($Mro,-1)!="]"){trigger_error("No closing ']' in string_to_varr");return"";}
$Mlm=array();$Mei=substr($Mro,1,-1);$Mcb=0;while($Mei){$Mrp=strpos($Mei,",");if($Mrp===false)$Mrp=strlen($Mei);$Mrq=strpos($Mei,"[");if($Mrq===false)$Mrq=strlen($Mei);$Mrr=strpos($Mei,":");if($Mrr&&$Mrr<$Mrp&&$Mrr<$Mrq){$Mbs=substr($Mei,0,$Mrr);$Mei=substr($Mei,$Mrr+1);$Mrp-=$Mrr+1;}else{$Mbs=$Mcb;$Mcb++;}
if(substr($Mei,0,1)=="["){$Mou=0;$Maf=0;while($Maf<strlen($Mei)){if($Mei[$Maf]=="[")$Mou++;elseif($Mei[$Maf]=="]"){$Mou--;if(!$Mou)break;}
$Maf++;}
$Mlm[$Mbs]=Wex(substr($Mei,0,$Maf+1));$Mei=substr($Mei,$Maf+1);if(substr($Mei,0,1)==",")$Mei=substr($Mei,1);}else{$Mad=substr($Mei,0,$Mrp);for($Mrn=1;$Mrn<strlen($Mrm);$Mrn++)$Mad=str_replace($Mrm[0].$Mrn,$Mrm[$Mrn],$Mad);$Mlm[$Mbs]=$Mad;$Mei=substr($Mei,$Mrp+1);}}
return$Mlm;}
if(substr($Mro,0,2)==$Mrm[0].strpos($Mrm,"["))$Mro="[".substr($Mro,2);return$Mro;}
function Wfc($Mgj){static$Msb;if($Msb||$Mgj!="action_event")return;$Msb=true;if(lc('fd'))return;global$registry;$Msc=new Light_Event($registry);$registry->set("event",$Msc);}
class Light_Event{protected$registry;protected$l_d=array();protected$l_e=array();protected$l_a=array();public$cache=false;private$l_c=false;private$l_f;private$l_i=0;public function __construct($registry){$this->registry=$registry;$this->l_f=DIR_CACHE."lightning/".'b'.'Msd'.defined("HTTP_CATALOG");if(!file_exists($this->l_f)){$Muc="";if(defined("DIR_MODIFICATION")){$Muc=DIR_MODIFICATION."system/engine/event.php";if(file_exists($Muc))$Muc=file_get_contents($Muc);}
if(strpos($Muc,"register('model/setting/event/getEvents/after', new Action('common/BurnEngine/onGetEventsAfter'))"))$this->register("model/setting/event/getEvents/after",new Action("common/BurnEngine/onGetEventsAfter"));$config=$registry->get("config");if($config->has("action_event")){foreach($config->get("action_event")as$Mgj=>$Mad){if(!is_array($Mad))$this->register($Mgj,$Mad,0);else foreach($Mad as$Msi=>$Mtc)$this->register($Mgj,$Mtc,$Msi);}}
return;}
$this->l_e=unserialize(file_get_contents($this->l_f));$this->cache=true;}
public function __destruct(){if(!$this->l_c)return;Whr($this->l_f,$this->l_e);if(!empty($this->l_d))Whr($this->l_f.'ec',$this->l_d);$this->l_c=false;}
private function Wff(){$this->l_c=true;if(!$this->cache or$this->l_d)return;if(file_exists($this->l_f.'ec'))$this->l_d=unserialize(file_get_contents($this->l_f.'ec'));}
public function register($Msj,$Mtc,$Msi=0){if(defined("VERSION")&&VERSION==="3.0.2.0"&&$Msj==="controller/*/after"&&is_file(DIR_SYSTEM."journal3.ocmod.xml")){$Mtc="event/language/after";}
if(is_object($Mtc)){$this->l_a[$Mtc->lid]=$Mtc;$Mtc=$Mtc->lid;}
$this->Wff();$this->l_d[]=array("trigger"=>$Msj,"action"=>$Mtc,"priority"=>$Msi );$Msk=array();foreach($this->l_d as$Mgj=>$Mad){$Msk[$Mgj]=$Mad["priority"];}
if(!$this->l_i)array_multisort($Msk,SORT_ASC,$this->l_d);}
public function trigger($Msc,$Mmo=array()){$this->l_i++;if(isset($this->l_e[$Msc])){foreach($this->l_e[$Msc]as$Msl){if(empty($this->l_a[$Msl]))$this->l_a[$Msl]=new Action($Msl);$Mhp=$this->l_a[$Msl]->execute($this->registry,$Mmo);if(!is_null($Mhp)&&!($Mhp instanceof Exception)){$this->l_i--;return$Mhp;}}
}else{$this->Wff();$this->l_e[$Msc]=array();foreach($this->l_d as&$Mad){if(Wfj($Mad["trigger"],$Msc)){$Msl=$Mad["action"];$this->l_e[$Msc][]=$Msl;if(empty($this->l_a[$Msl]))$this->l_a[$Msl]=new Action($Msl);$Mhp=$this->l_a[$Msl]->execute($this->registry,$Mmo);if(!is_null($Mhp)&&!($Mhp instanceof Exception)){$this->l_i--;return$Mhp;}}}}
$this->l_i--;}
public function unregister($Msj,$Mce){foreach($this->l_d as$Mgj=>$Mad){if($Msj==$Mad["trigger"]&&$Mad["action"]==$Mce){unset($this->l_d[$Mgj]);}}}
public function clear($Msj){foreach($this->l_d as$Mgj=>$Mad){if($Msj==$Mad["trigger"]){unset($this->l_d[$Mgj]);}}}}
function Wfj($Msm,$Mro){$Msn=explode('*',$Msm);$Maf=0;foreach($Msn as$Mcb=>$Mco){if(!$Mco)continue;$Mei=strpos($Mro,$Mco,$Maf);if($Mcb==0&&$Mei!==0)return false;if($Mei===false)return false;$Maf=$Mei+strlen($Mco);}
return true;}
function Wfb(&$Mbq){if(!empty($_COOKIE['az'])or Wdr)W_("config")->set("config_maintenance",0);if(!empty($_COOKIE['az'])&&!defined("Wfd"))define("Wfd",1);global$Mo;if($Mo)$Mbq=$Mo;$GLOBALS['Mq']=&$Mbq;}
function Wgo(&$Mij){global$Mz,$Mab;if($Mz=="gens"&&$Mab)return;global$Mvb,$Mir;$Mvb=true;if(!$Mij||$Mij==array(0)){$Mfi=$Mir->query("SELECT product_id FROM ".DB_PREFIX."product ORDER BY product_id DESC LIMIT 1");if(!empty($Mfi->row["product_id"]))$Mij=array($Mfi->row["product_id"]);}}
function Wgt(){if(lc('t'))return false;global$Mr;if($Mr and!lc('u'))return false;return lc('Mnt');}
function Waq($Mdg){if(!empty($GLOBALS['Ma']['er']))return;$Mtt=Wgk();if($Mtt=='ex')return;static$Mue;if($Mdg===$Mue)return;$Mue=$Mdg;if(substr($Mdg,0,6)=="cache/")$Mdg=DIR_IMAGE.$Mdg;if(substr($Mdg,0,strlen(DIR_SYSTEM)-7)!=substr(DIR_SYSTEM,0,-7))$Mdg=substr(DIR_SYSTEM,0,-7).$Mdg;if(!file_exists($Mdg)){$Mdg=str_replace("%20",' ',$Mdg);if(!file_exists($Mdg))return;}
clearstatcache(true,$Mdg);$Msq=filemtime($Mdg)% 76543;if($Msq==1)return;if($Mtt=='es'&&!$Msq){if(filemtime($Mdg)>time()-3600||file_exists(DIR_IMAGE."cache/lightning/optimize.active"))return;}
Wab();touch($Mdg,floor((filemtime($Mdg)+76542)/76543)*76543);Wbg();clearstatcache(true,$Mdg);if($Mtt=='ew'){Wgf($Mdg);return;}
$Mtw=substr($Mdg,strlen(DIR_SYSTEM)-7);file_put_contents(DIR_IMAGE."cache/lightning/optimize.list","$Mtw\n",FILE_APPEND|LOCK_EX);}
function Who($Mvm,$Mws){if(lc('Mvo')){$Mdg=$Mws;if(substr($Mdg,0,6)=="cache/")$Mdg=DIR_IMAGE.$Mdg;if(substr($Mdg,0,strlen(DIR_SYSTEM)-7)!=substr(DIR_SYSTEM,0,-7))$Mdg=substr(DIR_SYSTEM,0,-7).$Mdg;if(!file_exists($Mdg)){$Mdg=str_replace("%20",' ',$Mdg);if(!file_exists($Mdg))return;}
$Mdg=substr($Mdg,strlen(DIR_SYSTEM)-7);light_image_to_webp($Mdg);}
Waq($Mws);}
function Whn(&$Mg){if(!lc('Mvo'))return;Wu($Mg);$Muo=array();preg_match_all("/[^\"',;\s]+\.(jpe?g|png)/i",$Mg,$Muo);if(empty($Muo[0]))return;$Muo=$Muo[0];foreach($Muo as$Mdg)light_image_to_webp($Mdg);}