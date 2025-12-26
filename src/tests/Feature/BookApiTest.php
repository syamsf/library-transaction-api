<?php

namespace Tests\Feature;

use App\Models\BooksModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_fetch_all_books()
    {
        BooksModel::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'author',
                        'stock',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'meta' => [
                    'status',
                    'response_code'
                ]
            ])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_fetch_all_books_with_pagination()
    {
        BooksModel::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/books?page=1&perPage=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function it_can_fetch_a_book_by_id()
    {
        $book = BooksModel::factory()->create([
            'title' => 'Test Book',
            'author' => 'Test Author',
            'stock' => 10
        ]);

        $response = $this->getJson("/api/v1/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'author',
                    'stock',
                    'created_at',
                    'updated_at'
                ],
                'meta' => [
                    'status',
                    'response_code'
                ]
            ])
            ->assertJson([
                'data' => [
                    'title' => 'Test Book',
                    'author' => 'Test Author',
                    'stock' => 10
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_book_not_found()
    {
        $response = $this->getJson('/api/v1/books/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Book not found'
            ]);
    }

    /** @test */
    public function it_can_create_a_book()
    {
        $bookData = [
            'title' => 'New Book',
            'author' => 'New Author',
            'stock' => 15
        ];

        $response = $this->postJson('/api/v1/books', $bookData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'author',
                    'stock',
                    'created_at',
                    'updated_at'
                ],
                'meta'
            ])
            ->assertJson([
                'data' => [
                    'title' => 'New Book',
                    'author' => 'New Author',
                    'stock' => 15
                ]
            ]);

        $this->assertDatabaseHas('books', [
            'title' => 'New Book',
            'author' => 'New Author',
            'stock' => 15
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_book()
    {
        $response = $this->postJson('/api/v1/books', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'author']);
    }

    /** @test */
    public function it_validates_stock_is_integer_when_creating_book()
    {
        $bookData = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'stock' => 'invalid'
        ];

        $response = $this->postJson('/api/v1/books', $bookData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['stock']);
    }

    /** @test */
    public function it_validates_stock_is_not_negative_when_creating_book()
    {
        $bookData = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'stock' => -5
        ];

        $response = $this->postJson('/api/v1/books', $bookData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['stock']);
    }

    /** @test */
    public function it_can_update_a_book()
    {
        $book = BooksModel::factory()->create([
            'title' => 'Original Title',
            'author' => 'Original Author',
            'stock' => 10
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'stock' => 20
        ];

        $response = $this->putJson("/api/v1/books/{$book->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Title',
                    'stock' => 20
                ]
            ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
            'stock' => 20
        ]);
    }

    /** @test */
    public function it_can_partially_update_a_book()
    {
        $book = BooksModel::factory()->create([
            'title' => 'Original Title',
            'author' => 'Original Author',
            'stock' => 10
        ]);

        $response = $this->putJson("/api/v1/books/{$book->id}", [
            'stock' => 25
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Original Title',
            'author' => 'Original Author',
            'stock' => 25
        ]);
    }

    /** @test */
    public function it_returns_404_when_updating_non_existent_book()
    {
        $response = $this->putJson('/api/v1/books/999', [
            'title' => 'Updated Title'
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_a_book()
    {
        $book = BooksModel::factory()->create();

        $response = $this->deleteJson("/api/v1/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'message' => 'Book deleted successfully'
                ]
            ]);

        $this->assertSoftDeleted('books', [
            'id' => $book->id
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_book()
    {
        $response = $this->deleteJson('/api/v1/books/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_cannot_delete_already_deleted_book()
    {
        $book = BooksModel::factory()->create();
        $book->delete(); // Soft delete

        $response = $this->deleteJson("/api/v1/books/{$book->id}");

        $response->assertStatus(404);
    }
}

