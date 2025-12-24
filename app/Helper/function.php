<?php

use App\Models\Project;

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
    $fullPath = public_path($folder);
    if (str_starts_with($folder, 'vrtour/')) {

        $code = str_replace('vrtour/', '', $folder);
        $project = Project::where('vrtour_code', $code)->first();

        if (!$project) {
            $project = Project::where('name', $code)->first();
        }

        if ($project) {
            $oldPath = public_path('vrtour/' . $project->name);
            $newPath = public_path('vrtour/' . $project->vrtour_code);

            if (!Illuminate\Support\Facades\File::exists($newPath) && Illuminate\Support\Facades\File::exists($oldPath)) {
                Illuminate\Support\Facades\File::move($oldPath, $newPath);
                $fullPath = $newPath;
            }
        }
    }
    // Tạo thư mục
    if (!Illuminate\Support\Facades\File::exists($fullPath)) {
        Illuminate\Support\Facades\File::makeDirectory($fullPath, 0755, true);
    }
    // Tạo file
    if (!Illuminate\Support\Facades\File::exists($fullPath . '/' . $file)) {
        Illuminate\Support\Facades\File::put($fullPath . '/' . $file, '');
    }
    return "Đã tạo file thành công!";
}
