@extends('layouts.admin')
@section('title', 'Purchase Return Entry')
@section('breadcrumb', 'Purchase Return Entry')
@section('body')

<purchase-return-inventory-entry role="{{ auth()->user()->role }}" invoice={{$invoice}} id={{$id}} branch="{{ auth()->user()->branch_id }}"></purchase-return-inventory-entry>

@endsection