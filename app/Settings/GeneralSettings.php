<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $title;


    public string $theme;

    public string $Timezone;

    public string $language;

    public string $font;

    public static function group(): string
    {
        return 'general'; // Optional, if you want to group your settings
    }
}
