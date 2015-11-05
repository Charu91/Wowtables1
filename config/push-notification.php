<?php

return array(

    'appNameIOS'     => array(
        'environment' =>'development',
        'certificate' =>'/path/to/certificate.pem',
        'passPhrase'  =>'password',
        'service'     =>'apns'
    ),
    'appNameAndroid' => array(
        'environment' =>'production',
        'apiKey'      =>'AIzaSyB15jzRtzrOLgerwEm9C7pVf9SEP2MDv1k',
        'service'     =>'gcm'
    )

);