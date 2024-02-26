<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\EmailMessage;
use App\Mail\CustomEmail;
use App\Utils\helpers;
use App\Models\PaymentMethod;
use App\Models\Account;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Supplier;
use App\Models\PaymentPurchase;
use App\Mail\Payment_Purchase;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Config;
use DB;
use PDF;

class PaymentPurchasesController extends Controller
{

    protected $currency;
    protected $symbol_placement;

    public function __construct()
    {
        $helpers = new helpers();
        $this->currency = $helpers->Get_Currency();
        $this->symbol_placement = $helpers->get_symbol_placement();

    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('payment_purchases_add')){

            $request->validate([
                'purchase_id'  => 'required',
                'date'  => 'required',
                'payment_method_id'  => 'required',
            ]);


            if($request['amount'] > 0){

                \DB::transaction(function () use ($request) {
                    $purchase = Purchase::findOrFail($request['purchase_id']);
            
                    $total_paid = $purchase->paid_amount + $request['amount'];
                    $due = $purchase->GrandTotal - $total_paid;

                    if ($due <= 0.0) {
                        $payment_status = 'paid';
                    } 
                    else if ($due !== $purchase->GrandTotal) {
                        $payment_status = 'partial';
                    } 
                    else if ($due === $purchase->GrandTotal) {
                        $payment_status = 'unpaid';
                    }

                    PaymentPurchase::create([
                        'purchase_id' => $request['purchase_id'],
                        'account_id'  => $request['account_id']?$request['account_id']:NULL,
                        'Ref'         => $this->generate_random_code_payment(),
                        'date'        => $request['date'],
                        'payment_method_id'   => $request['payment_method_id'],
                        'amount'     => $request['amount'],
                        'change'      => 0,
                        'notes'       => $request['notes'],
                        'user_id'     => Auth::user()->id,
                    ]);

                    $purchase->update([
                        'paid_amount'    => $total_paid,
                        'payment_status' => $payment_status,
                    ]);

                }, 10);

            }
           
            return response()->json(['success' => true]);

        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('payment_purchases_edit')){

            $request->validate([
                'date'  => 'required',
                'payment_method_id'  => 'required',
            ]);

            \DB::transaction(function () use ($id, $request) {
                $payment = PaymentPurchase::findOrFail($id);
                $purchase = Purchase::whereId($request['purchase_id'])->first();
                $old_total_paid = $purchase->paid_amount - $payment->amount;
                $new_total_paid = $old_total_paid + $request['amount'];

                $due = $purchase->GrandTotal - $new_total_paid;
                if ($due === 0.0 || $due < 0.0) {
                    $payment_status = 'paid';
                } 
                else if ($due !== $purchase->GrandTotal) {
                    $payment_status = 'partial';
                } 
                else if ($due === $purchase->GrandTotal) {
                    $payment_status = 'unpaid';
                }

                //update payment
                $payment->update([
                    'date'    => $request['date'],
                    'payment_method_id'      => $request['payment_method_id'],
                    'account_id'             => $request['account_id']?$request['account_id']:NULL,
                    'amount' => $request['amount'],
                    'notes'   => $request['notes'],
                ]);

                $purchase->paid_amount = $new_total_paid;
                $purchase->payment_status = $payment_status;
                $purchase->save();

            }, 10);

            return response()->json(['success' => true, 'message' => 'Payment Update successfully'], 200);

        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $user_auth = auth()->user();
		if ($user_auth->can('payment_purchases_delete')){

            \DB::transaction(function () use ($id) {
                $payment = PaymentPurchase::findOrFail($id);
        
                $purchase = Purchase::find($payment->purchase_id);
                $total_paid = $purchase->paid_amount - $payment->amount;
                $due = $purchase->GrandTotal - $total_paid;

                if ($due === 0.0 || $due < 0.0) {
                    $payment_status = 'paid';
                } else if ($due !== $purchase->GrandTotal) {
                    $payment_status = 'partial';
                } else if ($due === $purchase->GrandTotal) {
                    $payment_status = 'unpaid';
                }

                PaymentPurchase::whereId($id)->update([
                    'deleted_at' => Carbon::now(),
                ]);

                $purchase->update([
                    'paid_amount' => $total_paid,
                    'payment_status' => $payment_status,
                ]);

            }, 10);

            return response()->json(['success' => true, 'message' => 'Payment Delete successfully'], 200);

        }
        return abort('403', __('You are not authorized'));
    }

      //----------- Get Data for Create Payment purchase --------------\\

    public function get_data_create(Request $request, $id)
    {
         
        $Purchase = Purchase::findOrFail($id);
        $due = number_format($Purchase->GrandTotal - $Purchase->paid_amount, 2, '.', '');

        $payment_methods = PaymentMethod::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','title']);
        $accounts = Account::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','account_name']);

        return response()->json(
        [
            'due' => $due,
            'payment_methods' => $payment_methods,
            'accounts' => $accounts,
        ]);

    }


    // generate_random_code_payment
    public function generate_random_code_payment()
    {
        $gen_code = 'INV/PO-' . date("Ymd") . '-'. substr(number_format(time() * mt_rand(), 0, '', ''), 0, 6);

        if (PaymentPurchase::where('Ref', $gen_code)->exists()) {
            $this->generate_random_code_payment();
        } else {
            return $gen_code;
        }
        
    }


      //----------- Payment Purchase PDF --------------\\

    public function Payment_purchase_pdf(Request $request, $id)
    {
        $payment = PaymentPurchase::with('payment_method','purchase', 'purchase.supplier')->findOrFail($id);

        $payment_data['purchase_Ref']   = $payment['purchase']->Ref;
        $payment_data['supplier_name']  = $payment['purchase']['supplier']->name;
        $payment_data['supplier_phone'] = $payment['purchase']['supplier']->phone;
        $payment_data['supplier_adr']   = $payment['purchase']['supplier']->address;
        $payment_data['supplier_email'] = $payment['purchase']['supplier']->email;
        $payment_data['amount']        = $payment->amount;
        $payment_data['Ref']            = $payment->Ref;
        $payment_data['date']           = Carbon::parse($payment->date)->format('d-m-Y H:i');
        $payment_data['Reglement']      = $payment['payment_method']->title;
  
        $settings = Setting::where('deleted_at', '=', null)->first();

        $Html = view('pdf.payments_purchase', [
            'setting' => $settings,
            'payment' => $payment_data,
        ])->render();

        $pdf = PDF::loadHTML($Html);

        return $pdf->download('payments_purchase.pdf');
    }



      //------------- Send Payment purchase on Email -----------\\
    public function SendEmail(Request $request)
    {
          //PaymentPurchase
          $payment = PaymentPurchase::with('purchase.supplier')->findOrFail($request->id);
 
          //settings
          $settings = Setting::where('deleted_at', '=', null)->first();
      
          //the custom msg of payment_received
          $emailMessage  = EmailMessage::where('name', 'payment_sent')->first();
  
          if($emailMessage){
              $message_body = $emailMessage->body;
              $message_subject = $emailMessage->subject;
          }else{
              $message_body = '';
              $message_subject = '';
          }
  
      
          $payment_number = $payment->Ref;
  
          $total_amount =  $this->render_price_with_symbol_placement(number_format($payment->amount, 2, '.', ','));
         
          $contact_name = $payment['purchase']['supplier']->name;
          $business_name = $settings->CompanyName;
  
          //receiver email
          $receiver_email = $payment['purchase']['supplier']->email;
  
          //replace the text with tags
          $message_body = str_replace('{contact_name}', $contact_name, $message_body);
          $message_body = str_replace('{business_name}', $business_name, $message_body);
          $message_body = str_replace('{payment_number}', $payment_number, $message_body);
          $message_body = str_replace('{total_amount}', $total_amount, $message_body);
 
         $email['subject'] = $message_subject;
         $email['body'] = $message_body;
         $email['company_name'] = $business_name;
 
         $this->Set_config_mail(); 
 
         $mail = Mail::to($receiver_email)->send(new CustomEmail($email));
 
         return $mail;
    }

     // Set config mail
     public function Set_config_mail()
     {
         $config = array(
             'driver' => env('MAIL_MAILER'),
             'host' => env('MAIL_HOST'),
             'port' => env('MAIL_PORT'),
             'from' => array('address' => env('MAIL_FROM_ADDRESS'), 'name' =>  env('MAIL_FROM_NAME')),
             'encryption' => env('MAIL_ENCRYPTION'),
             'username' => env('MAIL_USERNAME'),
             'password' => env('MAIL_PASSWORD'),
             'sendmail' => '/usr/sbin/sendmail -bs',
             'pretend' => false,
             'stream' => [
                 'ssl' => [
                     'allow_self_signed' => true,
                     'verify_peer' => false,
                     'verify_peer_name' => false,
                 ],
             ],
         );
         Config::set('mail', $config);
 
     }
 


     // render_price_with_symbol_placement

    public function render_price_with_symbol_placement($amount) {

        if ($this->symbol_placement == 'before') {
            return $this->currency . ' ' . $amount;
        } else {
            return $amount . ' ' . $this->currency;
        }
    }
    
}
