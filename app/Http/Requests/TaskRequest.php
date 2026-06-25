<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        title: 'TaskRequest',
        properties: [
            new OA\Property(
                property: 'page',
                description: 'Страница для вывода',
                type: 'integer',
                example: '2',
            ),
            new OA\Property(
                property: 'per_page',
                description: 'Кол-во элементов на странице',
                type: 'integer',
                example: '20'
            ),
            new OA\Property(
                property: 'search',
                description: 'Строка поиска по названию',
                type: 'string',
                example: 'Задача для поиска'
            ),
            new OA\Property(
                property: 'sort',
                description: 'Поле для сортировки',
                type: 'string',
                example: 'due_date'
            ),
        ],
        xml: new OA\Xml(name: 'TaskRequest'),
    )
]
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
            'search' => 'nullable|string',
            'sort' => ['nullable', Rule::in(['due_date'])],
        ];
    }
}
