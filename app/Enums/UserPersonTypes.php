<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static UserPersonTypes LEGAL_PERSON()
 * @method static UserPersonTypes PHYSICAL_PERSON()
 */
class UserPersonTypes extends Enum
{
    private const LEGAL_PERSON = 'legal_person';
    private const PHYSICAL_PERSON = 'physical_person';
}
