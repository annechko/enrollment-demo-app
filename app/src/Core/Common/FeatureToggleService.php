<?php

declare(strict_types=1);

namespace App\Core\Common;

class FeatureToggleService
{
    public function isEnabled(FeatureToggleType $type): bool
    {
        // todo add entity and repository and store in db.
        // admin user can activate different toggles.
        return true;
    }
}
