@extends('layouts.app')

@section('title', 'Kategori Cepat')

@section('content')
<h1 class="text-lg font-semibold mb-4">Tambah Kategori Cepat</h1>
<div x-data="{
    name: '',
    errors: {},
    categories: {{ \Illuminate\Support\Js::from($categories) }},
    loading: false,
    async submit() {
        if (this.loading) return;
        this.loading = true;
        this.errors = {};
        try {
            const meta = document.head.querySelector('meta[name=csrf-token]');
            const res = await fetch('{{ route('quick-categories.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': meta.content,
                },
                body: JSON.stringify({ name: this.name }),
            });
            if (res.status === 201) {
                this.categories.push(await res.json());
                this.name = '';
            } else if (res.status === 422) {
                this.errors = (await res.json()).errors;
            } else if (res.status === 401 || res.status === 419) {
                location.reload();
            } else {
                this.errors = { name: ['Terjadi kesalahan, coba lagi.'] };
            }
        } catch (e) {
            this.errors = { name: ['Gagal terhubung ke server.'] };
        } finally {
            this.loading = false;
        }
    },
}" class="max-w-md">
    <form @submit.prevent="submit()" class="flex gap-2 items-start mb-4">
        <div class="flex-1">
            <input type="text" x-model="name" placeholder="Nama kategori baru"
                class="w-full border rounded-md px-3 py-2 text-sm">
            <p x-text="errors.name?.[0]" class="text-sm text-red-600 mt-1"></p>
        </div>
        <button type="submit" :disabled="loading"
            class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm disabled:opacity-50">
            <span x-show="!loading">Tambah</span>
            <span x-show="loading">Mengirim...</span>
        </button>
    </form>
    <ul class="list-disc pl-5 text-sm">
        <template x-for="c in categories" :key="c.id">
            <li x-text="c.name"></li>
        </template>
    </ul>
</div>
@endsection