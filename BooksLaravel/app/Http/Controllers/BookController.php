<?php

namespace App\Http\Controllers;

use App\Book;
use App\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller {

    public function listBooks(Request $request) {
        if (\Auth::user()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = "";
        }
        $books = Book::all();
        $final_res = [];
        foreach ($books as $book) {
            $res = [];
            $res['id'] = $book->id;
            $res['name'] = $book->name;
            $res['author'] = $book->author->name;
            $res['genre'] = $book->genre->name;
            if ($book->user_id == $user_id) {
                $res['is_author'] = true;
            } else {
                $res['is_author'] = false;
            }
            $final_res[] = $res;
        }
        return response()->json(["message" => "Books List", "data" => $final_res], 200);
    }

    public function listGenre(Request $request) {

        $genre = Genre::all(['id', 'name']);
        return response()->json(["message" => "Genre List", "data" => $genre], 200);
    }

    public function createBook(Request $request) {
        $request->validate([
            'book_name' => 'required',
            'book_description' => 'required',
            'book_genre' => 'required',
            'book' => 'required|mimes:pdf'
        ]);
        $imageName = uniqid() . '.' .
                $request->file('book')->getClientOriginalExtension();

        $request->file('book')->move(
                public_path() . '/images/books/', $imageName
        );

        $book = new Book();
        $book->name = $request->book_name;
        $book->description = $request->book_description;
        $book->genre_id = decrypt($request->book_genre);
        $book->book_link = $imageName;
        $book->user_id = Auth::user()->id;
        $book->save();
        return response()->json(["message" => "Book added successfully"], 201);
    }

    public function updateBook(Request $request) {
        $request->validate([
            'book_id' => 'required',
            'book_name' => 'required',
            'book_description' => 'required',
            'book_genre' => 'required'
        ]);

        
        $book = Book::where('id',decrypt($request->book_id))->where('user_id',Auth::user()->id)->get()->first();
        if(!$book)
        {
        return response()->json(["message" => "Access denied"], 403);
        }else{
           $book->name = $request->book_name;
        $book->description = $request->book_description;
        $book->genre_id = decrypt($request->book_genre);
        $book->save();
        return response()->json(["message" => "Book updated successfully"], 200);  
        }
       
    }

    public function deleteBook(Request $request) {
        $request->validate([
            'book_id' => 'required'
        ]);
        $book = Book::where('id',decrypt($request->book_id))->where('user_id',Auth::user()->id)->get()->first();
         if(!$book)
        {
        return response()->json(["message" => "Access denied"], 403);
        }else{
        $book->delete();
        return response()->json(["message" => "Book deleted successfully"], 200);
    }
    }

    public function bookDetails(Request $request) {
        $book = Book::find(decrypt($request->book_id));
        $res['id'] = $book->id;
        $res['name'] = $book->name;
        $res['description'] = $book->description;
        $res['genre'] = $book->genre->name;
        $res['author'] = $book->author->name;
        $res['book_link'] = $book->book_link;
        $genre_id = decrypt($book->genre->id);
        $genre = Genre::all(['id', 'name']);
        $final_res_gen = [];
        foreach ($genre as $gen) {
            $res_gen['id'] = $gen->id;
            $res_gen['name'] = $gen->name;
            if ($genre_id == decrypt($gen->id)) {
                $res_gen['selected'] = true;
            } else {
                $res_gen['selected'] = false;
            }
            $final_res_gen[] = $res_gen;
        }
        return response()->json(["message" => "Book details", "data" => $res, "genre" => $final_res_gen], 200);
    }

}
