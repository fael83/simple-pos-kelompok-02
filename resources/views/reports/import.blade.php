@extends('layouts.app')

@section('title', 'Import Produk')

@section('content')
    <h1 class="text-lg font-semibold mb-4">Import Produk dari CSV</h1>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data" class="max-w-md">
        @csrf
        <label class="block mb-3">
            <span class="text-sm font-medium">File CSV</span>
            <input type="file" name="csv" accept=".csv" class="mt-1 block w-full text-sm">
            @error('csv')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </label>
        <p class="text-xs text-slate-500 mb-3">Column yang dibutuhkan: <code>name</code>, <code>category_id</code>, <code>price</code>.</p>
        <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm">Import</button>
    </form>

    @if(isset($importErrors) && $importErrors->isNotEmpty())
    <div class="mt-8">
        <h2 class="text-md font-semibold mb-3 text-red-600">Daftar Baris Gagal Import</h2>
        <div class="bg-white border rounded-md overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-4 py-2 border-b">Baris</th>
                        <th class="px-4 py-2 border-b">Data CSV</th>
                        <th class="px-4 py-2 border-b">Pesan Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($importErrors as $err)
                    <tr>
                        <td class="px-4 py-2 border-b">{{ $err->row_number }}</td>
                        <td class="px-4 py-2 border-b text-slate-500 font-mono text-xs">{{ $err->row_data }}</td>
                        <td class="px-4 py-2 border-b text-red-600">{{ $err->error_message }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection