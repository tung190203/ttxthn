<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    protected $table = "legal_document";

    public function detail()
    {
        return $this->hasMany(LegalDocumentImage::class, 'legal_documnet_id');
    }
}
