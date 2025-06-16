@extends('layouts.app')

@section('title', 'Manajemen Unit Usaha - Koperasi')

@section('page-title', 'Manajemen Unit Usaha')
@section('page-subtitle', 'Kelola unit-unit bisnis koperasi')

@section('content')
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h3 class="text-xl font-bold text-gray-800">Daftar Unit Usaha</h3>
            <a href="{{ route('pengurus.unit-usaha.create') }}" class="w-full sm:w-auto">
                <x-forms.button type="button" variant="primary" icon="plus" class="w-full sm:w-auto">
                    Tambah Unit Usaha
                </x-forms.button>
            </a>
        </div>

        <div class="p-6">
            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" id="search_input_unit_usaha" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama unit usaha..."
                        class="w-full md:w-1/2 lg:w-1/3 px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Unit Usaha Table -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]" id="unitUsahaTable">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50">
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Nama Unit Usaha
                            </th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Deskripsi</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Jml. Barang</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Dibuat Pada</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="unitUsahaTableBody">
                        @include('pengurus.unit_usaha.partials._unit_usaha_table_rows', [
                            'unitUsahas' => $unitUsahas,
                        ])
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationLinksUnitUsaha" class="mt-6">
                {{ $unitUsahas->links('vendor.pagination.tailwind') }}
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search_input_unit_usaha');
            let debounceTimerUnitUsaha;

            function fetchUnitUsaha() {
                clearTimeout(debounceTimerUnitUsaha);
                debounceTimerUnitUsaha = setTimeout(() => {
                    const search = searchInput.value;
                    const url = new URL("{{ route('pengurus.unit-usaha.index') }}");

                    if (search) url.searchParams.append('search', search);
                    url.searchParams.append('page', '1');

                    KoperasiApp.makeRequest(url.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }) // Kirim header AJAX
                        .then(data => {
                            document.getElementById('unitUsahaTableBody').innerHTML = data.html;
                            document.getElementById('paginationLinksUnitUsaha').innerHTML = data
                                .pagination;
                        })
                        .catch(error => {
                            console.error('Error fetching unit usaha:', error);
                            KoperasiApp.showNotification('Gagal memuat data unit usaha.', 'error');
                        });
                }, 500);
            }

            if (searchInput) {
                searchInput.addEventListener('input', fetchUnitUsaha);
            }

            document.getElementById('paginationLinksUnitUsaha').addEventListener('click', function(event) {
                const target = event.target.closest('a'); // Cari elemen <a> terdekat
                if (target && target.href && !target.classList.contains('disabled') && !target
                    .querySelector('span[aria-disabled="true"]')) {
                    event.preventDefault();
                    const url = new URL(target.href);
                    const search = searchInput.value;

                    if (search) url.searchParams.set('search', search);
                    else url.searchParams.delete('search');

                    KoperasiApp.makeRequest(url.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(data => {
                            document.getElementById('unitUsahaTableBody').innerHTML = data.html;
                            document.getElementById('paginationLinksUnitUsaha').innerHTML = data
                                .pagination;
                            window.scrollTo({
                                top: document.getElementById('unitUsahaTable').offsetTop - 80,
                                behavior: 'smooth'
                            });
                        })
                        .catch(error => console.error('Error fetching paginated unit usaha:', error));
                }
            });
        });

        function confirmDelete(deleteUrl, unitName) {
            if (confirm(
                    `Apakah Anda yakin ingin menghapus unit usaha "${unitName}"? Semua barang terkait juga akan terhapus jika tidak ada transaksi.`
                )) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                const csrfTokenInput = document.createElement('input');
                csrfTokenInput.type = 'hidden';
                csrfTokenInput.name = '_token';
                csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfTokenInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        //baru
        
    </script>
@endpush
