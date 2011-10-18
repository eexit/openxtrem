<?php

namespace Icone\Sysd\Soap\Client\Cli;

use Symfony\Component\Console\Application;

use Icone\Sysd\Soap\Client\Cli\Command;

/**
 * Icone\Sysd\Soap\Client\Cli\Client
 * 
 * @category Icone
 * @package Icone\Sysd
 * @subpackage Soap\Client\Cli
 * @copyright Copyright (c) 2011, Joris Berthelot
 * @author Joris Berthelot <joris.berthelot@gmail.com>
 */
class Client extends Application
{
    /**
     * Class constructor
     */
    public function __construct($debug = false)
    {
        parent::__construct('PHP-CLI SOAP Client application developped by Joris Berthelot (c) 2011', '1.00-DEV');
        
        if ($debug) {
            $this->setCatchExceptions(false);
        }
        
        // Declares application commands
        $this->addCommands(array(
            new Command\Calculator(),
            new Command\SendEvent()
        ));
    }
}
?>