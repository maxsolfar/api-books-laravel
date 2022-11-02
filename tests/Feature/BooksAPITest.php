<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksAPITest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books(){
        $books = Book::factory(4)->create();
        $this->getJson(route("books.index"))
            ->assertJsonFragment([
                "title" => $books[0]->title
            ])->assertJsonFragment([
                "title" => $books[1]->title
            ]);
    }

    /** @test */
    function can_get_one_book(){
        $book = Book::factory()->create();
        $this->getJson(route("books.show", $book))
            ->assertJsonFragment([
                "title" => $book->title
            ]);
    }

    /** @test */
    function can_create_book(){

        $this->postJson(route("books.store"), [])
            ->assertJsonValidationErrorFor("title");

        $this->postJson(route("books.store", [
            "title" => "New Book"
            ]))->assertJsonFragment([
            "title" => "New Book"
            ]);

        $this->assertDatabaseHas("books", [
            "title" => "New Book"
        ]);
    }

    /** @test */
    function can_update_book(){
        $book = Book::factory()->create();

        $this->patchJson(route("books.update", $book), [])
            ->assertJsonValidationErrorFor("title");

        $this->patchJson(route("books.update", $book),[
            "title" => "Edited Book"
        ])->assertJsonFragment([
            "title" => "Edited Book"
        ]);
    }

    /** @test */
    function can_delete_book(){
        $book = Book::factory()->create();

        $this->deleteJson(route("books.destroy", $book))
            ->assertNoContent();

        $this->assertDatabaseCount("books",0);
    }
}
