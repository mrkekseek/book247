<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class BookingInvoice extends Model
{
    protected $table = 'booking_invoices';

    public static $attributeNames = array(
        'booking_id'    => 'Booking ID',
        'invoice_number'=> 'Invoice Number',
        'other_details' => 'Invoice Details',
        'status'        => 'Invoice Status',
    );

    public static $message = array();

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'other_details',
        'status',
    ];

    public static function next_invoice_number(){
        $booking = BookingInvoice::select('invoice_number')->orderBy('invoice_number', 'desc')->get()->first();
        if ($booking){
            return ($booking->invoice_number+1);
        }
        else{
            return 1100;
        }
    }

    public function add_invoice_item($fillable){
        $fillable['booking_invoice_id'] = $this->id;

        $validator = Validator::make($fillable, BookingInvoiceItem::rules('POST'), BookingInvoiceItem::$message, BookingInvoiceItem::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $the_invoice_item = BookingInvoiceItem::create($fillable);

            if ($the_invoice_item){
                return ['success' => true, 'message' => 'Item Added'];
            }
            else{
                return ['success' => false, 'errors' => 'Error adding item'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'errors' => 'Error while adding item'];
        }
    }

    public function get_invoice_total(){
        $invoiceItems = BookingInvoiceItem::where('booking_invoice_id','=',$this->id)->get();
        if ($invoiceItems){
            $total = [
                'vat'       => [],
                'total_vat'      => 0,
                'total_discount' => 0,
                'total_price'    => 0,
                'total_sum'      => 0,
            ];
            foreach($invoiceItems as $item){
                $tdisc = (($item->discount * $item->price) / 100) * $item->quantity;
                $total['total_discount'] += $tdisc;

                $tprice = $item->price * $item->quantity;
                $total['total_price'] += $tprice;

                $tvat = (($tprice - $tdisc) * $item->vat) / 100;
                $total['total_vat'] += $tvat;
                if (isset($total['vat'][$item->vat])){
                    $total['vat'][$item->vat] += $tvat;
                }
                else{
                    $total['vat'][$item->vat] = $tvat;
                }

                $total['total_sum'] += ($tprice - $tdisc) + $tvat;
            }

            return $total;
        }
        else{
            return 0;
        }
    }

    public function add_transaction($fillable){
        $fillable['booking_invoice_id'] = $this->id;

        $validator = Validator::make($fillable, BookingFinancialTransaction::rules('POST'), BookingFinancialTransaction::$message, BookingFinancialTransaction::$attributeNames);
        if ($validator->fails()){
            return [
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()];
        }

        $transaction = BookingFinancialTransaction::
            where('booking_invoice_id','=',$this->booking_invoice_id)
            ->whereIn('status',['pending','processing'])
            ->orderBy('created_at','DESC')->get()->first();
        if ($transaction){
            // we cancel pending and processing transactions and add the new one
            $transaction->status='cancelled';
            $transaction->save();
        }

        try {
            $newTransaction = BookingFinancialTransaction::create($fillable);
            return [
                'success'=>true,
                'transaction_id' => $newTransaction->id,
                'transaction_status' => $newTransaction->status];
        }
        catch (Exception $e) {
            return [
                'success' => false,
                'errors' => 'Error Creating Transaction!'];
        }
    }

    public static function rules($method, $id=0){
        switch($method){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'booking_id'        => 'required|exists:bookings,id',
                    'invoice_number'    => 'required|unique:booking_invoices,invoice_number',
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'booking_id'        => 'required|exists:bookings,id',
                    'invoice_number'    => 'required|unique:booking_invoices,invoice_number'.($id ? ",$id,id" : ''),
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered'
                ];
            }
            default:break;
        }
    }

    public function financial_transaction(){
        return $this->hasMany('App\BookingFinancialTransaction', 'booking_invoice_id', 'id')->orderBy('created_at','DESC');
    }

    public function invoice_items(){
        return $this->hasMany('App\BookingInvoiceItem', 'booking_invoice_id', 'id')->orderBy('created_at','DESC');
    }
}
