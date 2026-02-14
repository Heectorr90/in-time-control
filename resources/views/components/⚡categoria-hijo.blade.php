<?php

use Livewire\Component;
use App\Models\CategoriaPadre;
use App\Models\CategoriaHijo;

new class extends Component
{
    public $nombre = '';
    public $categoria_padre_id = '';
    public $editingId = null;
    public $openModal = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'categoria_padre_id' => 'required|exists:categoria_padres,id'
    ];

    public function open()
    {
        $this->resetForm();
        $this->openModal = true;
    }

    public function edit($id)
    {
        $categoria = CategoriaHijo::findOrFail($id);

        $this->editingId = $categoria->id;
        $this->nombre = $categoria->nombre;
        $this->categoria_padre_id = $categoria->categoria_padre_id;

        $this->openModal = true;
    }

    public function close()
    {
        $this->resetForm();
        $this->openModal = false;
    }

    private function resetForm()
    {
        $this->reset(['nombre', 'categoria_padre_id', 'editingId']);
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        CategoriaHijo::updateOrCreate(
            ['id' => $this->editingId],
            [
                'nombre' => $this->nombre,
                'categoria_padre_id' => $this->categoria_padre_id,
            ]
        );

        $this->close();
    }

    public function delete($id)
    {
        $categoria = CategoriaHijo::findOrFail($id);

        if ($categoria->subcategorias()->exists()) {
            session()->flash('error', 'No se puede eliminar porque tiene subcategorías asociadas.');
            return;
        }

        $categoria->delete();

        session()->flash('success', 'Categoría hijo eliminada correctamente.');
    }

    public function getPadresProperty()
    {
        return CategoriaPadre::orderBy('nombre')->get();
    }

    public function getHijosProperty()
    {
        return CategoriaHijo::with('padre')
            ->orderBy('nombre')
            ->get();
    }
};
?>
<div class="p-6">

    <h2 class="text-2xl font-bold mb-4">Categorías Hijos</h2>

    <button
        wire:click="open"
        class="bg-green-600 text-white px-4 py-2 rounded-lg"
    >
        + Nueva Categoría Hijo
    </button>
    @if(session()->has('error'))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mt-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session()->has('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mt-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full mt-6 border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">Nombre</th>
                <th class="p-2">Categoría Padre</th>
                <th class="p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($this->hijos as $hijo)
                <tr class="border-t">
                    <td class="p-2">{{ $hijo->nombre }}</td>

                    <td class="p-2">
                        {{ $hijo->padre?->nombre ?? 'Sin categoría padre' }}
                    </td>

                    <td class="p-2 flex gap-2">
                        <button
                            wire:click="edit({{ $hijo->id }})"
                            class="bg-yellow-500 text-white px-2 py-1 rounded"
                        >
                            Editar
                        </button>

                        <button
                            wire:click="delete({{ $hijo->id }})"
                            onclick="confirm('¿Eliminar esta categoría?') || event.stopImmediatePropagation()"
                            class="bg-red-600 text-white px-2 py-1 rounded"
                        >
                            Eliminar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-4 text-center text-gray-500">
                        No hay categorías registradas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- MODAL --}}
    @if($openModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-xl w-96">

                <h2 class="text-lg font-bold mb-4">
                    {{ $editingId ? 'Editar' : 'Crear' }} Categoría Hijo
                </h2>

                <input
                    type="text"
                    wire:model="nombre"
                    placeholder="Nombre"
                    class="w-full border rounded px-3 py-2 mb-2"
                >

                @error('nombre')
                    <span class="text-red-500 text-sm block mb-2">
                        {{ $message }}
                    </span>
                @enderror

                <select
                    wire:model="categoria_padre_id"
                    class="w-full border rounded px-3 py-2"
                >
                    <option value="">Seleccionar Categoría Padre</option>

                    @foreach($this->padres as $padre)
                        <option value="{{ $padre->id }}">
                            {{ $padre->nombre }}
                        </option>
                    @endforeach
                </select>

                @error('categoria_padre_id')
                    <span class="text-red-500 text-sm block mt-2">
                        {{ $message }}
                    </span>
                @enderror

                <div class="flex justify-end gap-2 mt-4">
                    <button
                        wire:click="close"
                        class="bg-gray-300 px-4 py-2 rounded"
                    >
                        Cancelar
                    </button>

                    <button
                        wire:click="save"
                        class="bg-green-600 text-white px-4 py-2 rounded"
                    >
                        Guardar
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
