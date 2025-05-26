@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="container px-6 py-8 mx-auto">
    <h1 class="mb-2 text-3xl font-semibold text-gray-700 dark:text-gray-200">
        Selamat Datang, <span class="text-green-600 dark:text-green-400">{{ Auth::user()->name }}</span>!
    </h1>
    <p class="mb-8 text-gray-600 dark:text-gray-400">
        Ini adalah halaman beranda Koperasi XYZ. Silakan pilih menu yang sesuai dengan kebutuhan Anda.
    </p>

    {{-- Tampilkan pesan sukses dari session jika ada --}}
    @if (session('status'))
        <div class="px-4 py-3 mb-6 text-sm text-green-700 bg-green-100 border border-green-400 rounded dark:bg-green-700 dark:text-green-100" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        {{-- Kartu Aksi Cepat berdasarkan Role --}}
        @if(Auth::user()->isAdmin())
            {{-- Kartu untuk Admin --}}
            <x-home.action-card
                title="Manajemen Pengguna"
                description="Kelola data pengguna, role, dan hak akses."
                icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 016-6h6a6 6 0 016 6v1h-1M15 21H9"
                link="{{ route('admin.users.index') }}"
                color="blue"
            />
            <x-home.action-card
                title="Manajemen Unit Usaha"
                description="Atur unit-unit usaha yang ada di koperasi."
                icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                link="{{ route('admin.unit-usaha.index') }}"
                color="indigo"
            />
            <x-home.action-card
                title="Lihat Laporan"
                description="Akses rekapitulasi penjualan, simpanan, dan stok."
                icon="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                link="{{ route('admin.laporan.penjualan') }}" {{-- Atau halaman index laporan --}}
                color="purple"
            />

        @elseif(Auth::user()->isPengurus())
            {{-- Kartu untuk Pengurus --}}
            <x-home.action-card
                title="Transaksi Pembelian Baru"
                description="Catat pembelian barang oleh anggota."
                icon="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                link="{{ route('pengurus.pembelian.create') }}"
                color="green"
            />
            <x-home.action-card
                title="Catat Simpanan Anggota"
                description="Input data simpanan pokok, wajib, dan sukarela."
                icon="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                link="{{ route('pengurus.simpanan.catat') }}" {{-- Sesuaikan dengan route yang tepat --}}
                color="yellow"
            />
            <x-home.action-card
                title="Manajemen Barang & Stok"
                description="Kelola daftar barang, harga, dan pencatatan stok."
                icon="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                link="{{ route('pengurus.barang.index') }}"
                color="teal"
            />
             <x-home.action-card
                title="Pencatatan Cicilan"
                description="Catat pembayaran cicilan dari anggota."
                icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                link="{{ route('pengurus.pembelian.index') }}?status=cicilan" {{-- Arahkan ke daftar pembelian yang bisa dicicil --}}
                color="orange"
            />

        @elseif(Auth::user()->isAnggota())
            {{-- Kartu untuk Anggota --}}
            <x-home.action-card
                title="Profil Saya"
                description="Lihat dan perbarui data pribadi Anda."
                icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                link="{{ route('anggota.profil.show') }}"
                color="cyan"
            />
            <x-home.action-card
                title="Riwayat Simpanan"
                description="Lihat detail simpanan pokok, wajib, dan sukarela Anda."
                icon="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                link="{{ route('anggota.profil.riwayatSimpanan') }}"
                color="lime"
            />
            <x-home.action-card
                title="Riwayat Pembelian"
                description="Cek kembali transaksi pembelian barang yang telah Anda lakukan."
                icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                link="{{ route('anggota.profil.riwayatPembelian') }}"
                color="emerald"
            />
            {{-- Tambahkan kartu "Beli Barang" jika ada halaman katalog untuk anggota --}}
            {{-- <x-home.action-card
                title="Beli Barang Koperasi"
                description="Lihat katalog barang dan lakukan pembelian."
                icon="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                link="#" {{-- Ganti dengan route katalog --}}
                {{-- color="pink"
            /> 
        @endif
    </div>

    {{-- Bagian Tambahan - opsional --}}
    <div class="mt-12">
        <h2 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">Informasi Koperasi</h2>
        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <p class="text-gray-600 dark:text-gray-400">
                Selamat datang di sistem informasi Koperasi XYZ. Kami berkomitmen untuk memberikan pelayanan terbaik bagi seluruh anggota.
                Jika Anda memerlukan bantuan, jangan ragu untuk menghubungi pengurus koperasi.
            </p>
            {{-- Bisa ditambahkan info kontak, jam operasional, dll --}}
        </div>
    </div>

</div>
@endsection