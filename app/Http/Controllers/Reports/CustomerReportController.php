<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class CustomerReportController extends Controller
{
    //
    public function index()
    {

        $sections = Section::all();
        return view('reports.customers_report', compact('sections'));
    }

    public function search(Request $request)
    {

        // في حالة البحث بدون التاريخ

        if ($request->section_id && $request->product && $request->start_at == '' && $request->end_at == '') {
            $invoices = Invoice::where('section_id', '=', $request->section_id)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
        }

        // في حالة البحث بتاريخ

        else {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
            $invoices = Invoice::whereBetween('invoice_date', [$start_at, $end_at])->where('section_id', '=', $request->section_id)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
        }
    }
}
