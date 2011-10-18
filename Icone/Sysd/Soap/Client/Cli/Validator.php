<?php

namespace Icone\Sysd\Soap\Client\Cli;

/**
 * Icone\Sysd\Soap\Client\Cli\Validator
 * 
 * @category Icone
 * @package Icone\Sysd
 * @subpackage Soap\Client\Cli
 * @copyright Copyright (c) 2011, Joris Berthelot
 * @author Joris Berthelot <joris.berthelot@gmail.com>
 */
class Validator
{
    /**
     * DOM Document
     */
    protected $_doc;
    
    /**
     * Class constructor
     */
    public function __construct($xml)
    {
        if (!is_file($xml)) {
            return;
        }
        
        $this->_doc = new \DOMDocument();
        $this->_doc->load($xml);
    }
    
    /**
     * Checkes if the loaded XML document is valid
     */
    public function isValid($xsd)
    {
        if (!is_file($xsd)) {
            throw new \Exception('File not found!');
        }
        
        if (!$this->_doc instanceof \DOMDocument) {
            throw new \Exception('No XML file loaded!');
        }
        
        return $this->_doc->schemaValidate($xsd);
    }
}
?>