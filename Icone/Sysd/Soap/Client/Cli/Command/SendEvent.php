<?php

namespace Icone\Sysd\Soap\Client\Cli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;

use Icone\Sysd\Soap\Client\Cli\Validator;
use Icone\Sysd\Soap\Client\Cli\WebServiceProvider;

/**
 * Icone\Sysd\Soap\Client\Cli\Command\SendEvent
 * 
 * @category Icone
 * @package Icone\Sysd
 * @subpackage Soap\Client\Cli\Command
 * @copyright Copyright (c) 2011, Joris Berthelot
 * @author Joris Berthelot
 */
class SendEvent extends Console\Command\Command
{
    /**
     * Command declaration
     */
    protected function configure()
    {
        $this->setName('send:event')
             ->setDescription('Sends an event')
             ->addOption('username', null, InputOption::VALUE_OPTIONAL, 'WebService username')
             ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'WebService password')
             ->addArgument('eventfile', InputArgument::REQUIRED, 'XML request event file')
             ->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Saves the response as XML file')
             ->addOption('schema', null, InputOption::VALUE_OPTIONAL, 'XSD schema validation');
    }
    
    /**
     * Command business code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $input->getOption('username');
        $passwd = $input->getOption('password');
        $event = $input->getArgument('eventfile');
        $out = $input->getOption('output');
        $xsd = $input->getOption('schema');
        
        // The XML input file doesn't exist
        if (!is_file($event)) {
            $output->writeln(sprintf('%s<error>Given argument is not a file!</error>%s', PHP_EOL, PHP_EOL));
            exit(1);
        }
        
        // The file isn't a XML file
        if ('.xml' != strtolower(substr($event, strrpos($event, '.')))) {
            $output->writeln(sprintf('%s<error>Given file argument is not a XML file!</error>%s', PHP_EOL, PHP_EOL));
            exit(1);
        }
        
        // If specified, the output file dir doesn't exist
        if ($out && !is_dir(dirname($out))) {
            $output->writeln(sprintf('%s<error>The path "%s" does not exist!</error>%s', PHP_EOL, dirname($out), PHP_EOL));
            exit(1);
        }
        
        // If the validation is requested
        if ($xsd) {
            
            // If the validation file doesn't exist
            if (!is_file($xsd)) {
                $output->writeln(sprintf('%s<error>Given option schema is not a file!</error>%s', PHP_EOL, PHP_EOL));
                exit(1);
            }
            
            // Tries to validate the XML input
            $validator = new Validator($event);
            
            // If the validation fails
            if (!$validator->isValid($xsd)) {
                $output->writeln(sprintf('%s<error>XML event input %s is not valid against schema %s!</error>%s', PHP_EOL, $event, $xsd, PHP_EOL));
                exit(1);
            }
        }
        
        try {
            
            // Creates a new WebService instance and call the remote method
            if ($user && $passwd) {
                $client = WebServiceProvider::getInstance($user, $passwd);
            } else {
                $client = WebServiceProvider::getInstance();
            }
            
            $response = $client->event(utf8_encode(file_get_contents($event)));
            
            // Creates a new DOMDocument file from the WebService response
            $doc = new \DOMDocument();
            $doc->loadXML($response);
            
            // Saves the responses as file is speficied
            if ($out) {
                $doc->formatOutput = true;
                $doc->save($out);
            }
            
            // Creates a new DOMXPath object and registers the right ns
            $xpath = new \DOMXPath($doc);
            $xpath->registerNamespace('hprim', 'http://www.hprim.org/hprimXML');
            
            // Gets the response status
            $status = $xpath->query('hprim:enteteMessageAcquittement/@statut')->item(0)->nodeValue;
            
            // Message coloration depending of the status
            if ("OK" == trim($status)) {
                $output->writeln(sprintf('%sResponse status: <info>%s</info>', PHP_EOL, $status));
            } else {
                $output->writeln(sprintf('%sResponse status: <error>%s</error>', PHP_EOL, strtoupper($status)));
            }
            
            // Gets the response code
            $code = $xpath->query('hprim:enteteMessageAcquittement/hprim:observation/hprim:code')->item(0)->nodeValue;
            $output->writeln(sprintf('Response code: %s', $code));
            
            // Gets the response label
            $label = $xpath->query('hprim:enteteMessageAcquittement/hprim:observation/hprim:libelle')->item(0)->nodeValue;
            $output->writeln(sprintf('Response label: %s', utf8_decode($label)));
            
            // Gets the response comment
            $comment = $xpath->query('hprim:enteteMessageAcquittement/hprim:observation/hprim:commentaire')->item(0)->nodeValue;
            $output->writeln(sprintf('Response comment: %s', utf8_decode(str_replace(PHP_EOL, null, $comment))));
            
            // Print the saving file confirmation if specified
            if ($out) {
                $output->writeln(sprintf('Response file: %s%s', $out, PHP_EOL));
            } else {
                $output->writeln('');
            }
            
        } catch (\SoapFault $e) {
            $output->writeln(sprintf('%s<error>An error occured!</error>%s', PHP_EOL, PHP_EOL));
            $output->writeln(sprintf('Error message: %s%s', $e->getMessage(), PHP_EOL));
            exit(1);
        } catch (\DOMException $e) {
            $output->writeln(sprintf('%s<error>An error occured with XML file generation!</error>%s', PHP_EOL, PHP_EOL));
            $output->writeln(sprintf('Error message: %s%s', $e->getMessage(), PHP_EOL));
            exit(1);
        }
    }
}
?>
