<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceAttachement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceArchivedController extends Controller
{
    //
    public function deletePermanently(Request $request,$id){
        $id =$request->invoice_id;
        $invoice = Invoice::withTrashed()->where('id',$id)->first();
        $attacement = InvoiceAttachement::with('invoice')->where('invoice_id',$id)->first();
        //dd($attacement);
        if($attacement){
            if($attacement->file_name != null){
                Storage::deleteDirectory('public/attachements/'.$invoice->invoice_number);
            }
        }
        $invoice->forceDelete();
        flash()->addSuccess('تم حذف الفاتورة بنجاح');
        return redirect()->back();
    }

    public function restore(Request $request,$id)
    {
        $id =$request->invoice_id;
        Invoice::withTrashed()->find($id)->restore();
  
        flash()->addSuccess('تم استعادة الفاتورة بنجاح');
        return redirect()->route('invoices.index');    
    }  

    public function archive(Request $request,$id){
        $id =$request->invoice_id;
        Invoice::query()->where('id',$id)->delete();
        flash()->addSuccess('تم نقل الفاتورة الى الأرشيف بنجاح');
        return redirect()->route('invoices.index');
    }
}
