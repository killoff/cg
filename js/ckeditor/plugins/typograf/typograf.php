<?php
class typograf
{
    const SERVICE_HOST = 'www.typograf.ru';

    /**
     * @url http://www.typograf.ru/webservice/about/
     * @return string
     */
    public function getConfigXml()
    {
        return '<?xml version="1.0" encoding="windows-1251" ?>
        <preferences>
            <tags delete="0">1</tags>
            <paragraph insert="1">
                <start><![CDATA[<p>]]></start>
                <end><![CDATA[</p>]]></end>
            </paragraph>
            <newline insert="1"><![CDATA[<br />]]></newline>
            <cmsNewLine valid="0" />
            <dos-text delete="0" />
            <nowraped insert="1" nonbsp="0" length="0">
                <start><![CDATA[<nobr>]]></start>
                <end><![CDATA[</nobr>]]></end>
            </nowraped>
            <hanging-punct insert="0" />
            <hanging-line delete="0" />
            <minus-sign><![CDATA[&ndash;]]></minus-sign>
            <hyphen insert="0" length="0" />
            <acronym insert="1"></acronym>
            <symbols type="0" />
            <link target="" class="" />
        </preferences>';
    }

    public function fix($text)
    {
        $fp = @fsockopen(self::SERVICE_HOST,80,$e, $en, 30);
        if ($fp) {
            $config = $this->getConfigXml();
            $data = 'text='.urlencode($text).'&xml='.urlencode($config).'&chr=UTF-8';

            fputs($fp, "POST /webservice/ HTTP/1.1\n");
            fputs($fp, "Host: ".self::SERVICE_HOST."\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
            fputs($fp, "Content-length: " . strlen($data) . "\n");
            fputs($fp, "User-Agent: PHP Script\n");
            fputs($fp, "Connection: close\n\n");
            fputs($fp, $data);
            while(fgets($fp,2048) != "\r\n" && !feof($fp)); // skip Response headers
            $buf  = '';
            while(!feof($fp)) $buf .= fread($fp,2048);
            fclose($fp);
            return $buf;
        }
        else{
            return $text;
        }
    }
}

$t = new typograf();
$text = $_REQUEST['text'];
//$text = String::utf2cp1251($text);
if ($text) {
    echo $t->fix($text);
}
exit;
