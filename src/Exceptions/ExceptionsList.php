<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates\Exceptions;

class ExceptionsList
{

    const COMMON = [
        '-1' => [
            'message' => 'File creation failure.',
            'code' => -1
        ],
        '-2' => [
            'message' => 'File reading failure.',
            'code' => -2
        ]
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
        ],
        '-17' => [
            'message' => 'Data deleting failure.',
            'code' => -17
        ]
    ];

    const TEMPLATE_SETTINGS = [
        '-21' => [
            'message' => 'Template file saving failure.',
            'code' => -21
        ]
    ];

    const HANDLERS = [
        '-31' => [
            'message' => 'Incorrect value.',
            'code' => -31
        ]
    ];

}
