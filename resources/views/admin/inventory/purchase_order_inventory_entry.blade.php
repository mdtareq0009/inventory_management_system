@extends('layouts.admin')
@section('title', 'Purchase Order Entry')
@section('breadcrumb', 'Purchase Order Entry')
@section('body')

<purchase-order-inventory-entry role="{{ auth()->user()->role }}" invoice={{$invoice}} id={{$id}} branch="{{ auth()->user()->branch_id }}"></purchase-order-inventory-entry>

@endsection