<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $book= new Book;
        $books = json_encode($book->loadAuthorsAndPublishers());
        return view('books.index', compact('books'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('books.create', compact('authors', 'publishers'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author_id' => 'required_without:new_author|array',
            'author_id.*' => 'distinct|exists:authors,id',
            'publisher_id' => 'required_without:new_publisher|array',
            'publisher_id.*' => 'distinct|exists:publishers,id',
        ]);
        if($request->new_author)
            $author_id = $this->new_author($request->new_author)->id;
        else
            $author_id = $request->author_id;
        if($request->new_publisher)
            $publisher_id = $this->new_publisher($request->new_publisher)->id;
        else
            $publisher_id = $request->publisher_id;
        $this->present_book($request->title, $author_id, $publisher_id);
        return redirect()->route('books.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    { // dont use this becouse have realisation in index to js 
        $book= Book::find($id);  
        return view('books.show', compact('book'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::find($id)->load('authors', 'publishers');
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('books.edit', compact('book', 'authors', 'publishers'));
    }

    /**
     * Update the specified resource in storage.
     * 
     */
     public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'author_id' => 'required_without:new_author|array',
            'author_id.*' => 'distinct|exists:authors,id',
            'publisher_id' => 'required_without:new_publisher|array',
            'publisher_id.*' => 'distinct|exists:publishers,id',
        ]);
        $book = Book::find($id);
        $book->title = $request->title;
        $book->save();
        $authorIds = $request->input('author_id', []);
        if ($request->new_author) {
            $newAuthor = $this->new_author($request->new_author);
            $authorIds[] = $newAuthor->id;
        }
        $book->authors()->sync($authorIds);
        $publisherIds = $request->input('publisher_id', []);
        if ($request->new_publisher) {
            $newPublisher = $this->new_publisher($request->new_publisher);
            $publisherIds[] = $newPublisher->id;
        }
        $book->publishers()->sync($publisherIds);
        return redirect()->route('books.index'); 
    }
    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
            // find book by id
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            // additional code after delete success
            return response()->json(['success' => true]);
        } else {
            // book not found
            return response()->json(['error' => 'Book not found'], 404);
        }
        // we do not return to the index, because we are using AJAX
    }

    // have or not author 
    public function new_author($name)
    {
        $author = Author::where('name', $name)->first();
        if($author){
            return $author;
        }
        else
        {
            $author = new Author();
            $author->name = $name;
            $author->save();
            return $author;
        }
    }

    // have or not publisher
    public function new_publisher($name)
    {
        $publisher = Publisher::where('name', $name)->first();
        if($publisher){
            return $publisher;
        }
        else
        {
            $publisher = new Publisher();
            $publisher->name = $name;
            $publisher->save();
            return $publisher;
        }
    }

    // have or not book
    public function present_book($title, $author, $publisher)
    {
        $book = Book::where('title', $title)->first();
        if($book){
            return $book;
        }
        else
        {
            $book = new Book();
            $book->title = $title;
            $book->save();
            $book->authors()->attach($author);
            $book->publishers()->attach($publisher);
            return $book;
        }
    }
     // API all books 
    public function indexAPI()
    {
        $books = Book::all()->load('authors', 'publishers');
        return response()->json($books);
    }
    // API search book by title
    public function searchAPI(Request $request)
    {
        $search = $request->input('search');
        if(!$search){
            return response()->json([]);
        }
        //loadAuthorsAndPublishers
       $books = Book::where('title', 'like', "%$search%")->get()->load('authors', 'publishers');
       return response()->json($books);
    }


}
