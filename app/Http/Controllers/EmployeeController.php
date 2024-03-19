<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    
    public function index()
    {
        return Employee::select('id','email','phoneNumber', 	'name', 	'salary')->get();
    }

    
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'=>  'required',
            'name'	=>  'required',
            'phoneNumber'=>'required',
           'salary'=> 'required' ,

        ]);
        Employee::create($request->post());

        return response()->json([
            'message'=>'Item add succsufly'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee )
    {
        return   response()->json([
            'employee'=>$employee
        ]);
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
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'name' => 'required',
            'phoneNumber'=>'required',
            'salary' => 'required|numeric',
        ]);
        $employee->fill($request->post())->update();
        return response()->json([
            'message'=>'Item update succsufly'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json([
            'message'=>'Item deleted succsufly'
        ]);
    }
}