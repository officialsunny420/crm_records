<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stocks;
use App\Models\User;
use App\Models\Transaction;

class StocksController extends Controller
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
        // $stocks = Stocks::with('user')->where('type' , '=' ,'0')->latest()->paginate(5);
         $stocksQuery = Stocks::with(['user'])->where('type', 0);
         $id = $request->id;
        // Check if user ID is provided
        if ($id) {
            $stocksQuery->where('id', $id);
        }
 
        $stocks = $stocksQuery->paginate(5);
        return view('admin.stock.index',compact('stocks'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function add()
    {
        $users = self::getUsers(); // Fetch users with role_id not equal to 1
        return view('admin.stock.add',compact('users'));
    }
 
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item' => 'required',
            'user_id' => 'required',
            'qty' => 'required',
            'price' => 'required|numeric|min:0|max:1000000',
            'date' => 'required|date',
        ]);

        $totalCreditAmount = Transaction::
        where('type', 'credit')
        ->sum('amount');

        $totalDebitAmount = Transaction::
            where('type', 'debit')
            ->sum('amount');
        $balance = $totalCreditAmount - $totalDebitAmount;
        $amount = $request->qty * $request->price;
        if($balance < $amount){
            return redirect()->route('stock.add')
            ->with('error','Insufficient balance.');
        }
        $stocks = new Stocks();

		// Set the attributes
		$stocks->item = $request->item;
		$stocks->user_id = $request->user_id;
        $stocks->qty = $request->qty;
        $stocks->price = $request->price;
        $stocks->date = $request->date;
        $stocks->voucher = $request->voucher;
        $stocks->description = ($request->description != '') ? $request->description : '';
        $stocks->amount = $request->qty * $request->price;
        $stocks->save();
     
        return redirect()->route('stock.index')
                        ->with('success','Stock added successfully.');
    }
    function generateRandomEmail($name) {
        $username = strtolower(preg_replace('/[^a-z0-9]/i', '', $name)); // Remove non-alphanumeric characters and convert to lowercase
        $domain = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'example.com'][array_rand(['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'example.com'])]; // Randomly select a domain from the list
        return "{$username}@{$domain}";
    }
    public function edit(Request $request,$id)
    {
        $users = self::getUsers(); // Fetch users with role_id not equal to 1
		$stock = Stocks::where('id', $id)->first();
		if(!$stock){
			return redirect()->route('stock.index')
                        ->with('danger','No data found');
		}
        return view('admin.stock.edit',compact('stock','users'));
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
            'item' => 'required',
            'user_id' => 'required',
            'qty' => 'required',
            'price' => 'required|numeric|min:0|max:1000000',
            'date' => 'required|date',
        ]);

        $totalCreditAmount = Transaction::
        where('type', 'credit')
        ->sum('amount');

        $totalDebitAmount = Transaction::
            where('type', 'debit')
            ->sum('amount');

        // store
            $stocks = Stocks::find($id);
            $stocks->item = $request->item;
            $stocks->user_id = $request->user_id;
            $stocks->qty = $request->qty;
            $stocks->price = $request->price;
            $stocks->date = $request->date; 
            $stocks->voucher = $request->voucher;
            $stocks->description = ($request->description != '') ? $request->description : '';
            $amount = $request->qty * $request->price;

            // Recalculate balance
            if( $stocks->amount != $amount){
                $balance = $totalCreditAmount - $totalDebitAmount;
                $balance =  $stocks->amount + $balance;
                $balance =  $balance - $amount;
                // Check for insufficient balance
                if ($balance < 0) {
                    return redirect()->route('stock.edit', $id)->with('error', 'Insufficient balance. Available balance: ' . $balance);
                    die();
                }
            }
           
            $stocks->amount = $amount;

            $stocks->save();

            $transaction = Transaction::find($stocks->transaction_id);


            if ($transaction) {
                // Update transaction attributes if the transaction exists
                $transaction->amount = $request->qty * $request->price;
                $transaction->user_id = $request->user_id;
                // Update other transaction attributes if necessary
                $transaction->save();
            }
        return redirect()->route('stock.edit',$id)
                        ->with('success','Stock updated successfully');
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
      $stocks = Stocks::findorfail($id);
      $transaction_id = $stocks->transaction_id;
     // $stocks->delete();
      if($stocks->delete()){
        $transaction = Transaction::find($transaction_id);
        $transaction->delete();
      }
      return redirect()->route('stock.index')
                        ->with('success','Data deleted successfully');
    }
}
