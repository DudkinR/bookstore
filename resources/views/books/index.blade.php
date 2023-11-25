@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>List of books</h1>
            </div>
        </div>
        @if(Auth::check())
        <div class="row">
            <div class="col">
                <a href="{{ route('books.create') }}" class="btn btn-primary">New book</a>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col">
                <h1>Sort</h1>
            </div>
            <div class="col">
                <a href="#" class="btn btn-info" onclick="sort('az')" >A_Z</a>
            </div>
            <div class="col">
                <a href="#" class="btn btn-info" onclick="sort('za')" >Z_A</a>
            </div>
            <div class="col">
                <a href="#" class="btn btn-info" onclick="sort('tm')" >Time</a>
            </div>
            
        </div>
        <div class="container" id="_books">

        </div>
        <div class="container" id="_pagination">

        </div>
    </div>
    <script>
     window.books = {!!$books!!}; // Replace with your actual data
     window.pages=chunkArray(window.books, 5); // 5 items per page
    let page=1; // start only with page 1
    showPageContent(page, window.pages);
    showPagination(page, window.pages);
    </script>
@endsection
