<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Google\Service\PubsubLite\Resource\Admin;

/**
 * @method static static Admin()
 * @method static static Manager()
 * @method static static Normal()
 */
final class UserType extends Enum
{
    const Admin = 2;
    const Manager = 1;
    const Normal = 0;


    public static function toString(UserType $value)
    {
        if ($value == UserType::Admin())
            return "admin";
        if ($value==UserType::Manager())
            return "manager";
        if ($value==UserType::Normal())
            return "normal";

        return "none";

    }

    public function ocnverted(){
        if($this->value==UserType::Admin())
            return "admin";
        if($this->value==UserType::Manager())
            return "manager";
        if($this->value==UserType::Normal())
            return "normal";
    }
}
