<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'queue_number',
        'service_type',
        'customer_note',
        'status',
        'counter_number',
        'served_by',
        'called_at',
        'recall_count',
        'finished_at',
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Service type constants for Teller
     */
    public const TELLER_SERVICES = [
        'setoran_tunai' => 'Setoran Tunai',
        'penarikan_tunai' => 'Penarikan Tunai',
        'transfer_tunai' => 'Transfer Tunai',
        'pembayaran_angsuran' => 'Pembayaran Angsuran',
    ];

    /**
     * Service type constants for CS (Customer Service)
     */
    public const CS_SERVICES = [
        'buka_rekening' => 'Buka Rekening',
        'tutup_rekening' => 'Tutup Rekening',
        'pengaduan' => 'Pengaduan',
        'ganti_kartu' => 'Ganti Kartu ATM',
        'informasi_produk' => 'Informasi Produk',
    ];

    /**
     * Service type constants for Admin (Administrasi)
     */
    public const ADMIN_SERVICES = [
        'pemindahbukuan' => 'Pemindahbukuan',
        'cetak_mutasi' => 'Cetak Mutasi Rekening',
        'surat_keterangan' => 'Surat Keterangan Bank',
        'perubahan_data' => 'Perubahan Data Nasabah',
        'lainnya' => 'Layanan Lainnya',
    ];

    /**
     * Get all available services
     */
    public static function allServices(): array
    {
        return array_merge(self::TELLER_SERVICES, self::CS_SERVICES, self::ADMIN_SERVICES);
    }

    /**
     * Get services by type
     */
    public static function getServicesByType(string $type): array
    {
        return match($type) {
            'teller' => self::TELLER_SERVICES,
            'cs' => self::CS_SERVICES,
            'admin' => self::ADMIN_SERVICES,
            default => [],
        };
    }

    /**
     * Get service label
     */
    public function getServiceLabelAttribute(): string
    {
        return self::allServices()[$this->customer_note] ?? $this->customer_note;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-500',
            'in_process' => 'bg-blue-500',
            'finished' => 'bg-green-500',
            'skipped' => 'bg-red-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * Branch relationship
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Served by user relationship
     */
    public function servedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    /**
     * Scope for pending queues
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for in-process queues
     */
    public function scopeInProcess($query)
    {
        return $query->where('status', 'in_process');
    }

    /**
     * Scope for teller service type
     */
    public function scopeForTeller($query)
    {
        return $query->where('service_type', 'teller');
    }

    /**
     * Scope for CS service type
     */
    public function scopeForCs($query)
    {
        return $query->where('service_type', 'cs');
    }

    /**
     * Scope for Admin service type
     */
    public function scopeForAdmin($query)
    {
        return $query->where('service_type', 'admin');
    }

    /**
     * Scope for today's queues
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for specific branch
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Generate next queue number for a branch and service type
     */
    public static function generateQueueNumber(int $branchId, string $serviceType): string
    {
        $prefix = match($serviceType) {
            'teller' => 'T',
            'cs' => 'CS',
            'admin' => 'A',
            default => 'X',
        };
        
        $lastQueue = self::where('branch_id', $branchId)
            ->where('service_type', $serviceType)
            ->whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();

        if ($lastQueue) {
            $lastNumber = (int) substr($lastQueue->queue_number, strpos($lastQueue->queue_number, '-') + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s-%03d', $prefix, $nextNumber);
    }

    /**
     * Determine service type from customer_note
     */
    public static function determineServiceType(string $customerNote): string
    {
        if (array_key_exists($customerNote, self::TELLER_SERVICES)) {
            return 'teller';
        }
        if (array_key_exists($customerNote, self::CS_SERVICES)) {
            return 'cs';
        }
        if (array_key_exists($customerNote, self::ADMIN_SERVICES)) {
            return 'admin';
        }
        return 'teller'; // default
    }
}

