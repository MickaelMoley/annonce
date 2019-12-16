<?php


namespace App\Services;


class XmlReader
{

    public function execute($link)
    {
        $this->readXMLFrom($link);

    }

    private function readXMLFrom($link)
    {

        $xmlfile    = file_get_contents($link);
        $this->JSON_file = json_encode(simplexml_load_string($xmlfile));
    }
}