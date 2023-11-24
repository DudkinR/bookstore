@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Show book</h1>
        <div class="row">
            <div class="col">
                <a href="{{ route('books.index') }}" class="btn btn-primary">List of books</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3>Title:</h3>
                <p>{!!$book->title!!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3>Authors:</h3>
                <ul>
                    @foreach($book->authors as $author)
                        <li>{{ $author->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3>Publishers:</h3>
                <ul>
                    @foreach($book->publishers as $publisher)
                        <li>{{ $publisher->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row">
            <h1>Maneger</h1>
            <div class="col">
                <a href="{{ route('books.edit',$book->id) }}" class="btn btn-primary">Edit</a>
            </div>
            <div class="col">
                <form action="{{ route('books.destroy',$book->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>

        </div>

    </div>
@endsection
