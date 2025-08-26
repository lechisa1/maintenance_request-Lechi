<?php
// app/Helpers/OrganizationHelper.php
namespace App\Helpers;

use App\Models\OrganizationUnitLabel;
use Illuminate\Support\Facades\Cache;

class OrganizationHelper
{
    public static function labels()
    {
        return Cache::rememberForever('organization_labels', function () {
            return OrganizationUnitLabel::pluck('label', 'unit_type')->toArray();
        });
    }

    public static function label($unitType)
    {
        return self::labels()[$unitType] ?? ucfirst($unitType);
    }
}