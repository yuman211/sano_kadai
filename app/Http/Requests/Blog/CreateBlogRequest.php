<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateBlogRequest extends FormRequest
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
            // 'user_id' => 'required',
            'category_id' => 'required',
            'title' => ['required', 'max:20'],
            'price' => ['required', 'numeric'],
            'content' => ['required', 'min:5'],
        ];
    }


    public function messages()
    {
        return [
            'category_id.required' => 'カテゴリーを入力してください。', //入力が無かった場合のエラー文
            'title.required' => 'タイトルを入力してください。',
            'title.max' => 'タイトルは20文字以内で入力してください。',
            'price.required' => '価格を入力してください。',
            'price.numeric' => '価格は数字で入力してください。',
            'content.required' => '内容を入力してください',
            'content.min' => '内容は５文字以上で入力してください。',
        ];
    }

    /**
     * バリデーションエラーが起きたら実行される
     *
     * @param Validator $validator
     * @return HttpResponseException
     */
    protected function failedValidation(Validator $validator): HttpResponseException
    {
        $response = response()->json([
            'status' => 'validation error',
            'errors' => $validator->errors()
        ], 400);
        throw new HttpResponseException($response);
    }

}
