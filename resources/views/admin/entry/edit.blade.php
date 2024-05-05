@extends('layouts.admin_layout')

@section('content')
               <div class="row">
               <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Edit Entry') }}</span>
                        <a class="btn btn-primary" href="{{ route('entry.index') }}">Back</a>
                    </div>

                <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="row">
                       <div class="col-md-12 alert alert-success">
                        <p>{{ $message }}</p>
                      </div>
                    </div>
                    @endif
                    <form method="POST" action="{{ url('/entry/update/'.$result->id) }}">
                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                        @csrf
                        <div class="mb-3">
                            <label for="user" class="form-label"></label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                   <?php 
                                      $selected = "";
                                      if ($user->id == $result->user_id){
                                          $selected = 'selected';
                                      }
                                   ?>
                                    <option value="{{ $user->id }}" {{ $selected }} >{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label"></label>
                            <select class="form-control" id="payment_type" name="payment_type">
                                <option value="">Select Payment Type</option>
                                      <?php 
                                       $payment_options = payment_options(0 , true);
                                       if($payment_options):
                                         foreach($payment_options as $key => $value):
                                             $selected = "";
                                             if ($result->payment_type == $key){
                                                 $selected = 'selected';
                                             }
                                             
                                             echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                                         endforeach;
                                      endif;
                               ?>
                               
                            </select>
                        </div>
                        <div class="mb-3">
                           <label for="qty" class="form-label">Description</label>
                           <input type="text" name="description"  class="form-control @error('description') is-invalid @enderror"  value="{{ $result->description }}" required autocomplete="description" autofocus  placeholder="Enter description">
                             @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="mb-3">
                           <label for="item" class="form-label">Amount</label>
                           <input type="text"  name="amount"  class="form-control @error('amount') is-invalid @enderror"  value="{{ $result->amount }}" required autocomplete="amount" autofocus  placeholder="Enter amount">
                             @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="mb-3">
                           <label for="item" class="form-label">Date</label>
                           <input type="date"  name="date"  class="form-control @error('date') is-invalid @enderror"  value="{{ $result->date }}" required autocomplete="date" autofocus  placeholder="Enter date">
                             @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
                </div>
@endsection
