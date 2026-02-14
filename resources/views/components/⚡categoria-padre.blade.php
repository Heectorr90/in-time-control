<?php

use Livewire\Component;
use App\Models\CategoriaPadre;

new class extends Component
{
    public $nombre;
    public $editingId = null;
    public $openModal = false;

    protected $rules = [
        'nombre' => 'required|min:3|unique:categoria_padres,nombre'
    ];

    public function open()
    {
        $this->resetForm();
        $this->openModal = true;
    }

    public function edit($id)
    {
        $categoria = CategoriaPadre::findOrFail($id);

        $this->editingId = $categoria->id;
        $this->nombre = $categoria->nombre;
    }

    public function save()
    {
        $this->validate();

        CategoriaPadre::updateOrCreate(
            ['id' => $this->editingId],
            ['nombre' => $this->nombre]
        );

        $this->resetForm();
    }

    public function delete($id)
    {
        $categoria = CategoriaPadre::findOrFail($id);

        if ($categoria->hijos()->exists()) {
            session()->flash('error', 'No se puede eliminar la categoría porque tiene categorías hijas asociadas.');
            return;
        }

        $categoria->delete();

        session()->flash('success', 'Categoría padre eliminada correctamente.');
    }

    public function close()
    {
        $this->resetForm();
        $this->openModal = false;
    }

    private function resetForm()
    {
        $this->reset(['editingId', 'nombre']);
    }

    public function getCategoriasProperty()
    {
        return CategoriaPadre::orderBy('codigo')->get();
    }
};
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Categorías Padre</h2>

    <button
        wire:click="open"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg"
    >
        + Nueva Categoría Padre
    </button>

    @if($openModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">

            <div class="bg-white p-6 rounded-xl w-2/3">

                <h2 class="text-xl font-bold mb-4">
                    Administración de Categorías Padre
                </h2>

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

                {{-- LISTADO --}}
                <table class="w-full border mb-6">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Código</th>
                            <th class="p-2 text-left">Nombre</th>
                            <th class="p-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->categorias as $categoria)
                            <tr class="border-t">
                                <td class="p-2">{{ $categoria->codigo }}</td>
                                <td class="p-2">{{ $categoria->nombre }}</td>
                                <td class="p-2 flex gap-2">

                                    <button
                                        wire:click="edit({{ $categoria->id }})"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded text-sm"
                                    >
                                        editar
                                    </button>

                                    <button
                                        wire:click="delete({{ $categoria->id }})"
                                        onclick="confirm('¿Eliminar esta categoría?') || event.stopImmediatePropagation()"
                                        class="bg-red-600 text-white px-2 py-1 rounded text-sm"
                                    >
                                        eliminar
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- FORMULARIO --}}
                <div class="border-t pt-4">
                    <h3 class="font-semibold mb-2">
                        {{ $editingId ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h3>

                    <input
                        type="text"
                        wire:model="nombre"
                        placeholder="Nombre de la categoría"
                        class="w-full border rounded px-3 py-2"
                    >

                    @error('nombre')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <div class="flex justify-end gap-2 mt-4">
                        <button
                            wire:click="close"
                            class="bg-gray-300 px-4 py-2 rounded"
                        >
                            Cerrar
                        </button>

                        <button
                            wire:click="save"
                            class="bg-blue-600 text-white px-4 py-2 rounded"
                        >
                            {{ $editingId ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>
