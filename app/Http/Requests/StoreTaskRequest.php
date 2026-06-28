<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        title: 'TaskRequest',
        properties: [
            new OA\Property(
                property: 'title',
                description: 'Название задачи',
                type: 'string',
                example: 'Выполнение тестового задания',
            ),
            new OA\Property(
                property: 'description',
                description: 'Описание задачи',
                type: 'string',
                example: 'Тестовое задание необходимо выполнить в течении 5 рабочих дней'
            ),
            new OA\Property(
                property: 'due_date',
                description: 'Срок выполнения',
                type: 'date-time',
                example: '2026-06-26T10:00:00'
            ),
            new OA\Property(
                property: 'created_at',
                description: 'Дата создания',
                type: 'date-time',
                example: '2026-06-26T10:00:00'
            ),
            new OA\Property(
                property: 'status_id',
                description: 'Статус задачи',
                type: 'long',
                example: 'Не выполнена'
            ),
            new OA\Property(
                property: 'priority',
                description: 'Приоритет задачи',
                type: 'long',
                example: 'Высокий'
            ),
            new OA\Property(
                property: 'category',
                description: 'Категория задачи',
                type: 'long',
                example: 'Работа'
            )
        ],
        xml: new OA\Xml(name: 'TaskRequest'),
    )
]
class StoreTaskRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_at' => 'nullable|date',
            'due_date' => 'required|date',
            'status_id' => 'required|exists:statuses,id',
            'priority_id' => 'required|exists:priorities,id',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
