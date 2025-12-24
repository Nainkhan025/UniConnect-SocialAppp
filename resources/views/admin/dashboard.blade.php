@extends('layouts.layout')

@section('content')
<div class="container mt-4">

    <h3 class="fw-bold mb-4">Admin Dashboard</h3>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Users</h6>
                    <h3>—</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Posts</h6>
                    <h3>—</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Pending Reports</h6>
                    <h3>—</h3>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
