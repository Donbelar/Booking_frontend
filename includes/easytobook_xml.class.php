<?php
class EasytobookXML extends Easytobook {

    private static $xml;

    private static $header = "<?xml version=\"1.0\"?><soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"><soap:Body>",
            $footer = "</soap:Body></soap:Envelope>";


    protected static function build_request($xml_args, $opts){
        self::$xml = new SimpleXMLElement("<Easytobook />");

        $request = self::$xml->addChild('Request');
        $request->addAttribute('target', parent::$env);

        $authentication = $request->addChild('Authentication');
        $authentication->addAttribute('username', parent::$username);
        $authentication->addAttribute('password', parent::$password);

        foreach ($xml_args as $key => $value) {
            if ($key == 'campaignid'){
                $authentication->addAttribute($key, parent::$campaignid);
                continue;
            }
            if ($key == 'Function') {
                $authentication->addChild($key, $value);
                continue;
            }
            self::iterate_values($request, $key, $value);
        }

        if (isset($opts['plain_xml']) && $opts['plain_xml'] === true) {
            return self::build_xml_document(self::$xml);
        } else {
            return self::$xml;
        }
    }

    protected static function iterate_values($parent, $key, $value){
        if (is_array($value)){
            // array of childs
            if (is_int($key)){
                foreach ($value as $child_key => $child_value) {
                    self::iterate_values($parent, $child_key, $child_value);
                }
            // parent attributes arrays
            } elseif($key == 'attributes') {
                foreach ($value as $attr_key => $attr_value) {
                    $parent->addAttribute($attr_key, $attr_value);
                }
            // hash childs
            } else {
                $new_parent = $parent->addChild($key);
                foreach($value as $child_key => $child_value) {
                    self::iterate_values($new_parent, $child_key, $child_value);
                }
            }
        // simple value
        } else {
            $parent->addChild($key, $value);
        }
    }


    protected static function build_xml_document($xml_obj){
        return self::$header.preg_replace('/\<\?xml\sversion\=\"1\.0\"\?\>/', "", $xml_obj->asXml()).self::$footer;
    }
}
?>