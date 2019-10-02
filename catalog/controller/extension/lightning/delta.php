<?php 
 
function light_minify_html(&$html) { 
    if (!strpos($html, "<body")) return; 
    try { 
        $minify = xMinify_HTML::minify($html, array( 
            'cssMinifier' => array('xMinify_CSS', 'minify'), 
            'jsMinifier' => array('xJSMin', 'minify') 
        )); 
    } catch (Exception $e) { 
        return; 
    } 
    $html = $minify; 
} 
 
function light_minify_css(&$css, $link) { 
    if ($p = strrpos($link, '?')) $link = substr($link,0,$p); 
    $p = strrpos($link, '/'); 
    $folder = substr($link,0,$p); 
    try { 
        $minify = xMinify_CSS::minify($css, array( 
            'compress' => true, 
            'removeCharsets' => true, 
            'preserveComments' => false, 
            'currentDir' => null, 
            'docRoot' => null, 
            'prependRelativePath' => $folder, 
            'symlinks' => array())); 
    } catch (Exception $e) { 
        return; 
    } 
    $css = $minify; 
} 
 
function light_minify_js(&$js) { 
    if (strpos($js, "\\"."/"."/")) return; 
	$save = $js; 
    try { 
        $js = xJSMin::minify($js); 
    } catch (Exception $e) { 
        return; 
    } 
 
    $goal = strlen($save) * 0.02; 
    if ($goal < 10) $goal = 10; 
 
    if (strlen($save) - strlen($js) < $goal) $js = $save; 
    if (substr($js, 0, 1) == '"') $js = "\n".$js; 
} 
 

class xMinify_HTML { 
    protected $_jsCleanComments = true; 
    protected $_isXhtml = null; 
    protected $_replacementHash = null; 
    protected $_placeholders = array(); 
    protected $_cssMinifier = null; 
    protected $_jsMinifier = null; 
 
    public function __construct($html, $options = array()) 
    { 
        $this->_html = str_replace("\r\n", "\n", trim($html)); 
        if (isset($options['xhtml'])) { 
            $this->_isXhtml = (bool)$options['xhtml']; 
        } 
        if (isset($options['cssMinifier'])) { 
            $this->_cssMinifier = $options['cssMinifier']; 
        } 
        if (isset($options['jsMinifier'])) { 
            $this->_jsMinifier = $options['jsMinifier']; 
        } 
        if (isset($options['jsCleanComments'])) { 
            $this->_jsCleanComments = (bool)$options['jsCleanComments']; 
        } 
    } 
 
    public static function minify($html, $options = array()) { 
        $min = new self($html, $options); 
        return $min->process(); 
    } 
 
    public function process() 
    { 
        if ($this->_isXhtml === null) { 
            $this->_isXhtml = (false !== strpos($this->_html, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML')); 
        } 
 
        $this->_replacementHash = 'MINIFYHTML' . md5($_SERVER['REQUEST_TIME']); 
        $this->_placeholders = array(); 
 
        
        $this->_html = preg_replace_callback( 
            '/(\\s*)<script(\\b[^>]*?>)([\\s\\S]*?)<\\/script>(\\s*)/i' 
            ,array($this, '_removeScriptCB') 
            ,$this->_html); 
 
        
        $this->_html = preg_replace_callback( 
            '/\\s*<style(\\b[^>]*>)([\\s\\S]*?)<\\/style>\\s*/i' 
            ,array($this, '_removeStyleCB') 
            ,$this->_html); 
 
        
        $this->_html = preg_replace_callback( 
            '/<!--([\\s\\S]*?)-->/' 
            ,array($this, '_commentCB') 
            ,$this->_html); 
 
        
        $this->_html = preg_replace_callback('/\\s*<pre(\\b[^>]*?>[\\s\\S]*?<\\/pre>)\\s*/i' 
            ,array($this, '_removePreCB') 
            ,$this->_html); 
 
        
        $this->_html = preg_replace_callback( 
            '/\\s*<textarea(\\b[^>]*?>[\\s\\S]*?<\\/textarea>)\\s*/i' 
            ,array($this, '_removeTextareaCB') 
            ,$this->_html); 
 
        
        
        $this->_html = preg_replace('/^\\s+|\\s+$/m', '', $this->_html); 
 
        
        $this->_html = preg_replace('/\\s+(<\\/?(?:area|base(?:font)?|blockquote|body' 
            .'|caption|center|cite|col(?:group)?|dd|dir|div|dl|dt|fieldset|form' 
            .'|frame(?:set)?|h[1-6]|head|hr|html|legend|li|link|map|menu|meta' 
            .'|ol|opt(?:group|ion)|p|param|t(?:able|body|head|d|h||r|foot|itle)' 
            .'|ul)\\b[^>]*>)/i', '$1', $this->_html); 
 
        
        $this->_html = preg_replace( 
            '/>(\\s(?:\\s*))?([^<]+)(\\s(?:\s*))?</' 
            ,'>$1$2$3<' 
            ,$this->_html); 
 
        
        $this->_html = preg_replace('/(<[a-z\\-]+)\\s+([^>]+>)/i', "$1\n$2", $this->_html); 
 
        
        $this->_html = str_replace( 
            array_keys($this->_placeholders) 
            ,array_values($this->_placeholders) 
            ,$this->_html 
        ); 
        
        $this->_html = str_replace( 
            array_keys($this->_placeholders) 
            ,array_values($this->_placeholders) 
            ,$this->_html 
        ); 
        return $this->_html; 
    } 
 
    protected function _commentCB($m) 
    { 
        return (0 === strpos($m[1], '[') || false !== strpos($m[1], '<![')) 
            ? $m[0] 
            : ''; 
    } 
 
    protected function _removePreCB($m) 
    { 
        return $this->_reservePlace("<pre{$m[1]}"); 
    } 
 
    protected function _reservePlace($content) 
    { 
        $placeholder = '%' . $this->_replacementHash . count($this->_placeholders) . '%'; 
        $this->_placeholders[$placeholder] = $content; 
        return $placeholder; 
    } 
 
    protected function _removeTextareaCB($m) 
    { 
        return $this->_reservePlace("<textarea{$m[1]}"); 
    } 
 
    protected function _removeStyleCB($m) 
    { 
        $openStyle = "<style{$m[1]}"; 
        $css = $m[2]; 
        
        $css = preg_replace('/(?:^\\s*<!--|-->\\s*$)/', '', $css); 
 
        
        $css = $this->_removeCdata($css); 
 
        
        $minifier = $this->_cssMinifier 
            ? $this->_cssMinifier 
            : 'trim'; 
        $css = call_user_func($minifier, $css); 
 
        return $this->_reservePlace($this->_needsCdata($css) 
                ? "{$openStyle}/*<![CDATA[*/{$css}/*]]>*/</style>" 
                : "{$openStyle}{$css}</style>" 
        ); 
    } 
 
    protected function _removeCdata($str) 
    { 
        return (false !== strpos($str, '<![CDATA[')) 
            ? str_replace(array('<![CDATA[', ']]>'), '', $str) 
            : $str; 
    } 
 
    protected function _needsCdata($str) 
    { 
        return ($this->_isXhtml && preg_match('/(?:[<&]|\\-\\-|\\]\\]>)/', $str)); 
    } 
 
    protected function _removeScriptCB($m) 
    { 
        $openScript = "<script{$m[2]}"; 
        $js = $m[3]; 
 
        
        $ws1 = ($m[1] === '') ? '' : ' '; 
        $ws2 = ($m[4] === '') ? '' : ' '; 
 
        
        if ($this->_jsCleanComments) { 
            $js = preg_replace('/(?:^\\s*<!--\\s*|\\s*(?:\\/\\/)?\\s*-->\\s*$)/', '', $js); 
        } 
 
        
        $js = $this->_removeCdata($js); 
 
        
        $minifier = $this->_jsMinifier 
            ? $this->_jsMinifier 
            : 'trim'; 
        $js = call_user_func($minifier, $js); 
 
        return $this->_reservePlace($this->_needsCdata($js) 
                ? "{$ws1}{$openScript}/*<![CDATA[*/{$js}/*]]>*/</script>{$ws2}" 
                : "{$ws1}{$openScript}{$js}</script>{$ws2}" 
        ); 
    } 
} 
 
 
    class xMinify_CSS 
    { 
 
        public static function minify($css, $options = array()) 
        { 
            $options = array_merge(array( 
                'compress' => true, 
                'removeCharsets' => true, 
                'preserveComments' => true, 
                'currentDir' => null, 
                'docRoot' => $_SERVER['DOCUMENT_ROOT'], 
                'prependRelativePath' => null, 
                'symlinks' => array(), 
            ), $options); 
 
            if ($options['removeCharsets']) { 
                $css = preg_replace('/@charset[^;]+;\\s*/', '', $css); 
            } 
            if ($options['compress']) { 
                if (!$options['preserveComments']) { 
                    $css = xMinify_CSS_Compressor::process($css, $options); 
                } else { 
                    $css = xMinify_CommentPreserver::process( 
                        $css 
                        , array('xMinify_CSS_Compressor', 'process') 
                        , array($options) 
                    ); 
                } 
            } 
            if (!$options['currentDir'] && !$options['prependRelativePath']) { 
                return $css; 
            } 
            if ($options['currentDir']) { 
                return xMinify_CSS_UriRewriter::rewrite( 
                    $css 
                    , $options['currentDir'] 
                    , $options['docRoot'] 
                    , $options['symlinks'] 
                ); 
            } else { 
                return xMinify_CSS_UriRewriter::prepend( 
                    $css 
                    , $options['prependRelativePath'] 
                ); 
            } 
        } 
    } 
 
    class xJSMin 
    { 
        const ORD_LF = 10; 
        const ORD_SPACE = 32; 
        const ACTION_KEEP_A = 1; 
        const ACTION_DELETE_A = 2; 
        const ACTION_DELETE_A_B = 3; 
 
        protected $a = "\n"; 
        protected $b = ''; 
        protected $input = ''; 
        protected $inputIndex = 0; 
        protected $inputLength = 0; 
        protected $lookAhead = null; 
        protected $output = ''; 
        protected $lastByteOut = ''; 
        protected $keptComment = ''; 
 
        public function __construct($input) 
        { 
            $this->input = $input; 
        } 
 
        public static function minify($js) 
        { 
            $jsmin = new xJSMin($js); 
            return $jsmin->min(); 
        } 
 
        public function min() 
        { 
            if ($this->output !== '') { 
                return $this->output; 
            } 
 
            $mbIntEnc = null; 
            if (function_exists('mb_strlen') && ((int)ini_get('mbstring.func_overload') & 2)) { 
                $mbIntEnc = mb_internal_encoding(); 
                mb_internal_encoding('8bit'); 
            } 
            $this->input = str_replace("\r\n", "\n", $this->input); 
            $this->inputLength = strlen($this->input); 
 
            $this->action(self::ACTION_DELETE_A_B); 
 
            while ($this->a !== null) { 
                
                $command = self::ACTION_KEEP_A; 
                if ($this->a === ' ') { 
                    if (($this->lastByteOut === '+' || $this->lastByteOut === '-') 
                        && ($this->b === $this->lastByteOut) 
                    ) { 
                        
                        
                    } elseif (!$this->isAlphaNum($this->b)) { 
                        $command = self::ACTION_DELETE_A; 
                    } 
                } elseif ($this->a === "\n") { 
                    if ($this->b === ' ') { 
                        $command = self::ACTION_DELETE_A_B; 
 
                        
                        
                    } elseif ($this->b === null 
                        || (false === strpos('{[(+-!~', $this->b) 
                            && !$this->isAlphaNum($this->b)) 
                    ) { 
                        $command = self::ACTION_DELETE_A; 
                    } 
                } elseif (!$this->isAlphaNum($this->a)) { 
                    if ($this->b === ' ' 
                        || ($this->b === "\n" 
                            && (false === strpos('}])+-"\'', $this->a))) 
                    ) { 
                        $command = self::ACTION_DELETE_A_B; 
                    } 
                } 
                $this->action($command); 
            } 
            $this->output = trim($this->output); 
 
            if ($mbIntEnc !== null) { 
                mb_internal_encoding($mbIntEnc); 
            } 
            return $this->output; 
        } 
 
        protected function action($command) 
        { 
            
            if ($command === self::ACTION_DELETE_A_B 
                && $this->b === ' ' 
                && ($this->a === '+' || $this->a === '-') 
            ) { 
                
                
                if ($this->input[$this->inputIndex] === $this->a) { 
                    
                    $command = self::ACTION_KEEP_A; 
                } 
            } 
 
            switch ($command) { 
                case self::ACTION_KEEP_A: 
                    $this->output .= $this->a; 
 
                    if ($this->keptComment) { 
                        $this->output = rtrim($this->output, "\n"); 
                        $this->output .= $this->keptComment; 
                        $this->keptComment = ''; 
                    } 
 
                    $this->lastByteOut = $this->a; 
 
                
                case self::ACTION_DELETE_A: 
                    $this->a = $this->b; 
                    if ($this->a === "'" || $this->a === '"') { 
                        $str = $this->a; 
                        for (; ;) { 
                            $this->output .= $this->a; 
                            $this->lastByteOut = $this->a; 
 
                            $this->a = $this->get(); 
                            if ($this->a === $this->b) { 
                                break; 
                            } 
                            if ($this->isEOF($this->a)) { 
                                throw new xJSMin_UnterminatedStringException( 
                                    "xJSMin: Unterminated String at byte {$this->inputIndex}: {$str}"); 
                            } 
                            $str .= $this->a; 
                            if ($this->a === '\\') { 
                                $this->output .= $this->a; 
                                $this->lastByteOut = $this->a; 
 
                                $this->a = $this->get(); 
                                $str .= $this->a; 
                            } 
                        } 
                    } 
 
                
                case self::ACTION_DELETE_A_B: 
                    $this->b = $this->next(); 
                    if ($this->b === '/' && $this->isRegexpLiteral()) { 
                        $this->output .= $this->a . $this->b; 
                        $pattern = '/'; 
                        for (; ;) { 
                            $this->a = $this->get(); 
                            $pattern .= $this->a; 
                            if ($this->a === '[') { 
                                for (; ;) { 
                                    $this->output .= $this->a; 
                                    $this->a = $this->get(); 
                                    $pattern .= $this->a; 
                                    if ($this->a === ']') { 
                                        break; 
                                    } 
                                    if ($this->a === '\\') { 
                                        $this->output .= $this->a; 
                                        $this->a = $this->get(); 
                                        $pattern .= $this->a; 
                                    } 
                                    if ($this->isEOF($this->a)) { 
                                        throw new xJSMin_UnterminatedRegExpException( 
                                            "xJSMin: Unterminated set in RegExp at byte " 
                                            . $this->inputIndex . ": {$pattern}"); 
                                    } 
                                } 
                            } 
 
                            if ($this->a === '/') { 
                                break; 
                            } elseif ($this->a === '\\') { 
                                $this->output .= $this->a; 
                                $this->a = $this->get(); 
                                $pattern .= $this->a; 
                            } elseif ($this->isEOF($this->a)) { 
                                throw new xJSMin_UnterminatedRegExpException( 
                                    "xJSMin: Unterminated RegExp at byte {$this->inputIndex}: {$pattern}"); 
                            } 
                            $this->output .= $this->a; 
                            $this->lastByteOut = $this->a; 
                        } 
                        $this->b = $this->next(); 
                    } 
                
            } 
        } 
 
        protected function get() 
        { 
            $c = $this->lookAhead; 
            $this->lookAhead = null; 
            if ($c === null) { 
                
                if ($this->inputIndex < $this->inputLength) { 
                    $c = $this->input[$this->inputIndex]; 
                    $this->inputIndex += 1; 
                } else { 
                    $c = null; 
                } 
            } 
            if (ord($c) >= self::ORD_SPACE || $c === "\n" || $c === null) { 
                return $c; 
            } 
            if ($c === "\r") { 
                return "\n"; 
            } 
            return ' '; 
        } 
 
        protected function isEOF($a) 
        { 
            return ord($a) <= self::ORD_LF; 
        } 
 
 
        protected function next() 
        { 
            $get = $this->get(); 
            if ($get === '/') { 
                switch ($this->peek()) { 
                    case '/': 
                        $this->consumeSingleLineComment(); 
                        $get = "\n"; 
                        break; 
                    case '*': 
                        $this->consumeMultipleLineComment(); 
                        $get = ' '; 
                        break; 
                } 
            } 
            return $get; 
        } 
 
        protected function peek() 
        { 
            $this->lookAhead = $this->get(); 
            return $this->lookAhead; 
        } 
 
        protected function consumeSingleLineComment() 
        { 
            $comment = ''; 
            while (true) { 
                $get = $this->get(); 
                $comment .= $get; 
                if (ord($get) <= self::ORD_LF) { 
                    
                    if (preg_match('/^\\/@(?:cc_on|if|elif|else|end)\\b/', $comment)) { 
                        $this->keptComment .= "/{$comment}"; 
                    } 
                    return; 
                } 
            } 
        } 
 
 
        protected function consumeMultipleLineComment() 
        { 
            $this->get(); 
            $comment = ''; 
            for (; ;) { 
                $get = $this->get(); 
                if ($get === '*') { 
                    if ($this->peek() === '/') { 
                        $this->get(); 
                        if (0 === strpos($comment, '!mx')) { 
                            
                            if (!$this->keptComment) { 
                                
                                $this->keptComment = "\n"; 
                            } 
                            $this->keptComment .= "/*!" . substr($comment, 1) . "*/\n"; 
                        } else if (preg_match('/^@(?:cc_on|if|elif|else|end)\\b/', $comment)) { 
                            
                            $this->keptComment .= "/*{$comment}*/"; 
                        } 
                        return; 
                    } 
                } elseif ($get === null) { 
                    throw new xJSMin_UnterminatedCommentException( 
                        "xJSMin: Unterminated comment at byte {$this->inputIndex}: /*{$comment}"); 
                } 
                $comment .= $get; 
            } 
        } 
 
 
        protected function isRegexpLiteral() 
        { 
            if (false !== strpos("(,=:[!&|?+-~*{;", $this->a)) { 
                
                return true; 
            } 
            if ($this->a === ' ' || $this->a === "\n") { 
                $length = strlen($this->output); 
                if ($length < 2) { 
                    return true; 
                } 
                
                if (preg_match('/(?:case|else|in|return|typeof)$/', $this->output, $m)) { 
                    if ($this->output === $m[0]) { 
                        return true; 
                    } 
                    
                    $charBeforeKeyword = substr($this->output, $length - strlen($m[0]) - 1, 1); 
                    if (!$this->isAlphaNum($charBeforeKeyword)) { 
                        return true; 
                    } 
                } 
            } 
            return false; 
        } 
 
 
        protected function isAlphaNum($c) 
        { 
            return (preg_match('/^[a-z0-9A-Z_\\$\\\\]$/', $c) || ord($c) > 126); 
        } 
    } 
 
    class xJSMin_UnterminatedStringException extends Exception 
    { 
} 
    class xJSMin_UnterminatedCommentException extends Exception 
    { 
    } 
    class xJSMin_UnterminatedRegExpException extends Exception 
    { 
    } 
 
    class xMinify_CommentPreserver 
    { 
 
 
        public static $prepend = "\n"; 
 
 
        public static $append = "\n"; 
 
        public static function process($content, $processor, $args = array()) 
        { 
            $ret = ''; 
            while (true) { 
                list($beforeComment, $comment, $afterComment) = self::_nextComment($content); 
                if ('' !== $beforeComment) { 
                    $callArgs = $args; 
                    array_unshift($callArgs, $beforeComment); 
                    $ret .= call_user_func_array($processor, $callArgs); 
                } 
                if (false === $comment) { 
                    break; 
                } 
                $ret .= $comment; 
                $content = $afterComment; 
            } 
            return $ret; 
        } 
 
        private static function _nextComment($in) 
        { 
            if ( 
                false === ($start = strpos($in, '/*!')) 
                || false === ($end = strpos($in, '*/', $start + 3)) 
            ) { 
                return array($in, false, false); 
            } 
            $ret = array( 
                substr($in, 0, $start) 
            , self::$prepend . '/*!' . substr($in, $start + 3, $end - $start - 1) . self::$append 
            ); 
            $endChars = (strlen($in) - $end - 2); 
            $ret[] = (0 === $endChars) 
                ? '' 
                : substr($in, -$endChars); 
            return $ret; 
        } 
    } 
 
    class xMinify_CSS_Compressor 
    { 
 
 
        protected $_options = null; 
 
        protected $_inHack = false; 
 
 
        private function __construct($options) 
        { 
            $this->_options = $options; 
        } 
 
 
        public static function process($css, $options = array()) 
        { 
            $obj = new xMinify_CSS_Compressor($options); 
            return $obj->_process($css); 
        } 
 
        protected function _process($css) 
        { 
            $css = str_replace("\r\n", "\n", $css); 
 
            
            
            $css = preg_replace('@>/\\*\\s*\\*/@', '>/*keep*/', $css); 
 
            
            
            $css = preg_replace('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $css); 
            $css = preg_replace('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $css); 
 
            
            $css = preg_replace_callback('@\\s*/\\*([\\s\\S]*?)\\*/\\s*@' 
                , array($this, '_commentCB'), $css); 
 
            
            $css = preg_replace('/\\s*{\\s*/', '{', $css); 
            $css = preg_replace('/;?\\s*}\\s*/', '}', $css); 
 
            
            $css = preg_replace('/\\s*;\\s*/', ';', $css); 
 
            
            
 
            $css = preg_replace('/ 
                url\\( 
                \\s* 
                ([^\\)]+?) 
                \\s* 
                \\) 
            /x', 'url($1)', $css); 
 
            
            $css = preg_replace('/ 
                \\s* 
                ([{;]) 
                \\s* 
                ([\\*_]?[\\w\\-]+) 
                \\s* 
                : 
                \\s* 
                (\\b|[#\'"-]) 
            /x', '$1$2:$3', $css); 
 
            
	        /* Hangs for some CSS (test.css) 
            $css = preg_replace_callback('/ 
                (?: 
                    \\s* 
                    [^~>+,\\s]+ 
                    \\s* 
                    [,>+~] 
                )+ 
                \\s* 
                [^~>+,\\s]+ 
                { 
            /x' 
                , array($this, '_selectorsCB'), $css); 
	        */ 
 
            
            $css = preg_replace('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i' 
                , '$1#$2$3$4$5', $css); 
 
            
            $css = preg_replace_callback('/font-family:([^;}]+)([;}])/' 
                , array($this, '_fontFamilyCB'), $css); 
 
            $css = preg_replace('/@import\\s+url/', '@import url', $css); 
 
            
            $css = preg_replace('/[ \\t]*\\n+\\s*/', "\n", $css); 
 
            
            $css = preg_replace('/([\\w#\\.\\*]+)\\s+([\\w#\\.\\*]+){/', "$1\n$2{", $css); 
 
            
            $css = preg_replace('/ 
            ((?:padding|margin|border|outline):\\d+(?:px|em)?) 
            \\s+ 
            /x' 
                , "$1\n", $css); 
 
            
            $css = preg_replace('/:first-l(etter|ine)\\{/', ':first-l$1 {', $css); 
 
            return trim($css); 
        } 
 
        protected function _selectorsCB($m) 
        { 
            
            return preg_replace('/\\s*([,>+~])\\s*/', '$1', $m[0]); 
        } 
 
        protected function _commentCB($m) 
        { 
            $hasSurroundingWs = (trim($m[0]) !== $m[1]); 
            $m = $m[1]; 
            
            
            if ($m === 'keep') { 
                return '/**/'; 
            } 
            if ($m === '" "') { 
                
                return '/*" "*/'; 
            } 
            if (preg_match('@";\\}\\s*\\}/\\*\\s+@', $m)) { 
                
                return '/*";}}/* */'; 
            } 
            if ($this->_inHack) { 
                
                if (preg_match('@ 
                    ^/ 
                    \\s* 
                    (\\S[\\s\\S]+?) 
                    \\s* 
                    /\\* 
                @x', $m, $n)) { 
                    
                    $this->_inHack = false; 
                    return "/*/{$n[1]}/**/"; 
                } 
            } 
            if (substr($m, -1) === '\\') { 
                
                $this->_inHack = true; 
                return '/*\\*/'; 
            } 
            if ($m !== '' && $m[0] === '/') { 
                
                $this->_inHack = true; 
                return '/*/*/'; 
            } 
            if ($this->_inHack) { 
                
                $this->_inHack = false; 
                return '/**/'; 
            } 
            
            
            return $hasSurroundingWs 
                ? ' ' 
                : ''; 
        } 
 
        protected function _fontFamilyCB($m) 
        { 
            
            $pieces = preg_split('/(\'[^\']+\'|"[^"]+")/', $m[1], null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY); 
            $out = 'font-family:'; 
            while (null !== ($piece = array_shift($pieces))) { 
                if ($piece[0] !== '"' && $piece[0] !== "'") { 
                    $piece = preg_replace('/\\s+/', ' ', $piece); 
                    $piece = preg_replace('/\\s?,\\s?/', ',', $piece); 
                } 
                $out .= $piece; 
            } 
            return $out . $m[2]; 
        } 
    } 
 
 
    class xMinify_CSS_UriRewriter 
    { 
 
        /** 
         * rewrite() and rewriteRelative() append debugging information here 
         * 
         * @var string 
         */ 
        public static $debugText = ''; 
 
        /** 
         * In CSS content, rewrite file relative URIs as root relative 
         * 
         * @param string $css 
         * 
         * @param string $currentDir The directory of the current CSS file. 
         * 
         * @param string $docRoot The document root of the web site in which 
         * the CSS file resides (default = $_SERVER['DOCUMENT_ROOT']). 
         * 
         * @param array $symlinks (default = array()) If the CSS file is stored in 
         * a symlink-ed directory, provide an array of link paths to 
         * target paths, where the link paths are within the document root. Because 
         * paths need to be normalized for this to work, use "//" to substitute 
         * the doc root in the link paths (the array keys). E.g.: 
         * <code> 
         * array('//symlink' => '/real/target/path') 
         * array('//static' => 'D:\\staticStorage')  
         * </code> 
         * 
         * @return string 
         */ 
        public static function rewrite($css, $currentDir, $docRoot = null, $symlinks = array()) 
        { 
            self::$_docRoot = self::_realpath( 
                $docRoot ? $docRoot : $_SERVER['DOCUMENT_ROOT'] 
            ); 
            self::$_currentDir = self::_realpath($currentDir); 
            self::$_symlinks = array(); 
 
            
            foreach ($symlinks as $link => $target) { 
                $link = ($link === '//') 
                    ? self::$_docRoot 
                    : str_replace('//', self::$_docRoot . '/', $link); 
                $link = strtr($link, '/', DIRECTORY_SEPARATOR); 
                self::$_symlinks[$link] = self::_realpath($target); 
            } 
 
            self::$debugText .= "docRoot    : " . self::$_docRoot . "\n" 
                . "currentDir : " . self::$_currentDir . "\n"; 
            if (self::$_symlinks) { 
                self::$debugText .= "symlinks : " . var_export(self::$_symlinks, 1) . "\n"; 
            } 
            self::$debugText .= "\n"; 
 
            $css = self::_trimUrls($css); 
 
            
            $css = preg_replace_callback('/@import\\s+([\'"])(.*?)[\'"]/' 
                , array(self::$className, '_processUriCB'), $css); 
            $css = preg_replace_callback('/url\\(\\s*([^\\)\\s]+)\\s*\\)/' 
                , array(self::$className, '_processUriCB'), $css); 
 
            return $css; 
        } 
 
        /** 
         * In CSS content, prepend a path to relative URIs 
         * 
         * @param string $css 
         * 
         * @param string $path The path to prepend. 
         * 
         * @return string 
         */ 
        public static function prepend($css, $path) 
        { 
            self::$_prependPath = $path; 
 
            $css = self::_trimUrls($css); 
 
            
            $css = preg_replace_callback('/@import\\s+([\'"])(.*?)[\'"]/' 
                , array(self::$className, '_processUriCB'), $css); 
            $css = preg_replace_callback('/url\\(\\s*([^\\)\\s]+)\\s*\\)/' 
                , array(self::$className, '_processUriCB'), $css); 
 
            self::$_prependPath = null; 
            return $css; 
        } 
 
        /** 
         * Get a root relative URI from a file relative URI 
         * 
         * <code> 
         * Minify_CSS_UriRewriter::rewriteRelative( 
         *       '../img/hello.gif' 
         *     , '/home/user/www/css'  
         *     , '/home/user/www'      
         * ); 
         * 
         * 
         * 
         * Minify_CSS_UriRewriter::rewriteRelative( 
         *       'hello.gif' 
         *     , '/var/staticFiles/theme' 
         *     , '/home/user/www' 
         *     , array('/home/user/www/static' => '/var/staticFiles') 
         * ); 
         * 
         * </code> 
         * 
         * @param string $uri file relative URI 
         * 
         * @param string $realCurrentDir realpath of the current file's directory. 
         * 
         * @param string $realDocRoot realpath of the site document root. 
         * 
         * @param array $symlinks (default = array()) If the file is stored in 
         * a symlink-ed directory, provide an array of link paths to 
         * real target paths, where the link paths "appear" to be within the document 
         * root. E.g.: 
         * <code> 
         * array('/home/foo/www/not/real/path' => '/real/target/path') 
         * array('C:\\htdocs\\not\\real' => 'D:\\real\\target\\path')  
         * </code> 
         * 
         * @return string 
         */ 
        public static function rewriteRelative($uri, $realCurrentDir, $realDocRoot, $symlinks = array()) 
        { 
            
            $path = strtr($realCurrentDir, '/', DIRECTORY_SEPARATOR) 
                . DIRECTORY_SEPARATOR . strtr($uri, '/', DIRECTORY_SEPARATOR); 
 
            self::$debugText .= "file-relative URI  : {$uri}\n" 
                . "path prepended     : {$path}\n"; 
 
            
            foreach ($symlinks as $link => $target) { 
                if (0 === strpos($path, $target)) { 
                    
                    $path = $link . substr($path, strlen($target)); 
 
                    self::$debugText .= "symlink unresolved : {$path}\n"; 
 
                    break; 
                } 
            } 
            
            $path = substr($path, strlen($realDocRoot)); 
 
            self::$debugText .= "docroot stripped   : {$path}\n"; 
 
            
            $uri = strtr($path, '/\\', '//'); 
            $uri = self::removeDots($uri); 
 
            self::$debugText .= "traversals removed : {$uri}\n\n"; 
 
            return $uri; 
        } 
 
        /** 
         * Remove instances of "./" and "../" where possible from a root-relative URI 
         * 
         * @param string $uri 
         * 
         * @return string 
         */ 
        public static function removeDots($uri) 
        { 
            $uri = str_replace('/./', '/', $uri); 
            
            do { 
                $uri = preg_replace('@/[^/]+/\\.\\./@', '/', $uri, 1, $changed); 
            } while ($changed); 
            return $uri; 
        } 
 
        /** 
         * Defines which class to call as part of callbacks, change this 
         * if you extend Minify_CSS_UriRewriter 
         * 
         * @var string 
         */ 
        protected static $className = 'xMinify_CSS_UriRewriter'; 
 
        /** 
         * Get realpath with any trailing slash removed. If realpath() fails, 
         * just remove the trailing slash. 
         * 
         * @param string $path 
         * 
         * @return mixed path with no trailing slash 
         */ 
        protected static function _realpath($path) 
        { 
            $realPath = realpath($path); 
            if ($realPath !== false) { 
                $path = $realPath; 
            } 
            return rtrim($path, '/\\'); 
        } 
 
        /** 
         * Directory of this stylesheet 
         * 
         * @var string 
         */ 
        private static $_currentDir = ''; 
 
        /** 
         * DOC_ROOT 
         * 
         * @var string 
         */ 
        private static $_docRoot = ''; 
 
        /** 
         * directory replacements to map symlink targets back to their 
         * source (within the document root) E.g. '/var/www/symlink' => '/var/realpath' 
         * 
         * @var array 
         */ 
        private static $_symlinks = array(); 
 
        /** 
         * Path to prepend 
         * 
         * @var string 
         */ 
        private static $_prependPath = null; 
 
        /** 
         * @param string $css 
         * 
         * @return string 
         */ 
        private static function _trimUrls($css) 
        { 
            return preg_replace('/ 
            url\\(      # url( 
            \\s* 
            ([^\\)]+?)  # 1 = URI (assuming does not contain ")") 
            \\s* 
            \\)         # ) 
        /x', 'url($1)', $css); 
        } 
 
        /** 
         * @param array $m 
         * 
         * @return string 
         */ 
        private static function _processUriCB($m) 
        { 
            
            $isImport = ($m[0][0] === '@'); 
            
            if ($isImport) { 
                $quoteChar = $m[1]; 
                $uri = $m[2]; 
            } else { 
                
                $quoteChar = ($m[1][0] === "'" || $m[1][0] === '"') 
                    ? $m[1][0] 
                    : ''; 
                $uri = ($quoteChar === '') 
                    ? $m[1] 
                    : substr($m[1], 1, strlen($m[1]) - 2); 
            } 
            
            if ('/' !== $uri[0]                  
                && false === strpos($uri, '//')  
                && 0 !== strpos($uri, 'data:')   
            ) { 
                
                if (self::$_prependPath === null) { 
                    $uri = self::rewriteRelative($uri, self::$_currentDir, self::$_docRoot, self::$_symlinks); 
                } else { 
                    
                    $pre = self::$_prependPath; 
                    if ($uri[0]=='/') { 
                        $pre = substr($pre,0, strpos($pre, '/', strpos($pre, '//')+2)); 
                        $uri = substr($uri, 1); 
                    } else 
                    while (@$uri[0]=='.') { 
                        if (@$uri[1]=='.') $pre = substr($pre,0, strrpos($pre, '/')); 
                        $uri = substr($uri, strpos($uri, '/')+1); 
                    } 
                    $uri = $pre.'/'.$uri; 
                    
                    /* 
                    $uri = self::$_prependPath . $uri; 
                    if ($uri[0] === '/') { 
                        $root = ''; 
                        $rootRelative = $uri; 
                        $uri = $root . self::removeDots($rootRelative); 
                    } elseif (preg_match('@^((https?\:)?//([^/]+))/@', $uri, $m) && (false !== strpos($m[3], '.'))) { 
                        $root = $m[1]; 
                        $rootRelative = substr($uri, strlen($root)); 
                        $uri = $root . self::removeDots($rootRelative); 
                    } 
                    */ 
                } 
            } 
            return $isImport 
                ? "@import {$quoteChar}{$uri}{$quoteChar}" 
                : "url({$quoteChar}{$uri}{$quoteChar})"; 
        } 
    } 