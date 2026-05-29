<x-app-layout>
    <div class="px-6 py-8 max-w-[1920px] mx-auto space-y-6">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Media Promosi Display</h1>
                <p class="text-sm text-gray-400 mt-0.5">Kelola konten yang ditampilkan di layar antrian</p>
            </div>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                class="flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-sm font-medium rounded-lg shadow-lg shadow-primary/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Media
            </button>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    <select name="branch_id" onchange="this.form.submit()"
                        class="pl-9 pr-8 py-2 bg-card-dark border border-card-border text-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary appearance-none">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </form>
            <span class="text-sm text-gray-400">{{ $media->count() }} media ditemukan</span>
        </div>

        <!-- Media Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($media as $item)
            <div class="bg-card-dark border border-card-border rounded-xl overflow-hidden group hover:border-primary/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/30">
                <!-- Preview -->
                <div class="aspect-video bg-background-dark flex items-center justify-center relative overflow-hidden">
                    @if($item->type === 'image')
                        <img src="{{ asset('storage/' . $item->file_path) }}"
                            class="w-full h-full object-cover" alt="{{ $item->title }}">
                    @else
                        <video src="{{ asset('storage/' . $item->file_path) }}"
                            class="w-full h-full object-cover" controls></video>
                    @endif
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                        <span class="text-white text-xs font-medium">{{ $item->title ?? 'Tanpa Judul' }}</span>
                    </div>
                </div>
                <!-- Info -->
                <div class="p-4">
                    <h3 class="font-semibold text-white truncate">{{ $item->title ?? 'Tanpa Judul' }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->branch->name }}</p>
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        <span class="px-2 py-0.5 rounded text-xs {{ $item->type === 'image' ? 'bg-blue-500/15 text-blue-400' : 'bg-purple-500/15 text-purple-400' }}">
                            {{ ucfirst($item->type === 'image' ? 'Gambar' : 'Video') }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-xs bg-card-border/80 text-gray-400">Urutan: {{ $item->display_order }}</span>
                        <span class="px-2 py-0.5 rounded text-xs {{ $item->is_active ? 'bg-green-500/15 text-green-400' : 'bg-red-500/15 text-red-400' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button onclick="editMedia({{ json_encode($item) }})"
                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 px-3 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 rounded-lg text-xs transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </button>
                        <form action="{{ route('superadmin.media.destroy', $item) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus media ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-1.5 px-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg text-xs transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-16 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-lg font-medium">Belum ada media</p>
                <p class="text-sm mt-1">Klik "Tambah Media" untuk menambahkan konten promosi</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="w-full max-w-lg bg-card-dark border border-card-border rounded-2xl shadow-2xl animate-scale-in">
            <div class="flex items-center justify-between px-6 py-4 border-b border-card-border">
                <h3 class="text-lg font-semibold text-white">Tambah Media Baru</h3>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('superadmin.media.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Cabang <span class="text-red-400">*</span></label>
                    <select name="branch_id" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Tipe <span class="text-red-400">*</span></label>
                    <select name="type" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        <option value="image">Gambar</option>
                        <option value="video">Video</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">File <span class="text-red-400">*</span></label>
                    <input type="file" name="file" required accept="image/*,video/*"
                        class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-primary file:text-white hover:file:bg-primary-hover">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, MP4, WebM. Maks 50MB</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Judul</label>
                    <input type="text" name="title" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm" placeholder="Promosi Kredit Ringan...">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Urutan</label>
                        <input type="number" name="display_order" value="0" min="0" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Durasi (detik)</label>
                        <input type="number" name="duration_seconds" value="10" min="1" max="300" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-background-dark border border-card-border text-gray-300 rounded-lg hover:bg-card-border/50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg transition-colors text-sm font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="w-full max-w-lg bg-card-dark border border-card-border rounded-2xl shadow-2xl animate-scale-in">
            <div class="flex items-center justify-between px-6 py-4 border-b border-card-border">
                <h3 class="text-lg font-semibold text-white">Edit Media</h3>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Judul</label>
                    <input type="text" name="title" id="edit_title" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Urutan</label>
                        <input type="number" name="display_order" id="edit_order" min="0" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Durasi (detik)</label>
                        <input type="number" name="duration_seconds" id="edit_duration" min="1" max="300" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="edit_active" value="1" class="w-4 h-4 rounded bg-background-dark border-card-border text-primary focus:ring-primary">
                        <span class="text-sm text-gray-300">Aktif ditampilkan</span>
                    </label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-background-dark border border-card-border text-gray-300 rounded-lg hover:bg-card-border/50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg transition-colors text-sm font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editMedia(item) {
            document.getElementById('editForm').action = '/superadmin/media/' + item.id;
            document.getElementById('edit_title').value = item.title || '';
            document.getElementById('edit_order').value = item.display_order;
            document.getElementById('edit_duration').value = item.duration_seconds;
            document.getElementById('edit_active').checked = item.is_active;
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
