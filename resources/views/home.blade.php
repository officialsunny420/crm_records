@extends('layouts.admin_layout')

@section('content')

            <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
               <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Credit</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCreditAmount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                       Debit</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDebitAmount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Balance</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCreditAmount - $totalDebitAmount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->



    

    <div id="content">
                    <!-- Page Heading -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                          
                            <form  action="/home" method="get" id="" name="" novalidate="novalidate" class="fv-form fv-form-bootstrap">
                                  
                                    <div class="box-body">
                                        <div class="row">
                                       
                                            <div class="col-md-4  inside  ">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">User:</label>
                                                    <select class="form-control" id="user_id" name="user_id">
                                                    <option value="">Select User</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" {{ $user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                    
                                                </div>
                                             </div>
                                            <div class="col-md-4 inside  ">
                                                <div class="form-group date">
                                                    <label>From Date:</label>

                                                    <div class="input-group">
                                                    <input type="date"  name="start_date"  class="form-control @error('start_date') is-invalid @enderror"  value="{{ $start_date }}" required autocomplete="start_date" autofocus  placeholder="Enter date">
                                                    </div>
                                                                                    <!-- /.input group -->
                                                </div>
                                            </div>

                                            <div class="col-md-4 inside  ">
                                                <div class="form-group date">
                                                    <label>To Date:</label>

                                                    <div class="input-group">
                                                        <input type="date" name="end_date" class="form-control" placeholder="Enter date" value="{{ $end_date }}">
                                                    </div>
                                                                                    <!-- /.input group -->
                                                </div>
                                            </div>


                                        </div>


                                        <div class="row">

                                            <div class="col-md-12">

                                                <button type="submit" style=" margin: 6px;" class="btn btn-info pull-right">Search
                                                </button>
                                                <a href="/home"  style=" margin: 6px;" class="btn btn-info pull-right">Reset
                                                </a>
                                            </div>

                                        </div>

                                    </div><!-- /.box-body -->
                                </form>
                                <div class="col-md-2 ">
                                                 <a href="{{ route('add.balance') }}" class="btn btn-secondary">Credit Balance</a>
                                                </div>
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
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Added ON</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td><?php echo isset($transaction->user->name) ? $transaction->user->name : ''; ?></td>
                                                @php 
                                                    if($transaction->type == 'debit') {
                                                @endphp
                                                        <td>
                                                            <a href="#" class="btn btn-danger btn-icon-split">
                                                                <span class="text">{{ $transaction->amount }}</span>
                                                            </a>
                                                        </td>
                                                @php
                                                    } else {
                                                @endphp
                                                        <td>
                                                            <a href="#" class="btn btn-success  btn-icon-split">
                                                                <span class="text">{{ $transaction->amount }}</span>
                                                            </a>
                                                        </td>
                                                @php
                                                    }
                                                @endphp
                                            <td><?php echo isset($transaction->stocks->date) ? $transaction->stocks->date : ''; ?></td>
                                            <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <!--<form action="{{ url('/transaction/destroy/'.$transaction->id) }}" method="POST" onsubmit="return confirmDelete()">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-blue">View</button>
                                                </form>-->
                                                <?php 
                                                 if(isset($transaction->stocks->type)){
                                                    if(isset($transaction->stocks->type) && $transaction->stocks->type == '0'){
                                                        $view_url =  url('/stock/index/?id='.$transaction->stocks->id); 
                                                    }else{
                                                        $view_url =  url('/entry/index/?id='.$transaction->stocks->id); 
                                                    }
                                                ?>
                                                                 <a href="{{ $view_url }}" class="btn btn-warning  btn-icon-split">
                                                                  <span class="text">View</span>
                                                               </a>

                                                    <?php } ?>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- Display Pagination Links -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $transactions->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                </div>
            </div>
</div>
</div>
@endsection
