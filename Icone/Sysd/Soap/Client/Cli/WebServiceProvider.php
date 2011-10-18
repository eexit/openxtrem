<?php

namespace Icone\Sysd\Soap\Client\Cli;

use Zend\Soap\Client as Zend_Client;

/**
 * Icone\Sysd\Soap\Client\Cli\WebServiceProvider
 * 
 * @category Icone
 * @package Icone\Sysd
 * @subpackage Soap\Client\Cli
 * @copyright Copyright (c) 2011, Joris Berthelot
 * @author Joris Berthelot <joris.berthelot@gmail.com>
 */
class WebServiceProvider
{
    /**
     * Default username
     */
    const USERNAME = 'user';
    
    /**
     * Default password
     */
    const PASSWORD = 'passwd';
    
    /**
     * WebService endpoint
     */
    const URI = 'http://education.openxtrem.com/index.php?login=1&username=%s&password=%s&m=webservices&a=soap_server&wsdl';
    
    public static function getInstance($user = self::USERNAME, $password = self::PASSWORD)
    {
        $wsdl = sprintf(self::URI, $user, $password);
        return new Zend_Client($wsdl, $options = array(
            'soap_version'      => SOAP_1_1,
            //'proxy_host'        => 'wwwcache.univ-lr.fr',
            //'proxy_port'        => 3128,
            'encoding'          => 'UTF-8'
        ));
    }
}
?>