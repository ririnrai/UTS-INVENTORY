<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    private array $availableTypes = [
        'Elektronik',
        'Pakaian',
        'Makanan',
        'Alat Tulis',
        'Kendaraan',
    ];

    private array $availableRoles = [
        'Admin',
        'Petugas',
    ];

    public function index()
    {
        $inventories = Inventory::orderBy('date_session', 'desc')->get();
        $methods = ['fifo' => 'FIFO', 'lifo' => 'LIFO'];
        $bases = ['tanggal_masuk_keluar' => 'Tanggal Masuk/Keluar', 'date_session' => 'Date Session'];

        return view('inventories.index', [
            'inventories' => $inventories,
            'methods' => $methods,
            'bases' => $bases,
            'types' => $this->availableTypes,
        ]);
    }

    public function create()
    {
        return view('inventories.create', [
            'types' => $this->availableTypes,
            'roles' => $this->availableRoles,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateInventory($request);

        Inventory::create($data);

        return redirect()->route('inventories.index')->with('success', 'Barang inventori berhasil ditambahkan.');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventories.edit', [
            'inventory' => $inventory,
            'types' => $this->availableTypes,
            'roles' => $this->availableRoles,
        ]);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $this->validateInventory($request, $inventory->id);

        $inventory->update($data);

        return redirect()->route('inventories.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function outflow(Request $request)
    {
        $payload = $request->validate([
            'jenis_barang' => ['required', Rule::in($this->availableTypes)],
            'quantity' => 'required|integer|min:1',
            'method' => ['required', Rule::in(['fifo', 'lifo'])],
            'basis' => ['required', Rule::in(['tanggal_masuk_keluar', 'date_session'])],
            'session' => 'required|integer|min:1',
            'timestamp' => 'required|date_format:H:i',
            'date_session' => 'required|date',
        ]);

        $orderField = $payload['basis'];
        $orderDirection = $payload['method'] === 'lifo' ? 'desc' : 'asc';

        $items = Inventory::where('jenis_barang', $payload['jenis_barang'])
            ->where('jumlah_barang', '>', 0)
            ->orderBy($orderField, $orderDirection)
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Tidak ada stok barang untuk jenis yang dipilih.');
        }

        $remaining = $payload['quantity'];

        foreach ($items as $item) {
            if ($remaining <= 0) {
                break;
            }

            $take = min($item->jumlah_barang, $remaining);
            $item->jumlah_barang -= $take;
            $item->session = $payload['session'];
            $item->timestamp = $payload['timestamp'];
            $item->date_session = $payload['date_session'];
            $item->role = 'Petugas';
            $item->save();

            $remaining -= $take;
        }

        if ($remaining > 0) {
            return back()->with('error', 'Jumlah pengeluaran lebih besar dari stok tersedia.');
        }

        return back()->with('success', "Pengeluaran barang menggunakan metode " . strtoupper($payload['method']) . " berhasil.");
    }

    private function validateInventory(Request $request, int $inventoryId = null): array
    {
        $uniqueNoBarang = 'unique:inventories,no_barang';

        if ($inventoryId) {
            $uniqueNoBarang = Rule::unique('inventories', 'no_barang')->ignore($inventoryId);
        }

        return $request->validate([
            'nama_barang' => 'required|string|max:255',
            'no_barang' => ['required', 'integer', $uniqueNoBarang],
            'jumlah_barang' => 'required|integer|min:0',
            'jenis_barang' => ['required', Rule::in($this->availableTypes)],
            'tanggal_masuk_keluar' => 'required|date',
            'role' => ['required', Rule::in($this->availableRoles)],
            'session' => 'required|integer|min:1',
            'timestamp' => 'required|date_format:H:i',
            'date_session' => 'required|date',
        ]);
    }
}
