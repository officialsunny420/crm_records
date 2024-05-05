@extends('layouts.admin_layout')

@section('content')
<div id="content">
    <!-- Page Heading -->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Entries</h6>
            <a href="{{ route('entry.add') }}" class="btn btn-secondary">Add </a>
        </div>
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Payment Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $result->user->name }}</td>
                            <td>{{ payment_options($result->payment_type) }}</td>
                            <td>{{ $result->amount }}</td>
                            <td>{{ $result->description }}</td>
                            <td>{{ $result->date }}</td>
                            <td>
                                <form action="{{ url('/entry/destroy/'.$result->id) }}" method="POST" onsubmit="return confirmDelete()">
                                    <a class="btn btn-primary" href="{{ route('entry.edit',$result->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                 <!-- Display Pagination Links -->
                 <div class="d-flex justify-content-center mt-4">
                    {{ $results->links() }}
                </div>
            </div>
        </div>
    </div>
    
</div>

@endsection
<style>
/* Custom Pagination Styles */
.pagination {
    margin: 20px 0;
}

.pagination > li > a,
.pagination > li > span {
    padding: 10px 15px;
    border: 1px solid #ddd;
    color: #333;
    background-color: #fff;
}

.pagination > li > a:hover {
    background-color: #f5f5f5;
}

.pagination > .active > a,
.pagination > .active > span {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

</style>
<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this user?');
}
</script>
