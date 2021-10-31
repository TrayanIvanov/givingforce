<?php

namespace App\Service;

use App\Entity\Role;
use App\Entity\User;

class UserPermissions
{
    public const PERMISSION_MOVE_APPLICATION = 'permission_move_application';
    public const PERMISSION_APPROVE_CHARITY = 'permission_approve_charity';
    public const PERMISSION_SEE_REPORTS = 'permission_see_reports';
    public const PERMISSION_CREATE_APPLICATION = 'permission_create_application';

    public const PERMISSIONS = [
        Role::ROLE_EMPLOYEE => [
            self::PERMISSION_CREATE_APPLICATION,
        ],
        Role::ROLE_ADMIN => [
            self::PERMISSION_APPROVE_CHARITY,
        ],
        Role::ROLE_ADMIN_APPLICATIONS => [
            self::PERMISSION_APPROVE_CHARITY,
            self::PERMISSION_MOVE_APPLICATION,
        ],
        Role::ROLE_ADMIN_REPORTS => [
            self::PERMISSION_APPROVE_CHARITY,
            self::PERMISSION_SEE_REPORTS,
        ],
    ];

    public function granted(User $user, string $requestedPermission): bool
    {
        $availablePerissions = [];

        /** @var Role $role */
        foreach ($user->getRoles() as $role) {
            foreach (self::PERMISSIONS[$role->getRole()] as $perission) {
                $availablePerissions[] = $perission;
            }
        }
        $availablePerissions = array_unique($availablePerissions);

        return in_array($requestedPermission, $availablePerissions);
    }
}
