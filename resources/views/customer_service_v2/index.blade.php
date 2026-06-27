@extends('layouts.v2')

@section('content')
<div x-data="customerService()" x-init="init()" class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Kolom Kiri: Sidebar Kategori -->
    <div class="col-span-1 bg-white p-4 rounded-lg shadow h-fit">
        <h3 class="font-bold text-lg mb-4 text-gray-700 border-b pb-2">Kategori Menu</h3>
        <ul class="space-y-2">
            <li>
                <button @click="setCategory('all')" 
                        :class="activeCategory === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'"
                        class="w-full text-left px-4 py-2 rounded-md font-medium transition-colors">
                    Semua Kategori
                </button>
            </li>
            @foreach($data['category'] as $cat)
            <li>
                <button @click="setCategory('{{ $cat['id'] }}')" 
                        :class="activeCategory === '{{ $cat['id'] }}' ? 'bg-blue-500 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'"
                        class="w-full text-left px-4 py-2 rounded-md font-medium transition-colors flex justify-between">
                    <span>{{ $cat['name'] }}</span>
                    <span class="text-sm bg-gray-300 text-gray-800 px-2 rounded-full">{{ $cat['active_menus_count'] ?? 0 }}</span>
                </button>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Kolom Tengah: Daftar Menu -->
    <div class="col-span-1 md:col-span-2 bg-white p-4 rounded-lg shadow">
        
        <!-- Top Action Bar -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Menu</h2>
            <div class="w-1/2">
                <input type="text" x-model="searchQuery" @input.debounce.500ms="fetchMenus()" 
                       placeholder="Cari menu..." 
                       class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="isLoading" class="flex justify-center py-10" x-cloak>
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Grid Menu -->
        <div x-show="!isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" x-cloak>
            <template x-for="menu in menus" :key="menu.id">
                <div class="border rounded-lg p-4 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="h-32 bg-gray-200 rounded-md mb-2 bg-cover bg-center" 
                             :style="'background-image: url(' + (menu.file_url ? menu.file_url : 'https://via.placeholder.com/150') + ')'">
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg leading-tight" x-text="menu.name"></h4>
                        <p class="text-gray-500 text-sm mb-2" x-text="menu.category_name"></p>
                    </div>
                    <div>
                        <p class="text-blue-600 font-bold mb-3" x-text="formatRupiah(menu.price)"></p>
                        <button @click="addToCart(menu)" 
                                class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-md font-medium transition-colors">
                            + Tambah
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!isLoading && menus.length === 0" class="text-center py-10 text-gray-500" x-cloak>
            Tidak ada menu yang ditemukan.
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    function customerService() {
        return {
            activeCategory: 'all',
            searchQuery: '',
            menus: [],
            isLoading: false,
            page: 1,

            init() {
                this.fetchMenus();
            },

            setCategory(cat) {
                this.activeCategory = cat;
                this.page = 1;
                this.fetchMenus();
            },

            fetchMenus() {
                this.isLoading = true;
                // Menggunakan Relative URL tanpa trailing slash setelah 'search' untuk mencegah Apache 301 Redirect ke HTTP
                let url = `/web/menus_catering/search?category=${this.activeCategory}&search=${this.searchQuery}&page=${this.page}&paket=`;
                
                axios.get(url)
                    .then(response => {
                        // Sesuaikan dengan struktur response Laravel Anda
                        if(response.data && response.data.data && response.data.data.data) {
                            this.menus = response.data.data.data;
                        } else if(response.data && response.data.data) {
                            this.menus = response.data.data;
                        } else {
                            this.menus = response.data;
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error("Gagal mengambil menu:", error);
                        this.isLoading = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal memuat daftar menu. Cek koneksi Anda.'
                        });
                    });
            },

            addToCart(menu) {
                Swal.fire({
                    icon: 'success',
                    title: 'Ditambahkan',
                    text: menu.name + ' berhasil ditambahkan ke keranjang!',
                    timer: 1500,
                    showConfirmButton: false
                });
                // Logika keranjang penuh bisa ditambahkan nanti di sini
            },

            formatRupiah(angka) {
                let number_string = angka.toString().replace(/[^,\d]/g, ''),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return 'Rp ' + rupiah;
            }
        }
    }
</script>
@endpush
