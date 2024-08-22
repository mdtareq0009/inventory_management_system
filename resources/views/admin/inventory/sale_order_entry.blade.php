@extends('layouts.admin')
@section('title', 'Sale Order Entry')
@section('breadcrumb', 'Sale Order Entry')
@section('body')

<sale-order-inventory-entry role="{{ auth()->user()->role }}" invoice={{$invoice}} id={{$id}} branch="{{ auth()->user()->branch_id }}"></sale-order-inventory-entry>

@endsection