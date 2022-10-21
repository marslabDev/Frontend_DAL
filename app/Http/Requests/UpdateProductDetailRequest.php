<?php

namespace App\Http\Requests;

use App\Models\ProductDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_detail_edit');
    }

    public function rules()
    {
        return [
            'price' => [
                'required',
            ],
            'product_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
