<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplicationDocument extends Model
{
    protected $table = 'application_documents';

    protected static $mediaColumns = [
        'legal_entity',
        'logo_electronic',
        'cab_agreement',
        'assessment_checklist',
        'standard',
        'quality_documentation',
        'relevant_associate',
        'testing_scheme',
        'proficiency_testing',
        'requiring_accreditation',
        'job_description',
        'risk_analysis',
        'technical_and_quality',
        'signature',
        'selfie',
        'application_fee'
    ];

    public function remarkby()
    {
        return $this->belongsTo(Admin::class, 'remark_by');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

  
    public function __get($key)
    {
        if (str_ends_with($key, '_file')) {
            $baseKey = str_replace('_file', '', $key);

            if (in_array($baseKey, self::$mediaColumns)) {
                $mediaId = $this->getAttribute($baseKey);
                return $this->getMediaPath($mediaId);
            }
        }

        return parent::__get($key);
    }

    protected function getMediaPath($mediaId)
    {
        if (!$mediaId) {
            return null;
        }

        $media = DB::table('media')->select('file')->where('id', $mediaId)->first();
        return $media ? asset($media->file) : null;
    }
    
}
