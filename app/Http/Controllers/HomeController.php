<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Stocks;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    // Method to fetch users based on conditions
    public static function getUsers($condition = [])
    {
        $condition[] = ['role_id', '!=', 1];
        return User::where($condition)->get();
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        $totalCreditAmount = Transaction::
        where('type', 'credit')
        ->sum('amount');
       
        $totalDebitAmount = Transaction::
            where('type', 'debit')
            ->sum('amount');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $user_id = $request->input('user_id');

           // $transactionsQuery = DB::table('transactions')
           // ->leftJoin('stocks', 'stocks.id', '=', 'transactions.id')
           // ->leftJoin('users', 'users.id', '=', 'transactions.user_id')
           // ->select('transactions.*', 'stocks.date', 'users.name as name')
           // ->latest();
        
        // Check if start and end dates are provided
        $transactionsQuery = Transaction::with(['stocks', 'user']);

        // Retrieving variables from the request
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;
    
        // Filter transactions by date range if both start and end dates are provided
       
    
        // Filter transactions by user ID if provided
        if ($user_id) {
            $transactionsQuery->whereHas('stocks', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        }
        if ($start_date && $end_date) {
            // Ensuring correct querying when involving relationships
            $transactionsQuery->whereHas('stocks', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            });
            $transactions = $transactionsQuery->paginate(5);
        }else{
            $transactions = $transactionsQuery->latest()->paginate(5);
        }
        // Paginate the results
       
    
        // Fetch users with role_id not equal to 1
        $users = self::getUsers();
   // dd($transactions);
        // Return the view with the necessary data
        return view('home', compact('transactions', 'totalCreditAmount', 'totalDebitAmount', 'users', 'start_date', 'end_date', 'user_id'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function addUser()
    {
        return view('admin.adduser');
    }
    public function addBalance()
    {
        return view('admin.addbalance');
    }
    public function listUser()
    {
        $users = User::latest()->paginate(5);
        return view('admin.list-user',compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            //'phone' => 'required',
        ]);
        // Example usage:
        $name =$validatedData['name'];
        $randomNumber = rand(0, 100);
        $email = self::generateRandomEmail($name.$randomNumber);
        $user = new User();

		// Set the attributes
		$user->name = $name;
		$user->email = $email;
        $user->role_id = 2;
        $user->password = Hash::make($email);
        $user->save();
     
        return redirect()->route('list.user')
                        ->with('success','User created successfully.');
    }
    public function storeBalance(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required',
            //'phone' => 'required',
        ]);
        // Example usage:
        $transaction = Transaction::create([
            'amount' => $request->amount,
            'user_id' => Auth::id(),
            'description' => 'Balance added', // You can customize the description
            'type' => 'credit', // Or 'income' depending on your application logic
        ]);
     
        return redirect()->route('home')
                        ->with('success','Balance added successfully.');
    }
    function generateRandomEmail($name) {
        $username = strtolower(preg_replace('/[^a-z0-9]/i', '', $name)); // Remove non-alphanumeric characters and convert to lowercase
        $domain = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'example.com'][array_rand(['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'example.com'])]; // Randomly select a domain from the list
        return "{$username}@{$domain}";
    }
    public function edit(Request $request,$id)
    {
		//
		$user = User::where('id', $id)->first();
		if(!$user){
			return redirect()->route('index')
                        ->with('danger','No data found');
		}
        return view('admin.edituser',compact('user'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
		
		//dd($request);
		// validate
        // read more on validation at http://laravel.com/docs/validation
        $request->validate([
            'name' => 'required',
        ]);
       // $validator = Validator::make(Input::all(), $rules);
        // store
            $user = User::find($id);
            $user->name       = $request->name;
            $user->save();

    
        return redirect()->route('user.edit',$id)
                        ->with('success','User updated successfully');
    }
    public function profileEdit(Request $request)
    {
		//
		$user = User::where('id', Auth::id())->first();
		if(!$user){
			return redirect()->route('index')
                        ->with('danger','No data found');
		}
        return view('admin.profile',compact('user'));
    }
    public function profileUpdate(Request $request)
    {
		$user = User::where('id', Auth::id())->first();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profile.edit', $user)
            ->with('success', 'Profile updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $client_id = $id;
      $user = User::findorfail($id);
      $user->delete();
      return redirect()->route('list.user')
                        ->with('success','Data deleted successfully');
    }
}
