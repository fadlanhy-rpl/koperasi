 @extends('layouts.app')

 @section('title', 'Manajemen Pengguna - Koperasi')

 @section('page-title', 'Manajemen Pengguna')
 @section('page-subtitle', 'Kelola data pengguna sistem koperasi')

 @section('content')
     <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
         <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
             <h3 class="text-xl font-bold text-gray-800">Daftar Pengguna</h3>
             <a href="{{ route('admin.manajemen-pengguna.create') }}" class="w-full sm:w-auto">
                 <x-forms.button type="button" variant="primary" icon="plus" class="w-full sm:w-auto">
                     Tambah Pengguna
                 </x-forms.button>
             </a>
         </div>
         
         <div class="p-6">
             <!-- Filters -->
             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                 <div>
                     <label for="role_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Role:</label>
                     <select id="role_filter" name="role_filter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                         <option value="all">Semua Role</option>
                         @foreach($roles as $role)
                             <option value="{{ $role }}" {{ request('role_filter') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                         @endforeach
                     </select>
                 </div>
                 <div>
                     <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Status:</label>
                     <select id="status_filter" name="status_filter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                         <option value="all">Semua Status</option>
                          @foreach($statuses as $status)
                             <option value="{{ $status }}" {{ request('status_filter') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                         @endforeach
                     </select>
                 </div>
                 <div>
                     <label for="search_input" class="block text-sm font-medium text-gray-700 mb-1">Pencarian:</label>
                     <div class="relative">
                         <input type="text" id="search_input" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, no. anggota..." 
                                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                         <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                     </div>
                 </div>
             </div>
             
             <!-- Users Table -->
             <div class="overflow-x-auto">
                 <table class="w-full min-w-[700px]" id="usersTable">
                     <thead>
                         <tr class="border-b-2 border-gray-200 bg-gray-50">
                             <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Nama</th>
                             <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Email</th>
                             <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">No. Anggota</th>
                             <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Role</th>
                             <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Bergabung</th>
                             <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Status</th>
                             <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Aksi</th>
                         </tr>
                     </thead>
                     <tbody id="userTableBody">
                         @include('admin.pengguna.partials._user_table_rows', ['users' => $users])
                     </tbody>
                 </table>
             </div>
             
             <!-- Pagination -->
             <div id="paginationLinks" class="mt-6">
                 {{ $users->links('vendor.pagination.tailwind') }} {{-- Menggunakan view pagination Tailwind bawaan Laravel --}}
             </div>
         </div>
     </div>

     <!-- Modal Konfirmasi Delete (Contoh) -->
     {{-- <div id="deleteConfirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center z-50">
         <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
             <div class="mt-3 text-center">
                 <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                     <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                 </div>
                 <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Hapus Pengguna</h3>
                 <div class="mt-2 px-7 py-3">
                     <p class="text-sm text-gray-500" id="deleteMessage">Apakah Anda yakin ingin menghapus pengguna ini?</p>
                 </div>
                 <div class="items-center px-4 py-3 space-x-2">
                     <button id="confirmDeleteButton" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                         Ya, Hapus
                     </button>
                     <button id="cancelDeleteButton" class="px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-md w-auto shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                         Batal
                     </button>
                 </div>
             </div>
         </div>
     </div> --}}
 @endsection

 @push('scripts')
 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const roleFilter = document.getElementById('role_filter');
         const statusFilter = document.getElementById('status_filter');
         const searchInput = document.getElementById('search_input');
         let debounceTimer;

         function fetchUsers() {
             clearTimeout(debounceTimer);
             debounceTimer = setTimeout(() => {
                 const role = roleFilter.value;
                 const status = statusFilter.value;
                 const search = searchInput.value;
                 const url = new URL("{{ route('admin.manajemen-pengguna.index') }}");

                 if (role && role !== 'all') url.searchParams.append('role_filter', role);
                 if (status && status !== 'all') url.searchParams.append('status_filter', status);
                 if (search) url.searchParams.append('search', search);
                 
                 // Menambahkan page=1 agar selalu kembali ke halaman pertama saat filter/search
                 url.searchParams.append('page', '1'); 

                 KoperasiApp.makeRequest(url.toString(), { method: 'GET' })
                     .then(data => {
                         document.getElementById('userTableBody').innerHTML = data.html;
                         document.getElementById('paginationLinks').innerHTML = data.pagination;
                         // Re-initialize event listeners untuk tombol delete jika ada di dalam partial
                     })
                     .catch(error => {
                         console.error('Error fetching users:', error);
                         KoperasiApp.showNotification('Gagal memuat data pengguna.', 'error');
                     });
             }, 500); // Debounce 500ms
         }

         roleFilter.addEventListener('change', fetchUsers);
         statusFilter.addEventListener('change', fetchUsers);
         searchInput.addEventListener('input', fetchUsers);

         // Handle klik pada link pagination via AJAX
         document.getElementById('paginationLinks').addEventListener('click', function(event) {
             if (event.target.tagName === 'A' && event.target.href) {
                 event.preventDefault();
                 const url = new URL(event.target.href);
                 // Ambil filter yang sudah ada dari input
                 const role = roleFilter.value;
                 const status = statusFilter.value;
                 const search = searchInput.value;

                 if (role && role !== 'all') url.searchParams.set('role_filter', role);
                 else url.searchParams.delete('role_filter');

                 if (status && status !== 'all') url.searchParams.set('status_filter', status);
                 else url.searchParams.delete('status_filter');
                 
                 if (search) url.searchParams.set('search', search);
                 else url.searchParams.delete('search');

                 KoperasiApp.makeRequest(url.toString(), { method: 'GET' })
                     .then(data => {
                         document.getElementById('userTableBody').innerHTML = data.html;
                         document.getElementById('paginationLinks').innerHTML = data.pagination;
                         window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll ke atas tabel
                     })
                     .catch(error => console.error('Error fetching paginated users:', error));
             }
         });
     });
     
     function confirmDelete(deleteUrl, userName) {
         // Menggunakan KoperasiApp.openModal dan KoperasiApp.showNotification jika sudah terintegrasi
         // Untuk sekarang, kita pakai confirm bawaan
         if (confirm(`Apakah Anda yakin ingin menghapus pengguna "${userName}"? Tindakan ini tidak dapat diurungkan.`)) {
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
 </script>
 @endpush