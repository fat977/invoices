<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sections = Section::all();
        return view('sections.sections',compact('sections'));
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
    public function store(SectionRequest $request)
    {
        //
        $data = $request->validated();
        $data['created_by'] = Auth::user()->name;
        Section::create($data);
        flash()->addSuccess('تم اضافة القسم بنجاح');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(SectionRequest $request,$id)
    {
        //
        $section = Section::findOrFail($id);
        $data = $request->validated();
        $section->update($data);
        flash()->addSuccess('تم تعديل القسم بنجاح');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        //
        $id= $request->id;
        $section = Section::findOrFail($id);
        $section->delete();
        flash()->addSuccess('تم حذف القسم بنجاح');
        return redirect()->back();
    }
}
