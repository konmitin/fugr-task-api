<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected Status $status;
    protected Priority $priority;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->status = Status::find(1) ?? Status::create(['title' => 'Не выполнена']);
        $this->priority = Priority::find(1) ?? Priority::create(['title' => 'Низкий']);
        $this->category = Category::find(1) ?? Category::create(['title' => 'Работа']);
    }

    private function makeTask(array $attributes = []): Task
    {
        $attributes['title'] = $attributes['title'] ??  'Новая задача';
        $attributes['due_date'] = $attributes['due_date'] ??  '2026-06-05';

        return Task::create(array_merge([
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'category_id' => $this->category->id,
        ], $attributes));
    }

    // ==================== INDEX ====================

    public function test_index_returns_paginated_tasks_with_correct_resource_structure(): void
    {
        Task::factory()->count(25)->create([
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([

                '*' => [
                    'id',
                    'title',
                    'description',
                    'created_at',
                    'due_date',
                    'status' => ['id', 'title'],
                    'priority' => ['id', 'title'],
                    'category' => ['id', 'title'],
                ]
            ]);

        $this->assertCount(20, $response->json());
    }

    public function test_index_can_specify_per_page(): void
    {
        Task::factory()->count(15)->create([
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson('/api/tasks?per_page=10');

        $response->assertStatus(200);
        $this->assertCount(10, $response->json());
    }

    public function test_index_can_search_by_title(): void
    {
        $this->makeTask(['title' => 'Специфическая задача']);
        $this->makeTask(['title' => 'Обычная задача']);

        $response = $this->getJson('/api/tasks?search=Специфическая');

        $response->assertStatus(200);

        $this->assertCount(1, $response->json());
        $this->assertEquals('Специфическая задача', $response->json('0.title'));
    }

    public function test_index_can_sort_by_due_date(): void
    {
        $task1 = $this->makeTask(['due_date' => '2026-01-01']);
        $task2 = $this->makeTask(['due_date' => '2025-01-01']);

        $response = $this->getJson('/api/tasks?sort=due_date');

        $response->assertStatus(200);

        $this->assertEquals($task2->id, $response->json('0.id'));
    }

    // ==================== STORE ====================

    public function test_store_creates_a_task_and_returns_id_with_message(): void
    {
        $data = [
            'title' => 'Новая задача',
            'description' => 'Описание задачи',
            'due_date' => '2024-12-31',
            'status_id' => $this->status->id,
            'priority_id' => $this->priority->id,
            'category_id' => $this->category->id,
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task created',
            ])
            ->assertJsonStructure(['id', 'message']);

        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_store_fails_with_validation_errors(): void
    {
        $response = $this->postJson('/api/tasks', [
            'due_date' => 'invalid-date',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'due_date']);
    }

    // ==================== UPDATE ====================

    public function test_update_modifies_a_task(): void
    {
        $task = $this->makeTask(['title' => 'Старое название']);

        $updateData = [
            'title' => 'Новое название',
            'status_id' => $this->status->id,
        ];

        $response = $this->patchJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task updated']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Новое название',
        ]);
    }

    public function test_update_returns_404_for_missing_task(): void
    {
        $response = $this->patchJson('/api/tasks/9999', [
            'title' => 'Задача',
            'status_id' => $this->status->id,
        ]);

        $response->assertStatus(404);
    }

    // ==================== SHOW ====================

    public function test_show_returns_task_with_full_resource_structure(): void
    {
        $task = $this->makeTask();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date,
                'status' => [
                    'id' => $this->status->id,
                    'title' => $this->status->title,
                ],
                'priority' => [
                    'id' => $this->priority->id,
                    'title' => $this->priority->title,
                ],
                'category' => [
                    'id' => $this->category->id,
                    'title' => $this->category->title,
                ]
            ]);
    }

    public function test_show_returns_404_for_missing_task(): void
    {
        $response = $this->getJson('/api/tasks/9999');
        $response->assertStatus(404);
    }

    // ==================== DESTROY ====================

    public function test_destroy_deletes_a_task(): void
    {
        $task = $this->makeTask();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task destroyed']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_destroy_returns_404_for_missing_task(): void
    {
        $response = $this->deleteJson('/api/tasks/9999');
        $response->assertStatus(404);
    }
}
