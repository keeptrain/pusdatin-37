<?php

namespace App\Models;

use App\States\LetterStatus;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documents\DocumentUpload;
use App\Models\Letters\RequestStatusTrack;
use App\States\PublicRelation\PublicRelationStatus;

class PublicRelationRequest extends Model
{
    use HasStates;
    
    protected $table = "public_relation_requests";

    protected $casts = [
        'status' => PublicRelationStatus::class,
        'links' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'month_publication',
        'spesific_date',
        'theme',
        // 'media_type',
        'target',
        'active_review',
        'links',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentUploads()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

    public function requestStatusTrack()
    {
        return $this->morphMany(RequestStatusTrack::class, 'statusable');
    }


}
