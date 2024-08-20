@extends('layouts.admin')
@section('title', 'Category Entry')
@section('breadcrumb', 'Category Entry')
@section('body')

<category-entry role="{{ auth()->user()->role }}"></category-entry>

@endsection