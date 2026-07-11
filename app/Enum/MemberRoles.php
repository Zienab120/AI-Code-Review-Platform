<?php

namespace App\Enum;

class MemberRoles
{
    const OWNER = 'owner';
    const ADMIN = 'admin';
    const MaINTAINER = 'maintainer';
    const DEVELOPER = 'developer';
    const VIEWER = 'viewer';

    public static function getRoles(): array
    {
        return [
            self::OWNER,
            self::ADMIN,
            self::MaINTAINER,
            self::DEVELOPER,
            self::VIEWER
        ];
    }
}
