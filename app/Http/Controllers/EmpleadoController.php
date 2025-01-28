<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Storage;
 
class EmpleadoController extends Controller
{
    public function index() {
        return view('empleados.index');
    }

 
    public function fetchAll() {
        $employee = Empleado::all();
        $output = '';
        if ($employee->count() > 0) {
            $output .= '<table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>Nombre y Apellido</th>              
                <th>E-mail</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($employee as $rs) {
                $output .= '<tr>
                <td>' . $rs->id . '</td>
                <td><img src="storage/images/' . $rs->avatar . '" width="50" class="img-thumbnail rounded-circle"></td>
                <td>' . $rs->nombre . ' ' . $rs->apellido . '</td>
                <td>' . $rs->email . '</td>
                <td>
                  <a href="#" id="' . $rs->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><i class="bi-pencil-square h4"></i></a>
                 
                  <a href="#" id="' . $rs->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No hay registros en la base de datos!</h1>';
        }
    }
 
    // insert a new employee ajax request
    public function store(Request $request) {
        $file = $request->file('avatar');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/images', $fileName); //php artisan storage:link
 
        $empData = ['nombre' => $request->txtNombre, 'apellido' => $request->txtApellido, 'email' => $request->email, 'avatar' => $fileName];
        Empleado::create($empData);
        return response()->json([
            'status' => 200,
        ]);
    }
 
    // edit an employee ajax request
    public function edit(Request $request) {
        $id = $request->id;
        $emp = Empleado::find($id);
        return response()->json($emp);
    }
 
    // update an employee ajax request
    public function update(Request $request) {
        $fileName = '';
        $emp = Empleado::find($request->emp_id);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            if ($emp->avatar) {
                Storage::delete('public/images/' . $emp->avatar);
            }
        } else {
            $fileName = $request->emp_avatar;
        }
 
        $empData = ['nombre' => $request->txtNombre, 'apellido' => $request->txtApellido, 'email' => $request->email, 'avatar' => $fileName];
 
        $emp->update($empData);
        return response()->json([
            'status' => 200,
        ]);
    }
 
    // delete an employee ajax request
    public function delete(Request $request) {
        $id = $request->id;
        $emp = Empleado::find($id);
        if (Storage::delete('public/images/' . $emp->avatar)) {
            Empleado::destroy($id);
        }
    }
}