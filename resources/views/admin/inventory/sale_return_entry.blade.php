@extends('layouts.admin')
@section('title', 'Sale Return Entry')
@section('breadcrumb', 'Sale Return Entry')
@section('body')

<sale-return-inventory-entry role="{{ auth()->user()->role }}" invoice={{$invoice}} id={{$id}} branch="{{ auth()->user()->branch_id }}"></sale-return-inventory-entry>

@endsection