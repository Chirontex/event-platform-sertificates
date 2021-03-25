<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates\Exceptions;

class ExceptionsList
{

    const COMMON = [

    ];

    const PROVIDERS = [
        '-11' => [
            'message' => 'Table creation failure.',
            'code' => -11
        ],
        '-12' => [
            'message' => 'Data selection from table failed.',
            'code' => -12
        ],
        '-13' => [
            'message' => 'Key cannot be empty.',
            'code' => -13
        ],
        '-14' => [
            'message' => 'Value cannot be empty.',
            'code' => -14
        ],
        '-15' => [
            'message' => 'Data inserting failure.',
            'code' => -15
        ],
        '-16' => [
            'message' => 'Data updating failure.',
            'code' => -16
        ]
    ];

}
