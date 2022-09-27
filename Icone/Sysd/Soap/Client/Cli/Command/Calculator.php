<?php

namespace Icone\Sysd\Soap\Client\Cli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console;

use Icone\Sysd\Soap\Client\Cli\WebServiceProvider;

/**
 * Icone\Sysd\Soap\Client\Cli\Command\Calculator
 * 
 * @category Icone
 * @package Icone\Sysd
 * @subpackage Soap\Client\Cli\Command
 * @copyright Copyright (c) 2011, Joris Berthelot
 * @author Joris Berthelot
 */
class Calculator extends Console\Command\Command
{
    /**
     * Command declaration
     */
    protected function configure()
    {
        $this->setName('calc')
             ->setDescription('Tests the SOAP server')
             ->addOption('username', null, InputOption::VALUE_OPTIONAL, 'WebService username')
             ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'WebService password')
             ->addOption('operand', null, InputOption::VALUE_REQUIRED, 'Operation ("add" or "substract")')
             ->addArgument('x', InputArgument::REQUIRED, 'First number')
             ->addArgument('y', InputArgument::REQUIRED, 'Second number');
    }
    
    /**
     * Command business code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $input->getOption('username');
        $passwd = $input->getOption('password');
        $op = $input->getOption('operand');
        $x = intval($input->getArgument('x'));
        $y = intval($input->getArgument('y'));
        
        // If the operand is not handled by the WebService
        if (!in_array($op, array('add', 'substract'))) {
            $output->writeln(sprintf('%s<error>Invalid operation "%s"! Use "add" or "substract".</error>%s', PHP_EOL, $op, PHP_EOL));
            exit(1);
        }
        
        try {
            
            // Creates a new WebService instance and call the remote method
            if ($user && $passwd) {
                $client = WebServiceProvider::getInstance($user, $passwd);
            } else {
                $client = WebServiceProvider::getInstance();
            }
            
            $response = $client->calculatorAuth($op, $x, $y);
            
            // Prints the result
            $output->writeln(sprintf('%s%d %sed to %d = <info>%s</info>%s', PHP_EOL, $y, $op, $x, intval($response), PHP_EOL));
            
        } catch (\SoapFault $e) {
            $output->writeln(sprintf('%s<error>An error occured!</error>%s', PHP_EOL, PHP_EOL));
            $output->writeln(sprintf('Error message: %s%s', $e->getMessage(), PHP_EOL));
            exit(1);
        }
    }
}
?>
