<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Equipo;
use App\Models\CategoriaPadre;
use App\Models\CategoriaHijo;
use App\Models\Subcategoria;

new class extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $openModal = false;

    public $categoria_padre_id;
    public $categoria_hijo_id;
    public $subcategoria_id;
    public $nombre;
    public $numero_serie = '';
    public $estatus = 'Activo';
    public $notas = '';
    public $editingId = null;
    public $filter_padre_id;
    public $filter_hijo_id;
    public $filter_subcategoria_id;

    protected $rules = [
        'categoria_padre_id' => 'required',
        'categoria_hijo_id' => 'required',
        'subcategoria_id' => 'required',
        'nombre' => 'required|min:3',
        'estatus' => 'required',
    ];

    // Filtros

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoriaPadreId()
    {
        $this->reset(['categoria_hijo_id', 'subcategoria_id']);
    }

    public function updatedCategoriaHijoId()
    {
        $this->reset(['subcategoria_id']);
    }

    public function updatedFilterPadreId()
    {
        $this->reset(['filter_hijo_id', 'filter_subcategoria_id']);
    }

    public function updatedFilterHijoId()
    {
        $this->reset(['filter_subcategoria_id']);
    }

    // Métodos para abrir y cerrar el modal, y resetear el formulario


    public function open()
    {
        $this->resetForm();
        $this->openModal = true;
    }

    public function close()
    {
        $this->resetForm();
        $this->openModal = false;
    }

    private function resetForm()
    {
        $this->reset([
            'categoria_padre_id',
            'categoria_hijo_id',
            'subcategoria_id',
            'nombre',
            'numero_serie',
            'estatus',
            'notas'
        ]);

        $this->estatus = 'Activo';

        $this->resetValidation();
    }

    /* Para mostrar una vista previa del código de inventario basado en la subcategoría seleccionada. Si no hay subcategoría, no muestra nada. Si hay un error al generar el código (por ejemplo, si la subcategoría no existe), también devuelve null.
    */

    public function getPreviewCodigoProperty()
    {
        if (!$this->subcategoria_id) {
            return null;
        }

        try {
            return Equipo::generarCodigo($this->subcategoria_id);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Guarda tanto para creación como para edición

    public function save()
    {
        $this->validate();

        if ($this->editingId) {

            $equipo = Equipo::findOrFail($this->editingId);

            $equipo->update([
                'subcategoria_id' => $this->subcategoria_id,
                'nombre' => $this->nombre,
                'numero_serie' => $this->numero_serie ?: 'SN',
                'estatus' => $this->estatus,
                'notas' => $this->notas,
            ]);

        } else {

            Equipo::create([
                'subcategoria_id' => $this->subcategoria_id,
                'nombre' => $this->nombre,
                'numero_serie' => $this->numero_serie ?: 'SN',
                'estatus' => $this->estatus,
                'notas' => $this->notas,
            ]);
        }

        $this->close();
    }

    public function edit($id)
    {
        $equipo = Equipo::findOrFail($id);

        $this->editingId = $equipo->id;
        $this->categoria_padre_id = $equipo->subcategoria->hijo->padre->id;
        $this->categoria_hijo_id = $equipo->subcategoria->hijo->id;
        $this->subcategoria_id = $equipo->subcategoria_id;
        $this->nombre = $equipo->nombre;
        $this->numero_serie = $equipo->numero_serie;
        $this->estatus = $equipo->estatus;
        $this->notas = $equipo->notas;

        $this->openModal = true;
    }

    public function delete($id)
    {
        Equipo::findOrFail($id)->delete();
    }

    // Propiedades computadas para obtener los datos necesarios para la vista, aplicando filtros de búsqueda y relaciones necesarias para mostrar la información relacionada.

    public function getEquiposProperty()
    {
        return Equipo::with('subcategoria.hijo.padre')
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('codigo_inventario', 'like', "%{$this->search}%")
                  ->orWhere('nombre', 'like', "%{$this->search}%");
            });
        })
        ->when($this->filter_padre_id, function ($query) {
            $query->whereHas('subcategoria.hijo.padre', function ($q) {
                $q->where('id', $this->filter_padre_id);
            });
        })
        ->when($this->filter_hijo_id, function ($query) {
            $query->whereHas('subcategoria.hijo', function ($q) {
                $q->where('id', $this->filter_hijo_id);
            });
        })
        ->when($this->filter_subcategoria_id, function ($query) {
            $query->where('subcategoria_id', $this->filter_subcategoria_id);
        })
        ->paginate(5);
    }

    public function getPadresProperty()
    {
        return CategoriaPadre::orderBy('nombre')->get();
    }

    public function getHijosProperty()
    {
        return CategoriaHijo::where('categoria_padre_id', $this->categoria_padre_id)->get();
    }

    public function getSubcategoriasProperty()
    {
        return Subcategoria::where('categoria_hijo_id', $this->categoria_hijo_id)->get();
    }
};
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Listado de Equipos</h2>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="mb-4">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código o nombre..."
                class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200"
            >

            <button
                wire:click="open"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg whitespace-nowrap"
            >
                + Crear Equipo
            </button>
        </div>
        <div class="flex gap-2 mb-4">

            {{-- Filtro Padre --}}
            <select wire:model.live="filter_padre_id"
                class="border rounded px-3 py-2">
                <option value="">Todas las Categorías Padre</option>
                @foreach($this->padres as $padre)
                    <option value="{{ $padre->id }}">{{ $padre->nombre }}</option>
                @endforeach
            </select>

            {{-- Filtro Hijo --}}
            <select wire:model.live="filter_hijo_id"
                class="border rounded px-3 py-2"
                {{ !$filter_padre_id ? 'disabled' : '' }}>
                <option value="">Todas las Categorías Hijo</option>
                @foreach(
                    \App\Models\CategoriaHijo::where('categoria_padre_id', $filter_padre_id)->get()
                    as $hijo
                )
                    <option value="{{ $hijo->id }}">{{ $hijo->nombre }}</option>
                @endforeach
            </select>

            {{-- Filtro Subcategoría --}}
            <select wire:model.live="filter_subcategoria_id"
                class="border rounded px-3 py-2"
                {{ !$filter_hijo_id ? 'disabled' : '' }}>
                <option value="">Todas las Subcategorías</option>
                @foreach(
                    \App\Models\Subcategoria::where('categoria_hijo_id', $filter_hijo_id)->get()
                    as $sub
                )
                    <option value="{{ $sub->id }}">{{ $sub->nombre }}</option>
                @endforeach
            </select>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Código</th>
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Subcategoría</th>
                    <th class="px-4 py-2 text-left">Categoría Hijo</th>
                    <th class="px-4 py-2 text-left">Categoría Padre</th>
                    <th class="px-4 py-2 text-left">Serie</th>
                    <th class="px-4 py-2 text-left">Estatus</th>
                    <th class="px-4 py-2 text-left">Notas</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($this->equipos as $equipo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 flex gap-2">{{ $equipo->codigo_inventario }}</td>
                        <td class="px-4 py-2">{{ $equipo->nombre }}</td>
                        <td class="px-4 py-2">{{ $equipo->subcategoria?->nombre }}</td>
                        <td class="px-4 py-2">{{ $equipo->subcategoria?->hijo?->nombre }}</td>
                        <td class="px-4 py-2">{{ $equipo->subcategoria?->hijo?->padre?->nombre }}</td>
                        <td class="px-4 py-2">{{ $equipo->numero_serie }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-white text-xs
                                @if($equipo->estatus === 'Activo') bg-green-500
                                @elseif($equipo->estatus === 'Baja') bg-red-500
                                @else bg-yellow-500 @endif">
                                {{ $equipo->estatus }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $equipo->notas }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <button wire:click="edit({{ $equipo->id }})"
                                class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">
                                Editar
                            </button>
                            <button wire:click="delete({{ $equipo->id }})"
                                onclick="confirm('¿Eliminar equipo?') || event.stopImmediatePropagation()"
                                class="bg-red-600 text-white px-2 py-1 rounded text-sm">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $this->equipos->links() }}
        </div>

    </div>

    {{-- Modal --}}
    @if($openModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black/50">

        <div class="bg-white w-[420px] p-6 rounded-2xl shadow-xl">

            <h2 class="text-lg font-bold mb-4">Crear Equipo</h2>

            {{-- Categoría Padre --}}
            <select wire:model.live="categoria_padre_id"
                class="w-full border rounded px-3 py-2 mb-2 disabled:bg-gray-100 disabled:cursor-not-allowed"
                {{ $editingId ? 'disabled' : '' }}>
                <option value="">Seleccionar Categoría Padre</option>
                @foreach($this->padres as $padre)
                    <option value="{{ $padre->id }}">{{ $padre->nombre }}</option>
                @endforeach
            </select>
            @error('categoria_padre_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            {{-- Categoría Hijo --}}
            <select wire:model.live="categoria_hijo_id"
                wire:key="hijo-{{ $categoria_padre_id }}"
                class="w-full border rounded px-3 py-2 mb-2 disabled:bg-gray-100 disabled:cursor-not-allowed"
                {{ !$categoria_padre_id || $editingId ? 'disabled' : '' }}
            >
                <option value="">Seleccionar Categoría Hijo</option>
                @foreach($this->hijos as $hijo)
                    <option value="{{ $hijo->id }}">{{ $hijo->nombre }}</option>
                @endforeach
            </select>
            @error('categoria_hijo_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            {{-- Subcategoría --}}
            <select wire:model.live="subcategoria_id"
                class="w-full border rounded px-3 py-2 mb-2 disabled:bg-gray-100 disabled:cursor-not-allowed"
               {{ !$categoria_hijo_id || $editingId ? 'disabled' : '' }}>
                <option value="">Seleccionar Subcategoría</option>
                @foreach($this->subcategorias as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->nombre }}</option>
                @endforeach
            </select>
            @error('subcategoria_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            {{-- Vista previa del código --}}
            @if($this->previewCodigo)
                <div class="bg-slate-100 p-3 rounded mb-3 text-sm">
                    <strong>Vista previa del código:</strong><br>
                    {{ $this->previewCodigo }}
                </div>
            @endif

            {{-- Nombre --}}
            <input
                type="text"
                wire:model.live ="nombre"
                placeholder="Nombre del equipo"
                class="w-full border rounded px-3 py-2 mb-2"
            >
            @error('nombre')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            {{-- numero_serie --}}
            <input
                type="text"
                wire:model.live="numero_serie"
                placeholder="Número de serie (opcional)"
                class="w-full border rounded px-3 py-2 mb-2"
            />
            {{-- numero_serie --}}
            <select
                wire:model.live="estatus"
                class="w-full border rounded px-3 py-2 mb-2">
                <option value="Activo">Activo</option>
                <option value="Baja">Baja</option>
                <option value="Mantenimiento">Mantenimiento</option>
            </select>
            @error('estatus')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            {{-- Notas --}}
            <textarea
                wire:model.live="notas"
                placeholder="Notas (opcional)"
                class="w-full border rounded px-3 py-2 mb-2"
            ></textarea>



            <div class="flex justify-end gap-2 mt-4">
                <button wire:click="close"
                    class="bg-gray-300 px-4 py-2 rounded">
                    Cancelar
                </button>

                <button wire:click="save"
                    {{ !$categoria_padre_id || !$categoria_hijo_id || !$subcategoria_id || !$nombre ? 'disabled' : '' }}
                    class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50">
                    Guardar
                </button>
            </div>

        </div>

    </div>
    @endif

</div>

