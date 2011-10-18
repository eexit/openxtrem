# PHP-SOAP Practical Work #

This repos contains my practical work related to our PHP OpenXtrem conference at Uni.
This work is fully inspired of https://github.com/eexit/ulr-sysd-client I developped 2 days before.

## Requirements ##

 * >= PHP 5.3.3
 * SOAP PHP extension
 * DOM PHP extension

## Tree ##

    app
    ├── Loader.php
    ├── app.php
    └── client (chmod u+x)
    Icone
    └── Sysd
        └── Soap
            └── Client
                └── Cli
                    ├── Client.php
                    ├── Command
                    │   ├── Calculator.php
                    │   └── CreatePatient.php
                    ├── Validator.php
                    └── WebServiceProvider.php
 
## Usage ##

See application help :

    app/client help
    app/client help calc
    app/client help send:event

Testing the WebService :

    app/client calc --operant=add 2 2
    app/client calc --operant=substract 4 2

Sending an XML event to the WebService :

    app/client send:event var/creation.xml

Same query but with XML schema validation :

    app/client send:event --schema=var/xsd/validationschema.xsd var/creation.xml
    
Same query but with XML response file saving :

    app/client send:event --schema=var/xsd/validationschema.xsd --output=var/response.xml var/creation.xml

## Information ##

Copyright (c) 2011, Joris Berthelot

Joris Berthelot <joris.berthelot@gmail.com>

