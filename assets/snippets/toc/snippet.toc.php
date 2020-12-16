<?php
//$modx = evolutionCMS();
//Default params
$lStart = isset($lStart) ? $lStart : 2;
$lEnd = isset($lEnd) ? $lEnd : 3;
$tocTitle = isset($tocTitle) ? $tocTitle : 'Сontents';
$tocClass = isset($tocClass) ? $tocClass : 'toc';
$tocAnchorType = (isset($tocAnchorType) and ($tocAnchorType == 2)) ? 2 : 1;
$tocAnchorLen = (isset($tocAnchorLen) and ($tocAnchorLen > 0)) ? $tocAnchorLen : 0;
$content = (isset($content)) ? $modx->getTpl($content) : $modx->documentObject['content'];

$contentTpl = (isset($contentTpl)) ? $modx->getTpl($contentTpl) : $modx->getTpl('@FILE:assets/snippets/toc/templates/content.tpl');
$sidebarTpl = (isset($sidebarTpl)) ? $modx->getTpl($sidebarTpl) : $modx->getTpl('@FILE:assets/snippets/toc/templates/sidebar.tpl');

$show = (isset($show)) ? $show : 0;
$upbutton = (isset($upbutton)) ? $upbutton : 0;
$css = (isset($css)) ? $css : 1;

//show or hide
// 0 - hide all
// 1 - only content
// 2 - content & sidebar

if($show == 0) return $content;

//include transalias
$plugin_path = MODX_BASE_PATH.'assets/plugins/transalias';
$table_name = isset($table_name) ? $table_name : 'common';

if (!class_exists('TransAlias')) {
    require_once $plugin_path.'/transalias.class.php';
}
$trans = new TransAlias($modx);

$id = $modx->documentIdentifier;
$url = $modx->makeUrl($id,'','','full');

$tocResult = '';
$hArray = array();

$contLen = mb_strlen($content);

for($i=0;$i<=$contLen+1000; $i++) {

    $hPosBegin = mb_strpos($content, "<h", $i);
    if($hPosBegin !== false) $i = $hPosBegin; else break;
    $hPosEnd = 5 + mb_strpos($content, "</h", $i);
    if($hPosEnd) $i = $hPosEnd; else break;

    $h = mb_substr($content,$hPosBegin,$hPosEnd-$hPosBegin);

    $hLevel = mb_substr($h,2,1);

    if($hLevel >= $lStart and $hLevel <= $lEnd) {
        $hArray[$i]['header_in'] = $h;
        $hArray[$i]['level'] = $hLevel;

        preg_match("/<a [\s\S]*?name=\"([\w]+)\"/", $h, $getAnchor);

        if(count($getAnchor) > 1 ){
            $hArray[$i]["anchor"] = $getAnchor[1];
        } else {
            $hArray[$i]["anchor"] = "";
            $anchorPos = 1 + mb_strpos($hArray[$i]["header_in"], ">", 0);
            if($tocAnchorType == 2) {
                $hArray[$i]["anchor"] = $i;
            } elseif ($tocAnchorType == 1) {

                if ($trans->loadTable($table_name,'Yes')) {
                    $anchorName = $trans->stripAlias($h,'lowercase alphanumeric','-');
                    if($tocAnchorLen > 0) {
                        $anchorName = substr($anchorName,0,$tocAnchorLen);
                    }
                    $hArray[$i]["anchor"] = $anchorName;
                } else {
                    $hArray[$i]["anchor"] = $i;
                }
            }
            $hArray[$i]["header_out"] = mb_substr($hArray[$i]["header_in"], 0, $anchorPos) . "<a name=\"" . $hArray[$i]["anchor"] ."\"></a>" . mb_substr($hArray[$i]["header_in"], $anchorPos);
            $content = str_replace($hArray[$i]["header_in"], $hArray[$i]["header_out"], $content);
        }
    } else {
        $i = $hPosBegin + 5;
    }
}
//echo '<pre>';
//print_r($hArray);
//die();
if(count($hArray) > 0) {

    $curLev = 0;
    foreach ($hArray as $key => $value) {
        if($curLev == 0) {
            $tocResult .= '<ul class="' . $tocClass . '_' . $value['level'] . '">';
        } elseif($curLev != $value['level']) {
            if($curLev < $value['level']) {
                $tocResult .= '<ul class="' . $tocClass . '_' . $value['level'] . '">';
            } else {
                $tocResult .= str_repeat('</ul></li>',$curLev - $value['level']);
            }
        } else {
            $tocResult .= '</li>';
        }
        $tocResult .= '<li><a href="' . $url . '#' . $value['anchor'] . '">' . strip_tags($value['header_in']) . '</a>';
        $curLev = $value['level'];
    }

    $tocResult .= str_repeat('</ul></li>',$curLev - $lStart) . '</ul>';
    $tocResult = '<div class="' . $tocClass . '">' . $tocResult . '</ul></div>';

    if ($css == 1) {
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . MODX_SITE_URL . 'assets/snippets/toc/css/toc.css">');
    }
    if($show == 2 OR $upbutton != 0){
        $modx->regClientHTMLBlock('
            <script defer src="https://unpkg.com/smoothscroll-polyfill@0.4.4/dist/smoothscroll.min.js"></script>
            <script src="'.MODX_SITE_URL.'assets/snippets/toc/js/toc.js?v=1"></script>
        ');
    }
    if ($show == 2){
        $modx->regClientHTMLBlock($modx->parseText($sidebarTpl, ['class'=>$tocClass, 'title'=>$tocTitle, 'items'=>$tocResult]));
    }

    if($upbutton != 0){
        $modx->regClientHTMLBlock('<button type="button" class="up-btn" id=upBtn>&#x25B2;</button>');
    }

    return $modx->parseText($contentTpl, ['class'=>$tocClass, 'title'=>$tocTitle, 'items'=>$tocResult, 'content'=>$content]);
}




