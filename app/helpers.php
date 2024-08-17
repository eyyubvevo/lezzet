<?php
if (!function_exists('setting')) {

    function setting($key)
    {
        return \App\Models\Setting::getValueByKey($key);
    }

}
