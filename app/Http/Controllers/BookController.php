<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Services\AuthorService;
use App\Services\PublisherService;
use App\Services\BookService;

class BookController extends Controller
{

    private $book;
    private $author;
    private $publisher;
    protected $authorService;
    protected $publisherService;
    protected $bookService;
    public function __construct(
        Book $book,
        Author $author,
        Publisher $publisher,
        AuthorService $authorService,
        PublisherService $publisherService,
        BookService $bookService
        )
    {
        $this->book = $book;
        $this->author = $author;
        $this->publisher = $publisher;
        $this->authorService = $authorService;
        $this->publisherService = $publisherService;
        $this->bookService = $bookService;

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = json_encode($this->book->loadAuthorsAndPublishers());
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
            $author_id = $this->authorService->getOrCreateAuthor($request->new_author)->id;
        else
            $author_id = $request->author_id;
        if($request->new_publisher)
            $publisher_id = $this-> publisherService->getOrCreatePublisher($request->new_publisher)->id;
        else
            $publisher_id = $request->publisher_id;
        $this-> bookService->getOrCreateBook($request->title, $author_id, $publisher_id);
        
        return redirect()->route('books.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    { // dont use this becouse have realisation in index to js 
        $book=$this->book->loadAuthorsAndPublishers()->find($id);
        return view('books.show', compact('book'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = $this->book->find($id) ->load('authors', 'publishers');
        $authors = $this->author->all();
        $publishers = $this->publisher->all();
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
        $book = $this->book->find($id);
        $book->title = $request->title;
        $book->save();
        $authorIds = $request->input('author_id', []);
        if ($request->new_author) {
            $newAuthor = $this-> authorService->getOrCreateAuthor($request->new_author);
            $authorIds[] = $newAuthor->id;
        }
        $book->authors()->sync($authorIds);
        $publisherIds = $request->input('publisher_id', []);
        if ($request->new_publisher) {
            $newPublisher = $this->publisherService->getOrCreatePublisher($request->new_publisher);
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
        $book = $this->book->find($id);
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

     // API all books 
    public function indexAPI()
    {
        $books = $this->book->loadAuthorsAndPublishers();
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
       $books =  $this->book->where('title', 'like', "%$search%")->get()->load('authors', 'publishers');
       return response()->json($books);
    }


}
