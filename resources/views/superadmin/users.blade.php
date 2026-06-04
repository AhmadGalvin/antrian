<x-app-layout>
    <div class="px-6 py-8 max-w-[1920px] mx-auto space-y-6">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Kelola User</h1>
                <p class="text-sm text-gray-400 mt-0.5">Manajemen akun pengguna sistem</p>
            </div>
            <button onclick="document.getElementById('addUserModal').classList.remove('hidden')"
                class="flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-sm font-medium rounded-lg shadow-lg shadow-primary/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah User
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
                <h3 class="font-semibold text-white">Daftar Pengguna</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ count($users) }} user terdaftar</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-background-dark text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="px-6 py-4 text-left font-medium tracking-wide">Nama</th>
                            <th class="px-6 py-4 text-left font-medium tracking-wide">Username</th>
                            <th class="px-6 py-4 text-center font-medium tracking-wide">Role</th>
                            <th class="px-6 py-4 text-left font-medium tracking-wide">Cabang</th>
                            <th class="px-6 py-4 text-center font-medium tracking-wide">Loket</th>
                            <th class="px-6 py-4 text-center font-medium tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-card-border">
                        @foreach($users as $user)
                        <tr class="hover:bg-background-dark/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-primary to-blue-400 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-gray-100">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    @if($user->role === 'superadmin') bg-red-500/15 text-red-400
                                    @elseif($user->role === 'teller') bg-blue-500/15 text-blue-400
                                    @elseif($user->role === 'cs') bg-green-500/15 text-green-400
                                    @elseif($user->role === 'admin') bg-purple-500/15 text-purple-400
                                    @else bg-gray-500/15 text-gray-400 @endif">
                                    {{ $user->role_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400">{{ $user->branch?->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-400">
                                @if($user->counter_number)
                                    <span class="px-2 py-0.5 bg-card-border/50 text-gray-300 rounded text-xs font-mono">{{ $user->counter_number }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick="editUser({{ json_encode($user) }})"
                                        class="flex items-center gap-1.5 text-xs text-blue-400 hover:text-blue-300 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex items-center gap-1.5 text-xs text-red-400 hover:text-red-300 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="w-full max-w-lg bg-card-dark border border-card-border rounded-2xl shadow-2xl animate-scale-in">
            <div class="flex items-center justify-between px-6 py-4 border-b border-card-border">
                <h3 class="text-lg font-semibold text-white">Tambah User Baru</h3>
                <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('superadmin.users.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Username <span class="text-red-400">*</span></label>
                        <input type="text" name="username" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Password <span class="text-red-400">*</span></label>
                    <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Role <span class="text-red-400">*</span></label>
                        <select name="role" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="teller">Teller</option>
                            <option value="cs">Customer Service</option>
                            <option value="admin">Admin</option>
                            <option value="kiosk">Kiosk</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Nomor Loket</label>
                        <input type="number" name="counter_number" min="1" max="10" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Cabang</label>
                    <select name="branch_id" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        <option value="">- Tidak ada (Superadmin) -</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->code }} - {{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-background-dark border border-card-border text-gray-300 rounded-lg hover:bg-card-border/50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg transition-colors text-sm font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="w-full max-w-lg bg-card-dark border border-card-border rounded-2xl shadow-2xl animate-scale-in">
            <div class="flex items-center justify-between px-6 py-4 border-b border-card-border">
                <h3 class="text-lg font-semibold text-white">Edit User</h3>
                <button onclick="document.getElementById('editUserModal').classList.add('hidden')" class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editUserForm" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                
                <!-- Error Alert Container (hidden by default) -->
                <div id="editUserErrorAlert" class="hidden bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl flex items-start gap-2 text-sm">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div id="editUserErrorMessage"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Nama</label>
                        <input type="text" name="name" id="edit_user_name" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Username</label>
                        <input type="text" name="username" id="edit_user_username" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Password <span class="text-gray-500 font-normal text-xs">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" minlength="8" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Role</label>
                        <select name="role" id="edit_user_role" required class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            <option value="teller">Teller</option>
                            <option value="cs">Customer Service</option>
                            <option value="admin">Admin</option>
                            <option value="kiosk">Kiosk</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Nomor Loket</label>
                        <input type="number" name="counter_number" id="edit_user_counter" min="1" max="10" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Cabang</label>
                    <select name="branch_id" id="edit_user_branch" class="w-full px-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        <option value="">- Tidak ada (Superadmin) -</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->code }} - {{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-background-dark border border-card-border text-gray-300 rounded-lg hover:bg-card-border/50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg transition-colors text-sm font-medium">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editUser(user) {
            document.getElementById('editUserForm').action = '/superadmin/users/' + user.id;
            document.getElementById('edit_user_name').value = user.name;
            document.getElementById('edit_user_username').value = user.username;
            document.getElementById('edit_user_role').value = user.role;
            document.getElementById('edit_user_counter').value = user.counter_number || '';
            document.getElementById('edit_user_branch').value = user.branch_id || '';
            
            // Reset error state
            document.getElementById('editUserErrorAlert').classList.add('hidden');
            document.getElementById('editUserErrorMessage').innerHTML = '';
            
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        document.getElementById('editUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            const errorAlert = document.getElementById('editUserErrorAlert');
            const errorMessage = document.getElementById('editUserErrorMessage');
            
            // Show loading state
            submitBtn.innerHTML = 'Updating...';
            submitBtn.disabled = true;
            errorAlert.classList.add('hidden');
            
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (response.ok) {
                    window.location.reload();
                } else if (response.status === 422) {
                    const data = await response.json();
                    let errorsHtml = '<ul class="list-disc pl-4 space-y-1">';
                    for (let field in data.errors) {
                        errorsHtml += `<li>${data.errors[field][0]}</li>`;
                    }
                    errorsHtml += '</ul>';
                    
                    errorMessage.innerHTML = errorsHtml;
                    errorAlert.classList.remove('hidden');
                } else {
                    errorMessage.innerHTML = 'Terjadi kesalahan sistem.';
                    errorAlert.classList.remove('hidden');
                }
            } catch (error) {
                errorMessage.innerHTML = 'Terjadi kesalahan koneksi.';
                errorAlert.classList.remove('hidden');
            } finally {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    </script>
</x-app-layout>
