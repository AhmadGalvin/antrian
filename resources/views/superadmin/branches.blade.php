<x-app-layout>
    <div class="px-6 py-8 max-w-[1920px] mx-auto space-y-6">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Kelola Cabang</h1>
                <p class="text-sm text-gray-400 mt-0.5">Manajemen data cabang BPR BKK</p>
            </div>
            <button onclick="document.getElementById('addBranchModal').classList.remove('hidden')"
                class="flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-sm font-medium rounded-lg shadow-lg shadow-primary/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Cabang
            </button>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Table -->
        <div class="bg-card-dark border border-card-border rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-card-border">
                <h3 class="font-semibold text-white">Daftar Cabang</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ count($branches) }} cabang terdaftar</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-background-dark text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="px-6 py-4 text-left font-medium tracking-wide">Kode</th>
                            <th class="px-6 py-4 text-left font-medium tracking-wide">Nama Cabang</th>
                            <th class="px-6 py-4 text-left font-medium tracking-wide">Alamat</th>
                            <th class="px-6 py-4 text-center font-medium tracking-wide">Jumlah User</th>
                            <th class="px-6 py-4 text-center font-medium tracking-wide">Status</th>
                            <th class="px-6 py-4 text-center font-medium tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-card-border">
                        @foreach($branches as $branch)
                        <tr class="hover:bg-background-dark/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-mono bg-card-border/50 text-gray-300 rounded">{{ $branch->code }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-100">{{ $branch->name }}</td>
                            <td class="px-6 py-4 text-gray-400 text-sm max-w-xs truncate">{{ $branch->address ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2.5 py-1 text-sm font-semibold rounded-full bg-blue-500/15 text-blue-400">{{ $branch->users_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $branch->is_active ? 'bg-green-500/15 text-green-400' : 'bg-red-500/15 text-red-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $branch->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                    {{ $branch->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick="editBranch({{ json_encode($branch) }})"
                                        class="flex items-center gap-1.5 text-xs text-blue-400 hover:text-blue-300 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                    <form action="{{ route('superadmin.branches.destroy', $branch) }}" method="POST" class="inline" onsubmit="return confirm('Hapus cabang {{ $branch->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex items-center gap-1.5 text-xs text-red-400 hover:text-red-300 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Branch Modal -->
    <div id="addBranchModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="w-full max-w-md bg-card-dark border border-card-border rounded-2xl shadow-2xl animate-scale-in">
            <div class="flex items-center justify-between px-6 py-4 border-b border-card-border">
                <h3 class="text-lg font-semibold text-white">Tambah Cabang Baru</h3>
                <button onclick="document.getElementById('addBranchModal').classList.add('hidden')" class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('superadmin.branches.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Kode Cabang <span class="text-red-400">*</span></label>
                    <input type="text" name="code" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm" placeholder="BPR-KEC-XX">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Cabang <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm" placeholder="Kecamatan ...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Alamat</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm" placeholder="Alamat lengkap..."></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('addBranchModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-background-dark border border-card-border text-gray-300 rounded-lg hover:bg-card-border/50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg transition-colors text-sm font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Branch Modal -->
    <div id="editBranchModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="w-full max-w-md bg-card-dark border border-card-border rounded-2xl shadow-2xl animate-scale-in">
            <div class="flex items-center justify-between px-6 py-4 border-b border-card-border">
                <h3 class="text-lg font-semibold text-white">Edit Cabang</h3>
                <button onclick="document.getElementById('editBranchModal').classList.add('hidden')" class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editBranchForm" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Kode Cabang</label>
                    <input type="text" name="code" id="edit_code" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama Cabang</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Alamat</label>
                    <textarea name="address" id="edit_address" rows="2" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"></textarea>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 rounded bg-background-dark border-card-border text-primary focus:ring-primary">
                        <span class="text-sm text-gray-300">Cabang Aktif</span>
                    </label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('editBranchModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-background-dark border border-card-border text-gray-300 rounded-lg hover:bg-card-border/50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg transition-colors text-sm font-medium">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editBranch(branch) {
            document.getElementById('editBranchForm').action = '/superadmin/branches/' + branch.id;
            document.getElementById('edit_code').value = branch.code;
            document.getElementById('edit_name').value = branch.name;
            document.getElementById('edit_address').value = branch.address || '';
            document.getElementById('edit_is_active').checked = branch.is_active;
            document.getElementById('editBranchModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
