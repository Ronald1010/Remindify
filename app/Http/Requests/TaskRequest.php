<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
public function rules(): array
{
    return [
        'task_name'     => 'nullable|string|max:10000',
        'description'   => 'nullable|string',
        'deadline_date' => 'nullable|date_format:Y-m-d',


        'deadline_time' => 'nullable|date_format:H:i', 
        'image_path'    => 'nullable|image|mimes:jpg,gif,png,bmp|max:5120',
        // Add more validation rules as needed for other fields
    ];
}



}
