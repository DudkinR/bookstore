@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Edit book</h1>
        <div class="row">
            <div class="col">
                <a href="{{ route('books.index') }}" class="btn btn-primary">List of books</a>
            </div>
        </div>
        <form action="{{ route('books.update',$book->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="{!!$book->title!!}" class="form-control">
            </div>
            <div class="form-group">
                <label for="content">Author:</label>
                <select name="author_id[]" id="author_id" class="form-control" multiple >
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}"
                            @if(in_array($author->id, $book->authors->pluck('id')->toArray()))   selected @endif                           
                            >{{ $author->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="content">New author:</label>
                <input type="text" name="new_author" id="new_author" class="form-control">
            </div>
            <div class="form-group">
                <label for="content">Publisher:</label>
                <select name="publisher_id[]"  id="publisher_id" class="form-control"  multiple>
                    @foreach($publishers as $publisher)
                    <option value="{{ $publisher->id }}"
                        @if(in_array($publisher->id, $book->publishers->pluck('id')->toArray())) selected @endif
                            >{{ $publisher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="content">New publisher:</label>
                <input type="text" name="new_publisher" id="new_publisher" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
@endsection
