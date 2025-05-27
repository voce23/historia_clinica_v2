<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use App\Models\Establecimiento;

class Index extends Component
{

    public $usuario_id, $nombre, $email, $role, $password;
    public $modoEditar = false;
    public $confirmandoEliminacionId = null;
    public $usuarioIdEditando = null;
    public $establecimiento_id;





    /**
     * Validation rules for user creation
     */
    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::defaults()],
            'role' => 'required|in:admin,medico,enfermera',
            'establecimiento_id' => 'nullable|exists:establecimientos,id',
            
        ];
    }

    /**
     * Create a new user.
     */
    public function crearUsuario()
    {
        $this->validate();

        User::create([
            'name' => $this->nombre,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'establecimiento_id' => $this->establecimiento_id,
        ]);

        session()->flash('message', 'Usuario creado exitosamente.');

        $this->reset(['nombre', 'email', 'password', 'role']);
    }

    /**
     * Render the component view with all users.
     */
    public function render()
{
    $establecimientos = Establecimiento::orderBy('nombre')->get();

    // Solo los usuarios del mismo establecimiento si no es admin
    $usuarios = auth()->user()->role === 'admin'
        ? User::with('establecimiento')->get()
        : User::with('establecimiento')
            ->where('establecimiento_id', auth()->user()->establecimiento_id)
            ->get();

    return view('livewire.usuarios.index', [
        'usuarios' => $usuarios,
        'establecimientos' => $establecimientos,
    ]);
}



    public function editar($id)
    {
        $usuario = User::findOrFail($id);

        $this->usuario_id = $usuario->id;
        $this->nombre = $usuario->name;
        $this->email = $usuario->email;
        $this->role = $usuario->role;
        $this->password = ''; // opcional

        $this->modoEditar = true;
    }

    public function actualizarUsuario()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->usuario_id,
            'role' => 'required|in:admin,medico,enfermera',
            'password' => 'nullable|min:6',
        ]);

        $usuario = User::findOrFail($this->usuario_id);
        $usuario->name = $this->nombre;
        $usuario->email = $this->email;
        $usuario->role = $this->role;

        if (!empty($this->password)) {
            $usuario->password = Hash::make($this->password);
            $usuario->establecimiento_id = $this->establecimiento_id;
        }
        

        $usuario->save();

        session()->flash('message', 'Usuario actualizado correctamente.');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['usuario_id', 'nombre', 'email', 'role', 'password', 'modoEditar']);
    }
    public function confirmarEliminacion($id)
    {
        $this->confirmandoEliminacionId = $id;
    }

    public function eliminarUsuario()
    {
        $usuario = User::find($this->confirmandoEliminacionId);

        if ($usuario) {
            $usuario->delete();
            session()->flash('message', 'Usuario eliminado correctamente.');
        }

        $this->confirmandoEliminacionId = null;
    }

    /**
     * Restablece los campos del formulario.
     */
    public function resetFormulario()
    {
        $this->reset(['nombre', 'email', 'password', 'role', 'modoEditar', 'usuarioIdEditando']);
    }
}
