<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    
    public function index()
    {
        return Employee::select('id','email','phoneNumber', 'avatar','name', 	'salary')->get();
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
            'salary'=> 'required',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation pour l'avatar
        ]);

        // Créer un nouvel employé
        $employee = new Employee();
        $employee->email = $request->input('email');
        $employee->name = $request->input('name');
        $employee->phoneNumber = $request->input('phoneNumber');
        $employee->salary = $request->input('salary');

        // Gestion de l'avatar
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time().'.'.$avatar->getClientOriginalExtension();
            $avatar->storeAs('avatars', $avatarName); // Stocker l'avatar dans le dossier 'avatars'
            $employee->avatar = $avatarName;
        } else {
            // Avatar par défaut
            $defaultAvatar = 'default-avatar.png';
            $employee->avatar = $defaultAvatar;
        }

        // Enregistrer l'employé
        $employee->save();

        return response()->json([
            'message'=>'Employee added successfully'
        ]);
    }

    // Autres méthodes existantes...


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