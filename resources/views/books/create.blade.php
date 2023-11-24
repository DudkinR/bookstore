@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>New book</h1>
        <div class="row">
            <div class="col">
                <a href="{{ route('books.index') }}" class="btn btn-primary">List of books</a>
            </div>
        </div>
        <form action="{{ route('books.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="row">
                <div class="col">
                    <label for="content">Author:</label>
                    <select name="author_id[]" id="author_id" multiple class="form-control">
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="content">New author:</label>
                    <input type="text" name="new_author" id="new_author" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="content">Publisher:</label>
                    <select name="publisher_id[]" multiple id="publisher_id" class="form-control">
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="content">New publisher:</label>
                    <input type="text" name="new_publisher" id="new_publisher" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
