<?php

function getDataVrtour($_url)
{
    $ch             = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response       = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function createFile($folder, $file)
{
    // Tạo thư mục
    if (!Illuminate\Support\Facades\File::exists(public_path($folder))) {
        Illuminate\Support\Facades\File::makeDirectory(public_path($folder), 0755, true);
    }
    // Tạo file
    if (!Illuminate\Support\Facades\File::exists(public_path($folder.'/'.$file))) {
        Illuminate\Support\Facades\File::put(public_path($folder.'/'.$file), '');
    }
    return "Đã tạo file thành công!";
}