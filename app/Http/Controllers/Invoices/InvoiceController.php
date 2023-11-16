<?php

namespace App\Http\Controllers\Invoices;

use App\Events\InvoiceNotification;
use App\Exports\InvoicesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\StatusRequest;
use App\Http\traits\attachement;
use App\Models\Invoice;
use App\Models\InvoiceAttachement;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use App\Notifications\NewInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{
    use attachement;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if($request->has('paid')){
            $invoices = Invoice::query()->where('status',1)->get();
            $title = 'الفواتير المدفوعة';
        }elseif($request->has('unpaid')){
            $invoices = Invoice::query()->where('status',0)->get();
            $title = 'الفواتير الغير مدفوعة';
        }elseif($request->has('partially')){
            $invoices = Invoice::query()->where('status',2)->get();
            $title = 'الفواتير المدفوعة جزئيا';
        }elseif($request->has('archive')){
            $invoices = Invoice::query()->onlyTrashed()->get();
            $title = 'الفواتير المؤرشفة';
        }else{
            $invoices = Invoice::all();
            $title = 'قائمة الفواتير';
        }
        return view('invoices.invoices',compact('invoices','title'));
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sections = Section::all();
        return view('invoices.add_invoices',compact('sections'));
    }

    public function getProducts($id)
    {

        $products = Product::query()->where("section_id", $id)->with('section')->pluck('name', 'id');
        return json_encode($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {
        $data = $request->validated();
        $data['user_id']= Auth::user()->id;
        Invoice::create($data);
       
        if($request->hasFile('file_name')){
            $request->validate([
                'file_name' => ['mimes:png,jpg,pdf,jpeg'],
            ]);
            $invoice_id = Invoice::latest()->first()->id;
            $invoice_number = $request->invoice_number;
            $file_name = $this->uploadFile($request->file_name,$invoice_number);
            InvoiceAttachement::create([
                'file_name' => $file_name,
                'invoice_id'=>$invoice_id
            ]);
        }
        $invoice = Invoice::latest()->first();
        $user =User::query()->where('status',1)->where('role_name','["owner"]')->get();
        Notification::send($user,new NewInvoice($invoice));

        $notification_count = DB::table('notifications')->where('read_at',null)->count();
        event(new InvoiceNotification($notification_count));

        flash()->addSuccess('تم اضافة الفاتورة بنجاح');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $invoice = Invoice::with('section')->findOrFail($id);
        $attachements = InvoiceAttachement::query()->where('invoice_id',$id)->get();
        $user_id =Auth::user()->id;
   	    $getNotificationId = DB::table('notifications')->where('data->id',$id)->where('notifiable_id',$user_id)->pluck('id');
        if(Arr::exists($getNotificationId, '0')){        
            DB::table('notifications')->where('id',$getNotificationId)->update([
                'read_at'=>now()
            ]);
        }

        return view('invoices.details_invoice',compact('invoice','attachements'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $invoice = Invoice::with('section')->findOrFail($id);
        $sections = Section::all();
        return view('invoices.edit_invoice',compact('invoice','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceRequest $request,$id)
    {
        //
        $data = $request->validated();
        $invoice = Invoice::findOrFail($id);
        $invoice->update($data);
        flash()->addSuccess('تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        //
        $id = $request->invoice_id;
        //dd($id);
        $invoice = Invoice::findOrFail($id);
        $attacement = InvoiceAttachement::with('invoice')->where('invoice_id',$id)->first();
        if($attacement){
            if($attacement->file_name != null){
                Storage::deleteDirectory('public/attachements/'.$attacement->invoice->invoice_number);
            }
        }
        $invoice->forceDelete();
        flash()->addSuccess('تم حذف الفاتورة بنجاح');
        return redirect()->back();
    }

    public function status($id){
        $invoice = Invoice::with('section')->where('id',$id)->first();
        return view('invoices.status_update',compact('invoice'));
    }

    public function updateStatus(StatusRequest $request,$id){
        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'payment_date'=>$request->payment_date,
            'status'=>$request->status
        ]);
        flash()->addSuccess('تم تغيير حالة الدفع بنجاح');
        return redirect()->back();
    }

    public function print($id)
    {
        $invoice = Invoice::with('section')->where('id',$id)->first();
        return view('invoices.print_invoice',compact('invoice'));
    }

    public function export(){
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function MarkAsRead_all(){
        $userUnReadNotifications = Auth::user()->unreadNotifications;
        if($userUnReadNotifications){
            $userUnReadNotifications->markAsRead();
            return redirect()->back();
        }
    }

}
