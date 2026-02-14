<?php

use Livewire\Component;
use App\Models\Equipo;

new class extends Component
{
    public $equipos;

    public function mount()
    {
        // Eager loading para evitar N+1
        $this->equipos = Equipo::with('subcategoria.hijo.padre')->get();
    }
};
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Listado de Equipos</h2>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Código</th>
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Subcategoría</th>
                    <th class="px-4 py-2 text-left">Categoría Hijo</th>
                    <th class="px-4 py-2 text-left">Categoría Padre</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($equipos as $equipo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $equipo->codigo_inventario }}</td>
                        <td class="px-4 py-2">{{ $equipo->nombre }}</td>
                        <td class="px-4 py-2">{{ $equipo->subcategoria?->nombre }}</td>
                        <td class="px-4 py-2">{{ $equipo->subcategoria?->hijo?->nombre }}</td>
                        <td class="px-4 py-2">{{ $equipo->subcategoria?->hijo?->padre?->nombre }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

