<?php

use Livewire\Component;
use App\Models\CategoriaHijo;
use App\Models\Subcategoria;

new class extends Component
{
    public $nombre = '';
    public $categoria_hijo_id = '';
    public $editingId = null;
    public $openModal = false;

    protected $rules = [
        'nombre' => 'required|min:3',
        'categoria_hijo_id' => 'required|exists:categoria_hijos,id'
    ];

    public function open()
    {
        $this->resetForm();
        $this->openModal = true;
    }

    public function edit($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);

        $this->editingId = $subcategoria->id;
        $this->nombre = $subcategoria->nombre;
        $this->categoria_hijo_id = $subcategoria->categoria_hijo_id;

        $this->openModal = true;
    }

    public function close()
    {
        $this->resetForm();
        $this->openModal = false;
    }

    private function resetForm()
    {
        $this->reset(['nombre', 'categoria_hijo_id', 'editingId']);
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        Subcategoria::updateOrCreate(
            ['id' => $this->editingId],
            [
                'nombre' => $this->nombre,
                'categoria_hijo_id' => $this->categoria_hijo_id,
            ]
        );

        $this->close();
    }

    public function delete($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);

        if ($subcategoria->equipos()->exists()) {
            session()->flash('error', 'No se puede eliminar porque tiene equipos registrados.');
            return;
        }

        $subcategoria->delete();

        session()->flash('success', 'Subcategoría eliminada correctamente.');
    }

    public function getCategoriasProperty()
    {
        return CategoriaHijo::orderBy('nombre')->get();
    }

    public function getSubcategoriasProperty()
    {
        return Subcategoria::with('hijo')
            ->orderBy('nombre')
            ->get();
    }
};
?>

<div class="p-6">

    <h2 class="text-2xl font-bold mb-4">Subcategorías</h2>

    <button
        wire:click="open"
        class="bg-green-600 text-white px-4 py-2 rounded-lg"
    >
        + Nueva Subcategoría
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
                <th class="p-2">Categoría Hijo</th>
                <th class="p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($this->subcategorias as $subcategoria)
                <tr class="border-t">
                    <td class="p-2">{{ $subcategoria->nombre }}</td>

                    <td class="p-2">
                        {{-- 🔥 CORREGIDO --}}
                        {{ $subcategoria->hijo?->nombre ?? 'Sin categoría' }}
                    </td>

                    <td class="p-2 flex gap-2">
                        <button
                            wire:click="edit({{ $subcategoria->id }})"
                            class="bg-yellow-500 text-white px-2 py-1 rounded"
                        >
                            Editar
                        </button>

                        <button
                            wire:click="delete({{ $subcategoria->id }})"
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
                        No hay subcategorías registradas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- MODAL --}}
    @if($openModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50">
            <div class="bg-white p-6 rounded-xl w-96">

                <h2 class="text-lg font-bold mb-4">
                    {{ $editingId ? 'Editar' : 'Crear' }} Subcategoría
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

                {{-- 🔥 CORREGIDO --}}
                <select
                    wire:model="categoria_hijo_id"
                    class="w-full border rounded px-3 py-2"
                >
                    <option value="">Seleccionar Categoría Hijo</option>

                    @foreach($this->categorias as $categoria)
                        <option value="{{ $categoria->id }}">
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>

                @error('categoria_hijo_id')
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
