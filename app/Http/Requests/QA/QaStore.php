<?php

namespace App\Http\Requests\QA;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class QaStore extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "category_id" => "required",
            "question" => "required",
            "answer" => "required",
        ];
    }

    public function messages()
    {
        return [
            "category_id.required" => "Chưa chọn danh mục , vui lòng chọn danh mục !",
            "question.required" => "Chưa nhập câu hỏi , vui lòng nhập câu hỏi !",
            "answer.required" => "Chưa nhập câu trả lời , vui lòng nhập câu trả lời !",
        ];
    }
}