<?php

namespace Tests\Feature;

use App\Models\BooksModel;
use App\Models\BorrowsModel;
use App\Models\BorrowItemsModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_fetch_all_borrows()
    {
        $member = User::factory()->create();
        $borrow = BorrowsModel::factory()->create(['member_id' => $member->id]);
        $book = BooksModel::factory()->create();
        BorrowItemsModel::factory()->create([
            'borrow_id' => $borrow->id,
            'book_id' => $book->id
        ]);

        $response = $this->getJson('/api/borrows');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'member_id',
                        'member',
                        'borrowed_at',
                        'items',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'meta'
            ]);
    }

    /** @test */
    public function it_can_fetch_all_borrows_with_pagination()
    {
        $member = User::factory()->create();
        BorrowsModel::factory()->count(15)->create(['member_id' => $member->id]);

        $response = $this->getJson('/api/borrows?page=1&per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function it_can_fetch_a_borrow_by_id()
    {
        $member = User::factory()->create();
        $borrow = BorrowsModel::factory()->create(['member_id' => $member->id]);
        $book = BooksModel::factory()->create();
        BorrowItemsModel::factory()->create([
            'borrow_id' => $borrow->id,
            'book_id' => $book->id,
            'quantity' => 2
        ]);

        $response = $this->getJson("/api/borrows/{$borrow->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'member_id',
                    'member' => [
                        'id',
                        'name',
                        'email'
                    ],
                    'borrowed_at',
                    'items' => [
                        '*' => [
                            'id',
                            'book_id',
                            'book',
                            'quantity'
                        ]
                    ],
                    'created_at',
                    'updated_at'
                ],
                'meta'
            ]);
    }

    /** @test */
    public function it_returns_404_when_borrow_not_found()
    {
        $response = $this->getJson('/api/borrows/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Borrow record not found'
            ]);
    }

    /** @test */
    public function it_can_borrow_a_single_book()
    {
        $member = User::factory()->create();
        $book = BooksModel::factory()->create(['stock' => 10]);

        $borrowData = [
            'member_id' => $member->id,
            'books' => [
                [
                    'book_id' => $book->id,
                    'quantity' => 2
                ]
            ]
        ];

        $response = $this->postJson('/api/borrows', $borrowData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'member_id',
                    'member',
                    'borrowed_at',
                    'items',
                    'created_at',
                    'updated_at'
                ],
                'meta'
            ]);

        // Verify borrow was created
        $this->assertDatabaseHas('borrows', [
            'member_id' => $member->id
        ]);

        // Verify borrow item was created
        $this->assertDatabaseHas('borrow_items', [
            'book_id' => $book->id,
            'quantity' => 2
        ]);

        // Verify stock was decreased
        $book->refresh();
        $this->assertEquals(8, $book->stock);
    }

    /** @test */
    public function it_can_borrow_multiple_books()
    {
        $member = User::factory()->create();
        $book1 = BooksModel::factory()->create(['stock' => 10]);
        $book2 = BooksModel::factory()->create(['stock' => 5]);

        $borrowData = [
            'member_id' => $member->id,
            'books' => [
                [
                    'book_id' => $book1->id,
                    'quantity' => 2
                ],
                [
                    'book_id' => $book2->id,
                    'quantity' => 1
                ]
            ]
        ];

        $response = $this->postJson('/api/borrows', $borrowData);

        $response->assertStatus(201);

        // Verify both items were created
        $this->assertDatabaseHas('borrow_items', [
            'book_id' => $book1->id,
            'quantity' => 2
        ]);
        $this->assertDatabaseHas('borrow_items', [
            'book_id' => $book2->id,
            'quantity' => 1
        ]);

        // Verify stock was decreased for both
        $book1->refresh();
        $book2->refresh();
        $this->assertEquals(8, $book1->stock);
        $this->assertEquals(4, $book2->stock);
    }

    /** @test */
    public function it_validates_required_fields_when_borrowing()
    {
        $response = $this->postJson('/api/borrows', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['member_id', 'books']);
    }

    /** @test */
    public function it_validates_member_exists_when_borrowing()
    {
        $book = BooksModel::factory()->create(['stock' => 10]);

        $borrowData = [
            'member_id' => 999, // Non-existent member
            'books' => [
                [
                    'book_id' => $book->id,
                    'quantity' => 1
                ]
            ]
        ];

        $response = $this->postJson('/api/borrows', $borrowData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['member_id']);
    }

    /** @test */
    public function it_validates_book_exists_when_borrowing()
    {
        $member = User::factory()->create();

        $borrowData = [
            'member_id' => $member->id,
            'books' => [
                [
                    'book_id' => 999, // Non-existent book
                    'quantity' => 1
                ]
            ]
        ];

        $response = $this->postJson('/api/borrows', $borrowData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['books.0.book_id']);
    }

    /** @test */
    public function it_validates_quantity_is_positive_when_borrowing()
    {
        $member = User::factory()->create();
        $book = BooksModel::factory()->create(['stock' => 10]);

        $borrowData = [
            'member_id' => $member->id,
            'books' => [
                [
                    'book_id' => $book->id,
                    'quantity' => 0
                ]
            ]
        ];

        $response = $this->postJson('/api/borrows', $borrowData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['books.0.quantity']);
    }

    /** @test */
    public function it_prevents_borrowing_when_insufficient_stock()
    {
        $member = User::factory()->create();
        $book = BooksModel::factory()->create(['stock' => 2]);

        $borrowData = [
            'member_id' => $member->id,
            'books' => [
                [
                    'book_id' => $book->id,
                    'quantity' => 5 // More than available
                ]
            ]
        ];

        $response = $this->postJson('/api/borrows', $borrowData);

        $response->assertStatus(400)
            ->assertJson([
                'message' => "Insufficient stock for book: {$book->title}"
            ]);

        // Verify stock was NOT decreased
        $book->refresh();
        $this->assertEquals(2, $book->stock);
    }

    /** @test */
    public function it_rolls_back_transaction_when_borrowing_fails()
    {
        $member = User::factory()->create();
        $book1 = BooksModel::factory()->create(['stock' => 10]);
        $book2 = BooksModel::factory()->create(['stock' => 2]);

        $borrowData = [
            'member_id' => $member->id,
            'books' => [
                [
                    'book_id' => $book1->id,
                    'quantity' => 1
                ],
                [
                    'book_id' => $book2->id,
                    'quantity' => 5 // More than available
                ]
            ]
        ];

        $response = $this->postJson('/api/borrows', $borrowData);

        $response->assertStatus(400);

        // Verify NO borrow was created (transaction rolled back)
        $this->assertDatabaseCount('borrows', 0);
        $this->assertDatabaseCount('borrow_items', 0);

        // Verify stock was NOT decreased for either book
        $book1->refresh();
        $book2->refresh();
        $this->assertEquals(10, $book1->stock);
        $this->assertEquals(2, $book2->stock);
    }

    /** @test */
    public function it_validates_books_array_has_at_least_one_item()
    {
        $member = User::factory()->create();

        $borrowData = [
            'member_id' => $member->id,
            'books' => [] // Empty array
        ];

        $response = $this->postJson('/api/borrows', $borrowData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['books']);
    }
}

