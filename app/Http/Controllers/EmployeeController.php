<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        $employee = new Employee;
        $employee->name = $request->name;
        $employee->job_name = $request->job_name;
        $employee->manager_name = $request->manager_name;
        $employee->hire_date = $request->hire_date;
        $employee->salary = $request->salary;
        $employee->commission = $request->commission;

        $employee->save();

        return "Data has been Created!";
    }

    public function all()
    {
        $employee = Employee::paginate(5);

        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        $employee->name = $request->name;
        $employee->job_name = $request->job_name;
        $employee->manager_name = $request->manager_name;
        $employee->hire_date = $request->hire_date;
        $employee->salary = $request->salary;
        $employee->commission = $request->commission;

        $employee->save();

        return "Data has been Updated!";
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        $employee->delete();

        return "Data has been Deleted!";
    }
}
