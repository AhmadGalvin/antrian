<x-app-layout>
    <div class="px-6 py-8 max-w-[1920px] mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Pengaturan Profil</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola informasi akun dan keamanan Anda</p>
        </div>

        <div class="max-w-2xl space-y-6">
            <!-- Update Profile Info -->
            <div class="bg-card-dark border border-card-border rounded-xl p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-white">Informasi Profil</h2>
                    <p class="text-sm text-gray-400 mt-0.5">Perbarui nama dan alamat email akun Anda</p>
                </div>
                <div class="border-t border-card-border pt-5">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-card-dark border border-card-border rounded-xl p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-white">Perbarui Password</h2>
                    <p class="text-sm text-gray-400 mt-0.5">Gunakan password yang kuat untuk keamanan akun</p>
                </div>
                <div class="border-t border-card-border pt-5">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-card-dark border border-red-500/20 rounded-xl p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-red-400">Hapus Akun</h2>
                    <p class="text-sm text-gray-400 mt-0.5">Aksi ini tidak dapat dibatalkan</p>
                </div>
                <div class="border-t border-red-500/20 pt-5">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
