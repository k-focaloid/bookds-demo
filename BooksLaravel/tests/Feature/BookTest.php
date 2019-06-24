<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Genre;
use App\Book;

class BookTest extends TestCase {
    
    protected $firstUserToken = null;
    protected $secondUserToken = null;
    //Create user and authenticate the user
    protected function getFirstUser() {

        if($this->firstUserToken!=null)return $this->firstUserToken;
        $data = [
            'email' => 'first@gmail.com',
            'name' => 'First',
            'password' => 'secret1234'
        ];
        //Send post request
        $this->json('POST', 'api/signup', $data);

        $response = $this->json('POST', 'api/login', [
            'email' => 'first@gmail.com',
            'password' => 'secret1234',
        ]);

        $this->firstUserToken = $response->json()['token'];
        return $this->firstUserToken;
    }

    protected function getSecondUser() {
        if($this->secondUserToken!=null)return $this->secondUserToken;
        $data = [
            'email' => 'second@gmail.com',
            'name' => 'Second',
            'password' => 'secret1234'
        ];
        //Send post request
        $this->json('POST', 'api/signup', $data);

        $response = $this->json('POST', 'api/login', [
            'email' => 'second@gmail.com',
            'password' => 'secret1234',
        ]);

        $this->secondUserToken = $response->json()['token'];
        return $this->secondUserToken;
    }

    protected function resetTestData(){
        try{
            User::where('email', 'first@gmail.com')->get()->first()->books()->forceDelete();
            User::where('email', 'second@gmail.com')->get()->first()->books()->forceDelete();
            User::where('email', 'first@gmail.com')->forceDelete();
            User::where('email', 'second@gmail.com')->forceDelete();
            return true;
        }
        catch(\Exception $ex){
            dd ($ex);
            return false;
        }
       // assert()
    }

    protected function getGenre() {
        $genre = Genre::first();
        return $genre->id;
    }

    protected function getBook() {
        $book =  User::where('email', 'first@gmail.com')->first()->books()->first();
        return $book->id;
    }


     /**
     * @test
     * Testing Book Creation
     */
    public function testCreateBook() {
        //Get token
        $token = $this->getFirstUser();
        Storage::fake('document');
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->json('POST', 'api/book/create', [
            'book_name' => 'Jollof Rice',
            'book_description' => 'Parboil rice, get pepper and mix, and some spice and serve!',
            'book_genre' => $this->getGenre(), //get a genre
            'book' => $file
        ]);
        $response->assertStatus(201);
    }

    /**
     * @test
     * Testing Book updation by author
     */
    public function testBookUpdateByAuthor() {
        //Get token
        $token = $this->getFirstUser();
        $response = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->json('PUT', 'api/book/update', [
            'book_name' => 'Joll of Rice',
            'book_description' => 'Parboil rice, get pepper and mix, and some spice and serve!',
            'book_genre' => $this->getGenre(), //get a genre
            'book_id' => $this->getBook(), //get a book,
            '_method' => 'PUT'
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     * Testing Book updation by a non-author
     */
    public function testBookUpdateByNonAuthor() {
        //Get token
        $token = $this->getSecondUser();
        $response = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->json('PUT', 'api/book/update', [
            'book_name' => 'Joll of Rice',
            'book_description' => 'Parboil rice, get pepper and mix, and some spice and serve!',
            'book_genre' => $this->getGenre(), //get a genre
            'book_id' => $this->getBook(), //get a book,
            '_method' => 'PUT'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * Testing Book Details by author
     */
    public function testGetBookDetailsByAuthor() {
        //Get token
        $token = $this->getFirstUser();
        $response = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->json('GET', 'api/book/' . $this->getBook());
        $response->assertStatus(200);
    }

    /**
     * @test
     * Testing Book Details by non-author
     */
    public function testGetBookDetailsByNonAuthor() {
        //Get token
        $token = $this->getFirstUser();
        $response = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->json('GET', 'api/book/' . $this->getBook());
        $response->assertStatus(200);
    }

    /**
     * @test
     * Testing Book Details by unauthenticated user
     */
    public function testGetBookDetailsByNonUser() {
        $response = $this->json('GET', 'api/book/' . $this->getBook());
        $response->assertStatus(401);
    }


    /**
     * @test
     * Test book delete by a non-author
     */
    public function testBookDeleteByNonAuthor() {
        //Get token
        $token = $this->getSecondUser();
        $response = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->json('DELETE', 'api/book/delete', [
            'book_id' => $this->getBook(), //get a book,
            '_method' => 'DELETE'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * Test book delete by a non-user
     */
    public function testBookDeleteByNonUser() {
        //Get token
        $response = $this->withHeaders([
                ])->json('DELETE', 'api/book/delete', [
            'book_id' => $this->getBook(), //get a book,
            '_method' => 'DELETE'
        ]);
        $response->assertStatus(401);
    }

    /**
     * @test
     * Test book delete by the author
     */
    public function testBookDeleteByAuthor() {
        //Get token
        $token = $this->getFirstUser();
        $response = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->json('DELETE', 'api/book/delete', [
            'book_id' => $this->getBook(), //get a book,
            '_method' => 'DELETE'
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     * Cleanup Test Data
     */
    public function testCleanData() {
        $this->assertTrue($this->resetTestData());
    }

}
