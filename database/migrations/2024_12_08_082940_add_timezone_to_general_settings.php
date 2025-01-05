<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Add the theme setting
        $this->migrator->add('general.Timezone', 'UTC');  // Default value can be changed to 'dark' or anything else
    }
};
