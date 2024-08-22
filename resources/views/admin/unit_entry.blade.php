@extends('layouts.admin')
@section('title', 'Unit Entry')
@section('breadcrumb', 'Unit Entry')
@section('body')

<unit-entry role="{{ auth()->user()->role }}"></unit-entry>


@endsection