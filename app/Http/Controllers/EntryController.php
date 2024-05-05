<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Entry;
use App\Models\Stocks;
use App\Models\Transaction;

class EntryController extends Controller
{
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
    public function index(Request $request)
    {
        $users = self::getUsers(); // Fetch users with role_id not equal to 1
        // Retrieve stocks with associated user (username)
        // $results = Stocks::with('user')->where('type' , '=' ,'1')->latest()->paginate(5);
         $stocksQuery = Stocks::with(['user'])->where('type', 1);
         $id = $request->id;
        // Check if user ID is provided
        if ($id) {
            $stocksQuery->where('id', $id);
        }
 
        $results = $stocksQuery->paginate(5);
        return view('admin.entry.index',compact('results'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function add()
    {
        $users = self::getUsers(); // Fetch users with role_id not equal to 1
        return view('admin.entry.add',compact('users'));
    }
 
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:0|max:1000000',
            'date' => 'required|date',
        ]);

         // $validator = Validator::make(Input::all(), $rules);
       $totalCreditAmount = Transaction::
       where('type', 'credit')
       ->sum('amount');

       $totalDebitAmount = Transaction::
           where('type', 'debit')
           ->sum('amount');
       $balance = $totalCreditAmount - $totalDebitAmount;
       $amount = $request->amount;
       if($balance < $amount){
           return redirect()->route('entry.add')
           ->with('error','Insufficient balance.');
       }

        $stocks = new Stocks();

		// Set the attributes
	
		$stocks->user_id = $request->user_id;
        $stocks->type = 1;
        $stocks->date = $request->date;
        $stocks->payment_type = $request->payment_type;
        $stocks->description = ($request->description != '') ? $request->description : '';
        $stocks->amount = $request->amount;
        $stocks->save();
     
        return redirect()->route('entry.index')
                        ->with('success','Entry added successfully.');
    }
    function generateRandomEmail($name) {
        $username = strtolower(preg_replace('/[^a-z0-9]/i', '', $name)); // Remove non-alphanumeric characters and convert to lowercase
        $domain = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'example.com'][array_rand(['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'example.com'])]; // Randomly select a domain from the list
        return "{$username}@{$domain}";
    }
    public function edit(Request $request,$id)
    {
        $users = self::getUsers(); // Fetch users with role_id not equal to 1
		$result = Stocks::where('id', $id)->first();
		if(!$result){
			return redirect()->route('entry.index')
                        ->with('danger','No data found');
		}
        return view('admin.entry.edit',compact('result','users'));
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
		
        // read more on validation at http://laravel.com/docs/validation
        $validatedData = $request->validate([
            'user_id' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:0|max:1000000',
            'date' => 'required|date',
        ]);
       // $validator = Validator::make(Input::all(), $rules);
       $totalCreditAmount = Transaction::
        where('type', 'credit')
        ->sum('amount');

        $totalDebitAmount = Transaction::
            where('type', 'debit')
            ->sum('amount');
           
       
            $balance = $totalCreditAmount - $totalDebitAmount;
          //  $balance =  $allbalance - $stocks->amount;
            $stocks = Stocks::find($id);
            $stocks->user_id = $request->user_id;
            $stocks->type = 1;
            $stocks->date = $request->date;
            $stocks->payment_type = $request->payment_type;
            $stocks->description = ($request->description != '') ? $request->description : '';
            // Recalculate balance
            if( $stocks->amount != $request->amount){
                $balance = $totalCreditAmount - $totalDebitAmount;
                $balance =  $stocks->amount + $balance;
                $balance =  $balance - $request->amount;
                //$balance =  $allbalance - ($stocks->amount + $request->amount);
                // Check for insufficient balance
                if ($balance < 0) {
                    return redirect()->route('entry.edit', $id)->with('error', 'Insufficient balance. Available balance: ' . $balance);
                    die();
                }
            }
           
            $stocks->amount = $request->amount;
            // Save changes
            $stocks->save();

            $transaction = Transaction::find($stocks->transaction_id);


            if ($transaction) {
                // Update transaction attributes if the transaction exists
                $transaction->amount =  $request->amount;
                $transaction->user_id = $request->user_id;
                // Update other transaction attributes if necessary
                $transaction->save();
            }
    
        return redirect()->route('entry.edit',$id)
                        ->with('success','Entry updated successfully' . $balance);
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
      $entry = Stocks::findorfail($id);
      $transaction_id = $entry->transaction_id;
     // $stocks->delete();
      if($entry->delete()){
        $transaction = Transaction::find($transaction_id);
        $transaction->delete();
      }
      return redirect()->route('entry.index')
                        ->with('success','Data deleted successfully');
    }
}
