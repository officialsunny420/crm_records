@extends('layouts.admin_layout')

@section('content')
<div class="row">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                            <span>{{ __('Edit Stock') }}</span>
                            <a class="btn btn-primary" href="{{ route('stock.index') }}">Back</a>
                        </div>

                    <div class="card-body">
                    @if ($message = Session::get('success'))
                    <div class="row">
                       <div class="col-md-12 alert alert-success">
                        <p>{{ $message }}</p>
                      </div>
                    </div>
                    @endif
                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                        <form method="POST" action="{{ route('stock.update', $stock->id) }}">
                            @csrf
                           
                            <div class="mb-3">
                                <label for="user" class="form-label">User</label>
                                <select class="form-control" id="user_id" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $stock->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="item" class="form-label">Item</label>
                                <input type="text" name="item" class="form-control @error('item') is-invalid @enderror" value="{{ $stock->item }}" required autocomplete="item" autofocus placeholder="Enter Item">
                                @error('item')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="voucher" class="form-label">Voucher</label>
                                <input type="text" name="voucher" class="form-control @error('voucher') is-invalid @enderror" value="{{ $stock->voucher }}"  autocomplete="voucher" autofocus placeholder="Enter voucher">
                                @error('voucher')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="qty" class="form-label">Qty</label>
                                <input type="text" name="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ $stock->qty }}" required autocomplete="qty" autofocus placeholder="Enter qty">
                                @error('qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ $stock->price }}" required autocomplete="price" autofocus placeholder="Enter price">
                                @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ $stock->date }}" required autocomplete="date" autofocus placeholder="Enter date">
                                @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
