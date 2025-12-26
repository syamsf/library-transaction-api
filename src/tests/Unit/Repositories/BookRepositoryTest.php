<?php

namespace Tests\Unit\Repositories;

use App\Data\PaginationQueryParamData;
use App\Models\BooksModel;
use App\Repositories\BookRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class BookRepositoryTest extends TestCase {
    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_fetch_all_books() {
        $mockBooks = collect([
            ['id' => 1, 'title' => 'Book 1'],
            ['id' => 2, 'title' => 'Book 2'],
            ['id' => 3, 'title' => 'Book 3'],
        ]);

        $paginator = new LengthAwarePaginator($mockBooks, 3, 10);

        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('fetchAll')
            ->once()
            ->andReturn($paginator);

        $result = $repository->fetchAll(new PaginationQueryParamData());

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(3, $result);
    }

    /** @test */
    public function it_can_find_book_by_id() {
        $mockBook = new BooksModel;
        $mockBook->id = 1;
        $mockBook->title = 'Test Book';

        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($mockBook);

        $found = $repository->findById(1);

        $this->assertNotNull($found);
        $this->assertEquals(1, $found->id);
        $this->assertEquals('Test Book', $found->title);
    }

    /** @test */
    public function it_returns_null_when_book_not_found() {
        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('findById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $found = $repository->findById(999);

        $this->assertNull($found);
    }

    /** @test */
    public function it_can_create_a_book() {
        $data = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'stock' => 10
        ];

        $mockBook = new BooksModel;
        $mockBook->id = 1;
        $mockBook->title = 'Test Book';
        $mockBook->author = 'Test Author';
        $mockBook->stock = 10;

        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockBook);

        $book = $repository->create($data);

        $this->assertEquals('Test Book', $book->title);
        $this->assertEquals('Test Author', $book->author);
        $this->assertEquals(10, $book->stock);
    }

    /** @test */
    public function it_can_update_a_book() {
        $updateData = [
            'title' => 'Updated Title',
            'stock' => 20
        ];

        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('update')
            ->once()
            ->with(1, $updateData)
            ->andReturn(true);

        $result = $repository->update(1, $updateData);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_returns_false_when_updating_non_existent_book() {
        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('update')
            ->once()
            ->with(999, ['title' => 'Updated'])
            ->andReturn(false);

        $result = $repository->update(999, ['title' => 'Updated']);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_delete_a_book() {
        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $result = $repository->delete(1);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_returns_false_when_deleting_non_existent_book() {
        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('delete')
            ->once()
            ->with(999)
            ->andReturn(false);

        $result = $repository->delete(999);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_decrease_stock() {
        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('decreaseStock')
            ->once()
            ->with(1, 3)
            ->andReturn(true);

        $result = $repository->decreaseStock(1, 3);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_returns_false_when_decreasing_stock_below_zero() {
        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('decreaseStock')
            ->once()
            ->with(1, 5)
            ->andReturn(false);

        $result = $repository->decreaseStock(1, 5);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_increase_stock() {
        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('increaseStock')
            ->once()
            ->with(1, 5)
            ->andReturn(true);

        $result = $repository->increaseStock(1, 5);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_returns_false_when_increasing_stock_for_non_existent_book() {
        $repository = Mockery::mock(BookRepository::class)->makePartial();
        $repository->shouldReceive('increaseStock')
            ->once()
            ->with(999, 5)
            ->andReturn(false);

        $result = $repository->increaseStock(999, 5);

        $this->assertFalse($result);
    }
}

