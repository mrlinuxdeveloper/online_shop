@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard')

@section('content')

    <div class="container-fluid">

        <div class="mb-4">
            <h2>Dashboard</h2>
            <p class="text-muted">
                Welcome {{ auth()->user()->name }}
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Products</h6>
                        <h2>0</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Orders</h6>
                        <h2>0</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Users</h6>
                        <h2>0</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Categories</h6>
                        <h2>0</h2>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection