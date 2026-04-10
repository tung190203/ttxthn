<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$keys = ['chatbot_name', 'chatbot_tooltip', 'chatbot_welcome_message', 'chatbot_avatar', 'chatbot_primary_color'];

foreach ($keys as $key) {
    $setting = Setting::where('skey', $key)->first();
    if ($setting) {
        echo "Key: $key\n";
        echo "Raw svalue: " . $setting->getAttributes()['svalue'] . "\n";
        echo "Translations: " . json_encode($setting->getTranslations('svalue')) . "\n";
        echo "-------------------\n";
    } else {
        echo "Key: $key NOT FOUND\n";
    }
}
