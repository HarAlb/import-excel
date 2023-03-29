<?php

namespace App\Http\Api\V1\Files\Requests;

use App\Http\Api\Base\Requests\ApiRequest;
use App\Http\Api\Base\Constants\FileUploadConstants;

class StoreRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'file' => [
                'required',
                'file',
                'max:' . FileUploadConstants::FILE_MAX_SIZE * 1024,
                'mimes:' . FileUploadConstants::getMimes()
            ]
        ];
    }
}
