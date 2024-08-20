@extends('layouts.admin')
@section('title', 'Create User')
@section('breadcrumb', 'Create User')
@section('body')

<register role="{{ auth()->user()->role }}"></register>

@endsection