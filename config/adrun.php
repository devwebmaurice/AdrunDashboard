<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'table' => [
            
        "TBL_ANNONCEUR"                 => "adrun_annonceur",
        "TBL_ANNONCEUR_STU"             => "adrun_annonceur_status",
        "TBL_CIBLAGE"                   => "adrun_ciblage",
        'TBL_CIBLAGE_HAS_COMBINATION'   => 'adrun_ciblage_has_combination',
        "TBL_FORMAT"                    => "adrun_format",
        "TBL_EDITEUR"                   => "adrun_editeur",
        "TBL_PACK"                      => "adrun_pack",
        "TBL_USER"                      => "users",
        'TBL_EDITEUR_HAS_CIBLAGE'       => 'adrun_editeur_has_ciblage',
        'TBL_FORMAT_HAS_TYPE'           => 'adrun_format_has_type',
        'TBL_FORMAT_HAS_TARGET'         => 'adrun_format_has_target',
        'TBL_FORMAT_HAS_METHODE'        => 'adrun_format_has_methode',
        'TBL_PACK_HAS_WEBSITE'          => 'adrun_pack_has_website',
        'campaign'                      => 'adrun_campaign',
        'advertiser'                    => 'adrun_advertiser',
        'editeur'                       => 'adrun_editeur',
        'banner'                        => 'adrun_campaign_has_banner',
        'TBL_REPORT_SUMMARY'            => 'adrun_report_summary',
            
        ],
    
    'adtech' => [
        'ADTECH_DEBUG'                 => false,
        'ADTECH_LOGIN'                 => 'platform.api.1724.1',
        'ADTECH_CLIENT_ID'             => '39bc117d-8c75-403b-95b0-2e1e7dd807c1',
        'ADTECH_CLIENT_SECRET'         => 'YtoqY5auhGK8Nse6ZYJCWw',
        'ADTECH_SCOPE'                 => 'webAPI:1724.1:87321',
        'ADTECH_SERVER_URL'            => 'https://ws-api.adtech.de/',
        'ADTECH_SERVER_URL_END'        => '?wsdl',
        'ADTECH_SOAP_VERSION'          => array ('soap_version' => 'SOAP_1_2','trace' => 1)
    ],
    
    'report' => [
        'excel' => '&view=imps&format=exel&mainLogo=&subLogo=&langText=fr&langDate=fr_FR&langNum=fr_FR&',
        'csv'   => '&view=imps&format=csv&mainLogo=&subLogo=&langText=fr&langDate=fr_FR&langNum=fr_FR&',
        'xml'   => '&view=imps&format=xml&mainLogo=&subLogo=&langText=fr&langDate=fr_FR&langNum=fr_FR&'
        
    ],
    
    'emails' => [
//        'Caroline Legagneur'        => 'c.legagneur@adrun.re',
//        'Virginie Cuinet'           => 'v.cuinet@adrun.re',
//        'Laurent Trotzier'          => 'l.trotzier@adrun.re',
        'Yoann HAPOLD'              => 'devweb@adrun.re',
        'Linda Santoulangue'        => 'traffic@adrun.re',
        'Jacques Rima'              => 'devwebmaurice@adrun.re',
        'Mara Natacha SEEVATHEEAN ' => 'm.seevatheean@nrj.mu',
    ],
    
    'EMAIL' => [
        'SERVER_NAME'           => '[TESTING] ADRUN DASHBOARD INTEL [BETA]',
        'ANNOCEUR_NEW'          => 'Nouveau  Annonceur',
        'BILAN_CAMPAIGN'        => 'Bilan  Des Campaign [ALL]',
    
    ],    
        
    ];

