@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md animate-bounce-in" role="alert">
        <div class="flex items-center">
            <div class="py-1"><i class="fas fa-check-circle mr-3 text-xl"></i></div>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md animate-bounce-in" role="alert">
        <div class="flex items-center">
            <div class="py-1"><i class="fas fa-exclamation-circle mr-3 text-xl"></i></div>
            <div>
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-lg shadow-md animate-bounce-in" role="alert">
        <div class="flex items-center">
            <div class="py-1"><i class="fas fa-exclamation-triangle mr-3 text-xl"></i></div>
            <div>
                <p class="font-bold">Perhatian!</p>
                <p>{{ session('warning') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg shadow-md animate-bounce-in" role="alert">
        <div class="flex items-center">
            <div class="py-1"><i class="fas fa-info-circle mr-3 text-xl"></i></div>
            <div>
                <p class="font-bold">Informasi</p>
                <p>{{ session('info') }}</p>
            </div>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md animate-bounce-in" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-times-circle mr-3 text-xl"></i></div>
            <div>
                <p class="font-bold">Terdapat beberapa kesalahan input:</p>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif