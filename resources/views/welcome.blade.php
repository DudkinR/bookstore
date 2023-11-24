@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Books store</h1>
            <div class="row">
                <div class="col">
                    <a href="{{ route('books.index') }}" class="btn btn-primary">Books</a>
                </div>
                <div class="col">
                    <a href="{{ route('books.indexAPI') }}" class="btn btn-primary">API all books</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection