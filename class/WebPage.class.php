<?php
class WebPage
{
    private $head; //string
    private $title; //string
    private $body; //string

    public function __construct (string $title = null)
    {
        $this->head = "";
        $this->title = $title;
        $this->body = "";
    }
    public function body():string
    {
        return $this->body;
    }
    public function head():string
    {
        return $this->head;
    }
    public function setTitle(string $title):void
    {
        $this->title = $title;
    }
    public function appendToHead(string $content):void
    {
        $this->head.= $content;
    }
    public function appendCss(string $css):void
    {
        $this->head .= "<style>{$css}</style>";
    }
    public function appendCssUrl(string $url):void
    {
        $this->head .= "<link type='text/css' rel='stylesheet' href='{$url}'>";
    }
    public function appendJs(string $js):void
    {
        $this->head .= "<script type='text/javascript'>{$js}</script>";
    }
    public function appendJsUrl(string $url):void
    {
        $this->head .= "<script type='text/javascript' src='$url'>";
    }
    public function appendContent(string $content):void
    {
        $this->body .= $content;
    }
    public function toHTML():string
    {
        if(is_null($this->title))
        {
            throw new exception ('lol ya pas de titre ');
        }
        else{
        $date=$this->getLastModification();
        $html=<<<HTML
        <html>
            
            <head>{$this->head}
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <script src="Crypto.js"></script>
            <title>{$this->title}</title>
            </head>
            <body>
            {$this->body}
            <footer>last modification {$date}</footer>
            </body>
        </html>
HTML;
        }
        return $html;
    }
    public function getLastModification():String
    {
        setlocale(LC_TIME, "fr_FR");
        return strftime( "%D",getlastmod());
      
    }
    public function escapeString(string $string):string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, "utf-8");
    }
}
