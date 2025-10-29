<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Setting extends Model
{
    protected $table = 'settings';

    public static function check_exists_skey($skey = '')
    {
        if ($skey == '') {
            return false;
        }
        $setting = self::where('skey', $skey)->first();
        if (isset($setting)) {
            return true;//Đã tồn tại
        }
        return false;
    }

    public static function getAllSetting()
    {
        static $cached = [];
    
        if (isset($cached['all_setting'])) {
            return $cached['all_setting'];
        } else {
            $settings = self::get();
            $results = [];
    
            foreach ($settings as $setting) {
                if ($setting->skey === 'banners' || $setting->skey === 'features') {
                    $results[$setting->skey] = !empty($setting->svalue)
                        ? json_decode($setting->svalue, true)
                        : [];
                } else {
                    $results[$setting->skey] = $setting->svalue;
                }
            }
    
            $cached['all_setting'] = $results;
            return $results;
        }
    }    

    public static function getSettingByKey($key, $default = '')
    {
        $setting = Setting::where('skey', $key)
            ->first();
        return data_get($setting, 'svalue', $default);
    }
}
