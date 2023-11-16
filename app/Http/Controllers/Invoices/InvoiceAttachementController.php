<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Http\traits\attachement;
use App\Models\InvoiceAttachement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachementController extends Controller
{
    use attachement;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'file_name' => ['mimes:png,jpg,pdf,jpeg'],
        ]);
        $invoice_number = $request->invoice_number;
        $file_name = $this->uploadFile($request->file_name,$invoice_number);
        InvoiceAttachement::create([
            'file_name' => $file_name,
            'invoice_id'=>$request->invoice_id
        ]);
        flash()->addSuccess('تم اضافة المرفق بنجاح');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return Storage::disk('attachements')->get('public/attachements/'.$id);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        //
        //dd($request->all());
        $attachement = InvoiceAttachement::findOrFail($id);
        $attachement->delete();
        Storage::delete('public/attachements/'.$request->invoice_number.'/'.$request->file_name);
        flash()->addSuccess('تم حذف المرفق بنجاح');
        return redirect()->back();
    }

    public function download($invoice_number,$file_name)
    {
        //return response()->download(public_path('storage/attachements/'.$invoice_number.'/'.$file_name));
        return Storage::download('public/attachements/'.$invoice_number.'/'.$file_name);
      
    }

    public function view($invoice_number,$file_name)
    {
        $st="storage/attachements";
        $pathToFile = public_path($st.'/'.$invoice_number.'/'.$file_name);
        return response()->file($pathToFile);
      
    }
}
