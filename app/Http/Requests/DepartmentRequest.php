<?php
// app/Http/Requests/DepartmentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $departmentId = $this->route('department') ? $this->route('department')->id : null;
        
        return [
            'name' => 'required|string|max:255|unique:departments,name,' . $departmentId,
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255'
        ];
    }
}