@extends('layouts.adminBase')

@section('content')
@include('content-management.hosting.styles')
<style>
    @media print {
        .sidebar, .navbar, .hosting-tools, .alert { display: none !important; }
        .pay-request { border: 0; }
    }
</style>
<div class="admin-livewire-page d-flex w-100 align-items-stretch">
@include('content-management.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')
    <div class="container-fluid pt-4 px-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <p class="hosting-tools"><a href="{{ route('content-management.hosting.index') }}">All hosting invoices</a></p>
        @include('content-management.hosting.document')
        @include('content-management.hosting.actions')
    </div>
</div>
</div>
@endsection
