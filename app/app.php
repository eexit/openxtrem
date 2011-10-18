<?php
/*
namespace Sysd\Validator;

require_once __DIR__ . '/../src/Validator.php';

$validator = new Validator($doc = __DIR__ . '/../var/patient.xml');
$result = $validator->isValid($xsd = __DIR__ . '/../xsd/msgEvenementsPatients1053.xsd');

if ($result) {
    echo sprintf('%s%s was successfully validated against %s!%s', PHP_EOL, basename($doc), basename($xsd), PHP_EOL);
}
*/

require_once __DIR__ . '/Loader.php';
$client = new Icone\Sysd\Soap\Client\Cli\Client(true);
$client->run();
?>