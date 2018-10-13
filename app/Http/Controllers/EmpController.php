<?php
namespace App\Http\Controllers;

use App\Jobs\ExportData;
use App\Models\Employee;
use App\Models\EmployeeUpload;
use App\Models\Role;
use App\Models\UserRole;
use App\Promotion;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class EmpController extends Controller
{
    public function addEmployee()
    {
        $roles = Role::get();

        return view('hrms.employee.add', compact('roles'));
    }

    public function processEmployee(Request $request)
    {
        $filename = public_path('photos/a.png');
        if ($request->file('photo')) {
            $file             = $request->file('photo');
            $filename         = str_random(12);
            $fileExt          = $file->getClientOriginalExtension();
            $allowedExtension = ['jpg', 'jpeg', 'png'];
            $destinationPath  = public_path('photos');
            if (!in_array($fileExt, $allowedExtension)) {
                return redirect()->back()->with('message', 'Extension not allowed');
            }
            $filename = $filename . '.' . $fileExt;
            $file->move($destinationPath, $filename);

        }

        $user           = new User;
        $user->name     = $request->emp_name;
        $user->email    = str_replace(' ', '_', $request->emp_name) . '@rmsolution.tk';
        $user->password = bcrypt('123456');
        $user->save();

        $emp                       = new Employee;
        $emp->photo                = $filename;
        $emp->name                 = $request->emp_name;
        $emp->code                 = $request->emp_code;
      
        $emp->gender               = $request->gender;
        $emp->date_of_birth        = date_format(date_create($request->dob), 'Y-m-d');
        $emp->date_of_joining      = date_format(date_create($request->doj), 'Y-m-d');
        $emp->number               = $request->number;
        $emp->qualification        = $request->qualification;
        $emp->emergency_number     = $request->emergency_number;

        $emp->father_name          = $request->father_name;
        $emp->current_address      = $request->current_address;
        $emp->permanent_address    = $request->permanent_address;
      
        $emp->probation_period     = $request->probation_period;
        $emp->date_of_confirmation = date_format(date_create($request->date_of_confirmation), 'Y-m-d');
        $emp->department           = $request->department;
        $emp->salary               = $request->salary;
        $emp->account_number       = $request->account_number;
        $emp->bank_name            = $request->bank_name;
      
        $emp->date_of_resignation  = date_format(date_create($request->date_of_resignation), 'Y-m-d');
        $emp->notice_period        = $request->notice_period;
      
        $emp->full_final           = $request->full_final;
        $emp->user_id              = $user->id;
        $emp->save();

        $userRole          = new UserRole();
        $userRole->role_id = $request->role;
        $userRole->user_id = $user->id;
        $userRole->save();
        //$emp->userrole()->create(['role_id' => $request->role]);

        return json_encode(['title' => 'Success', 'message' => 'Employee added successfully', 'class' => 'modal-header-success']);

    }

    public function showEmployee()
    {
        $emps   = User::with('employee', 'role.role')->paginate(15);
        $column = '';
        $string = '';
        return view('hrms.employee.show_emp', compact('emps', 'column', 'string'));
    }

    public function showEdit($id)
    {
        //$emps = Employee::whereid($id)->with('userrole.role')->first();
        $emps = User::where('id', $id)->with('employee', 'role.role')->first();

        $roles = Role::get();
        return view('hrms.employee.add', compact('emps', 'roles'));
    }

    public function doEdit(Request $request, $id)
    {
        $filename = public_path('photos/a.png');
        if ($request->file('photo')) {
            $file             = $request->file('photo');
            $filename         = str_random(12);
            $fileExt          = $file->getClientOriginalExtension();
            $allowedExtension = ['jpg', 'jpeg', 'png'];
            $destinationPath  = public_path('photos');
            if (!in_array($fileExt, $allowedExtension)) {
                return redirect()->back()->with('message', 'Extension not allowed');
            }
            $filename = $filename . '.' . $fileExt;
            $file->move($destinationPath, $filename);

        }

        $photo             = $request->$filename;
        $emp_name          = $request->name;
        $emp_code          = $request->code;
        $gender            = $request->gender;
        $dob               = date_format(date_create($request->date_of_birth), 'Y-m-d');
        $doj               = date_format(date_create($request->date_of_joining), 'Y-m-d');
        $mob_number        = $request->number;
        $qualification     = $request->qualification;
        $emer_number       = $request->emergency_number;
       
        $father_name       = $request->father_name;
        $address           = $request->current_address;
        $permanent_address = $request->permanent_address;

        $prob_period       = $request->probation_period;
        $doc               = date_format(date_create($request->date_of_confirmation), 'Y-m-d');
        $department        = $request->department;
        $salary            = $request->salary;
        $account_number    = $request->account_number;
        $bank_name         = $request->bank_name;
       
        $dor               = date_format(date_create($request->date_of_resignation), 'Y-m-d');
        $notice_period     = $request->notice_period;
      
        $full_final        = $request->full_final;

        //$edit = Employee::findOrFail($id);
        $edit = Employee::where('user_id', $id)->first();

        if (!empty($photo)) {
            $edit->photo = $photo;
        }
        if (!empty($emp_name)) {
            $edit->name = $emp_name;
        }
        if (!empty($emp_code)) {
            $edit->code = $emp_code;
        }
        
        if (!empty($gender)) {
            $edit->gender = $gender;
        }
        if (!empty($dob)) {
            $edit->date_of_birth = $dob;
        }
        if (!empty($doj)) {
            $edit->date_of_joining = $doj;
        }
        if (!empty($mob_number)) {
            $edit->number = $mob_number;
        }
        if (!empty($qualification)) {
            $edit->qualification = $qualification;
        }
        if (!empty($emer_number)) {
            $edit->emergency_number = $emer_number;
        }
        
        if (!empty($father_name)) {
            $edit->father_name = $father_name;
        }
        if (!empty($address)) {
            $edit->current_address = $address;
        }
        if (!empty($permanent_address)) {
            $edit->permanent_address = $permanent_address;
        }
       
        if (!empty($prob_period)) {
            $edit->probation_period = $prob_period;
        }
        if (!empty($doc)) {
            $edit->date_of_confirmation = $doc;
        }
        if (!empty($department)) {
            $edit->department = $department;
        }
        if (!empty($salary)) {
            $edit->salary = $salary;
        }
        if (!empty($account_number)) {
            $edit->account_number = $account_number;
        }
        if (!empty($bank_name)) {
            $edit->bank_name = $bank_name;
        }
       
        if (!empty($dor)) {
            $edit->date_of_resignation = $dor;
        }
        if (!empty($notice_period)) {
            $edit->notice_period = $notice_period;
        }
       
        if (!empty($full_final)) {
            $edit->full_final = $full_final;
        }

        $edit->save();

        return json_encode(['title' => 'Success', 'message' => 'Employee details successfully updated', 'class' => 'modal-header-success']);
    }

    public function doDelete($id)
    {

        $emp = Employee::find($id);
        $emp->delete();

        \Session::flash('flash_message', 'Employee successfully Deleted!');

        return redirect()->back();
    }

    public function importFile()
    {
        return view('hrms.employee.upload');
    }

    public function uploadFile(Request $request)
    {
        $files = Input::file('upload_file');

        /* try {*/
        foreach ($files as $file) {
            Excel::load($file, function ($reader) {
                $rows = $reader->get(['emp_name', 'emp_code', 'role', 'gender', 'dob', 'doj', 'mob_number', 'qualification', 'emer_number', 'father_name', 'address', 'permanent_address', 'prob_period', 'doc', 'department', 'salary', 'account_number', 'bank_name', 'dor', 'notice_period', 'full_final']);

                foreach ($rows as $row) {
\Log::info($row->role);
                    $user           = new User;
                    $user->name     = $row->emp_name;
                    $user->email    = str_replace(' ', '_', $row->emp_name) . '@rmsolution.tk';
                    $user->password = bcrypt('123456');
                    $user->save();

                    $attachment         = new Employee();
                    $attachment->photo  = '/img/Emp.jpg';
                    $attachment->name   = $row->emp_name;
                    $attachment->code   = $row->emp_code;
                 

                    if (empty($row->gender)) {
                        $attachment->gender = 'Not Exist';
                    } else {
                        $attachment->gender = $row->gender;
                    }
                    if (empty($row->dob)) {
                        $attachment->date_of_birth = '0000-00-00';
                    } else {
                        $attachment->date_of_birth = date('Y-m-d',strtotime($row->dob));
                    }
                    if (empty($row->doj)) {
                        $attachment->date_of_joining = '0000-00-00';
                    } else {
                        $attachment->date_of_joining = date('Y-m-d', strtotime($row->doj));
                    }
                    if (empty($row->mob_number)) {
                        $attachment->number = '1234567890';
                    } else {
                        $attachment->number = $row->mob_number;
                    }
                    if (empty($row->qualification)) {
                        $attachment->qualification = 'Not Exist';
                    } else {
                        $attachment->qualification = $row->qualification;
                    }
                    if (empty($row->emer_number)) {
                        $attachment->emergency_number = '1234567890';
                    } else {
                        $attachment->emergency_number = $row->emer_number;
                    }
                   
                    if (empty($row->father_name)) {
                        $attachment->father_name = 'Not Exist';
                    } else {
                        $attachment->father_name = $row->father_name;
                    }
                    if (empty($row->address)) {
                        $attachment->current_address = 'Not Exist';
                    } else {
                        $attachment->current_address = $row->address;
                    }
                    if (empty($row->permanent_address)) {
                        $attachment->permanent_address = 'Not Exist';
                    } else {
                        $attachment->permanent_address = $row->permanent_address;
                    }

                    if (empty($row->prob_period)) {
                        $attachment->probation_period = 'Not Exist';
                    } else {
                        $attachment->probation_period = $row->prob_period;
                    }
                    if (empty($row->doc)) {
                        $attachment->date_of_confirmation = '0000-00-00';
                    } else {
                        $attachment->date_of_confirmation = date('Y-m-d', strtotime($row->doc));
                    }
                    if (empty($row->department)) {
                        $attachment->department = 'Not Exist';
                    } else {
                        $attachment->department = $row->department;
                    }
                    if (empty($row->salary)) {
                        $attachment->salary = '00000';
                    } else {
                        $attachment->salary = $row->salary;
                    }
                    if (empty($row->account_number)) {
                        $attachment->account_number = 'Not Exist';
                    } else {
                        $attachment->account_number = $row->account_number;
                    }
                    if (empty($row->bank_name)) {
                        $attachment->bank_name = 'Not Exist';
                    } else {
                        $attachment->bank_name = $row->bank_name;
                    }
                    
                    if (empty($row->dor)) {
                        $attachment->date_of_resignation = '0000-00-00';
                    } else {
                        $attachment->date_of_resignation = date('Y-m-d', strtotime($row->dor));
                    }
                    if (empty($row->notice_period)) {
                        $attachment->notice_period = 'Not exist';
                    } else {
                        $attachment->notice_period = $row->notice_period;
                    }

                    if (empty($row->full_final)) {
                        $attachment->full_final = 'Not exist';
                    } else {
                        $attachment->full_final = $row->full_final;
                    }
                    $attachment->user_id = $user->id;
                    $attachment->save();

                    $userRole          = new UserRole();
                    $userRole->role_id = convertRole($row->role);
                    $userRole->user_id = $user->id;
                    $userRole->save();

                }
                return 1;
                //return redirect('upload_form');*/
            });

        }
        /*catch (\Exception $e) {
           return $e->getMessage();*/

        \Session::flash('success', ' Employee details uploaded successfully.');

        return redirect()->back();
    }

    public function searchEmployee(Request $request)
    {
        $string = $request->string;
        $column = $request->column;
        if ($request->button == 'Search') {
            if ($string == '' && $column == '') {
                return redirect()->to('employee-manager');
            } elseif ($column == 'email') {
                $emps = User::with('employee')->where($column, $string)->paginate(20);
            } else {
                $emps = User::whereHas('employee', function ($q) use ($column, $string) {
                    $q->whereRaw($column . " like '%" . $string . "%'");
                })->with('employee')->paginate(20);
            }

            return view('hrms.employee.show_emp', compact('emps', 'column', 'string'));
        } else {
            if ($column == '') {
                $emps = User::with('employee')->get();
            } elseif ($column == 'email') {
                $emps = User::with('employee')->where($request->column, $request->string)->paginate(20);
            } else {
                $emps = User::whereHas('employee', function ($q) use ($column, $string) {
                    $q->whereRaw($column . " like '%" . $string . "%'");
                })->with('employee')->get();
            }

            $fileName = 'Employee_Listing_' . rand(1, 1000) . '.xlsx';
            $filePath = storage_path('export/') . $fileName;
            $file     = new \SplFileObject($filePath, "a");
            // Add header to csv file.
            $headers = ['id', 'photo', 'code', 'name', 'gender', 'date_of_birth', 'date_of_joining', 'number', 'qualification', 'emergency_number', 'father_name', 'current_address', 'permanent_address', 'probation_period', 'date_of_confirmation', 'department', 'salary', 'account_number', 'bank_name', 'date_of_resignation', 'notice_period', 'full_final', 'user_id', 'created_at', 'updated_at'];
            $file->fputcsv($headers);
            foreach ($emps as $emp) {
                $file->fputcsv([
                    $emp->id,
                    (
                        $emp->employee->photo) ? $emp->employee->photo : 'Not available',
                    $emp->employee->code,
                    $emp->employee->name,
                  
                    $emp->employee->gender,
                    $emp->employee->date_of_birth,
                    $emp->employee->date_of_joining,
                    $emp->employee->number,
                    $emp->employee->qualification,
                    $emp->employee->emergency_number,
                
                    $emp->employee->father_name,
                    $emp->employee->current_address,
                    $emp->employee->permanent_address,
                 
                    $emp->employee->probation_period,
                    $emp->employee->date_of_confirmation,
                    $emp->employee->department,
                    $emp->employee->salary,
                    $emp->employee->account_number,
                    $emp->employee->bank_name,
                  
                    $emp->employee->date_of_resignation,
                    $emp->employee->notice_period,
                  
                    $emp->employee->full_final
                ]);
            }

            return response()->download(storage_path('export/') . $fileName);
        }
    }


    public function showDetails()
    {
        $emps = User::with('employee')->paginate(15);
        return view('hrms.employee.show_bank_detail', compact('emps'));
    }

    public function updateAccountDetail(Request $request)
    {
        try {
            $model                    = Employee::where('id', $request->employee_id)->first();
            $model->bank_name         = $request->bank_name;
            $model->account_number    = $request->account_number;
          
            $model->save();
            return json_encode('success');
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' on ' . $e->getLine() . ' in ' . $e->getFile());
            return json_encode('failed');
        }

    }

    public function doPromotion()
    {
        $emps  = User::get();
        $roles = Role::get();
        return view('hrms.promotion.add_promotion', compact('emps', 'roles'));
    }

    public function getPromotionData(Request $request)
    {
        $result = Employee::with('userrole.role')->where('id', $request->employee_id)->first();
        if ($result) {
            $result = ['salary' => $result->salary, 'designation' => $result->userrole->role->name];
        }
        return json_encode(['status' => 'success', 'data' => $result]);
    }

    public function processPromotion(Request $request)
    {

        $newDesignation  = Role::where('id', $request->new_designation)->first();
        $process         = Employee::where('id', $request->emp_id)->first();
        $process->salary = $request->new_salary;
        $process->save();

        \DB::table('user_roles')->where('user_id', $process->user_id)->update(['role_id' => $request->new_designation]);

        $promotion                    = new Promotion();
        $promotion->emp_id            = $request->emp_id;
        $promotion->old_designation   = $request->old_designation;
        $promotion->new_designation   = $newDesignation->name;
        $promotion->old_salary        = $request->old_salary;
        $promotion->new_salary        = $request->new_salary;
        $promotion->date_of_promotion = date_format(date_create($request->date_of_promotion), 'Y-m-d');
        $promotion->save();

        \Session::flash('flash_message', 'Employee successfully Promoted!');
        return redirect()->back();
    }

    public function showPromotion()
    {
        $promotions = Promotion::with('employee')->paginate(10);
        return view('hrms.promotion.show_promotion', compact('promotions'));
    }

}
