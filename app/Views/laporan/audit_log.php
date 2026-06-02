<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2 class="mb-0">Audit Log System</h2>
        <span class="badge bg-dark text-light p-2 rounded shadow-sm d-flex align-items-center gap-2">
            <span class="live-pulse"></span>
            <span style="font-size: 0.85rem; font-weight: 500; letter-spacing: 0.5px;">Live Monitor Aktif</span>
        </span>
    </div>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Jejak aktivitas keamanan dan pemantauan otomatis perubahan data Sistem Informasi Manajemen Teh Kota</li>
    </ol>

    <ul class="nav nav-pills mb-4 bg-light p-2 rounded shadow-sm">
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan'); ?>">📋 Riwayat Transaksi</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan/labarugi'); ?>">💰 Laba Rugi</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan/stokinventori'); ?>">📦 Stok Inventori</a></li>
        <li class="nav-item"><a class="nav-link active font-weight-bold" href="<?= base_url('laporan/auditlog'); ?>">🔒 Audit Log</a></li>
    </ul>

    <style>
        .live-pulse {
            width: 9px;
            height: 9px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 1.6s infinite;
        }
        @keyframes pulse-green {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
        
        .card-header-premium {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #f8fafc;
            border-bottom: 2px solid #334155;
            padding: 1rem 1.25rem;
        }
        
        .badge-insert {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            font-weight: 600;
        }
        
        .badge-update {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            font-weight: 600;
        }
        
        .badge-delete {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            font-weight: 600;
        }
        
        .badge-other {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-weight: 600;
        }
        
        .row-new {
            animation: highlight-row 3s ease-out;
        }
        
        @keyframes highlight-row {
            0% { background-color: rgba(253, 224, 71, 0.8); }
            100% { background-color: transparent; }
        }
        
        .table-premium th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            background-color: #0f172a !important;
            color: #ffffff !important;
            border: none;
            padding: 14px 16px;
        }
        
        .table-premium td {
            vertical-align: middle;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .user-pill {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        
        .ip-pill {
            font-family: monospace;
            background-color: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        
        .refresh-spinner {
            transition: transform 0.8s ease-in-out;
        }
        .refreshing .refresh-spinner {
            transform: rotate(360deg);
        }
    </style>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header card-header-premium d-flex justify-content-between align-items-center">
            <span class="d-flex align-items-center gap-2">
                <i class="fas fa-shield-alt text-warning"></i>
                <span class="font-weight-bold">Jejak Aktivitas Keamanan Sistem</span>
            </span>
            <button id="btnRefresh" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1 border-0" title="Segarkan Data">
                <i class="fas fa-sync-alt refresh-spinner"></i>
                <span class="d-none d-sm-inline">Segarkan</span>
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-premium mb-0" id="tableAuditLogs" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Waktu Aktivitas</th>
                            <th width="15%">Pengguna</th>
                            <th width="50%">Detail Aktivitas / Aksi</th>
                            <th width="15%">IP Address</th>
                        </tr>
                    </thead>
                    <tbody id="logsContainer">
                        <?php if (!empty($logs)): $no = 1; foreach ($logs as $row): ?>
                            <tr data-id="<?= esc($row['ID_LOG']); ?>">
                                <td><?= $no++; ?></td>
                                <td class="text-secondary font-weight-bold" style="font-size: 0.85rem;">
                                    <?= date('d-m-Y H:i:s', strtotime($row['TANGGAL_WAKTU'])); ?>
                                </td>
                                <td>
                                    <span class="user-pill">
                                        <i class="fas fa-user-circle me-1"></i>
                                        <?= !empty($row['ID_USER']) ? esc($row['ID_USER']) : 'Sistem / Guest'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $aksi = esc($row['AKSI']);
                                        $badgeClass = 'badge-other';
                                        if (stripos($aksi, 'Menambahkan') !== false || stripos($aksi, 'INSERT') !== false) {
                                            $badgeClass = 'badge-insert';
                                        } elseif (stripos($aksi, 'Mengubah') !== false || stripos($aksi, 'UPDATE') !== false || stripos($aksi, 'Memperbarui') !== false) {
                                            $badgeClass = 'badge-update';
                                        } elseif (stripos($aksi, 'Menghapus') !== false || stripos($aksi, 'DELETE') !== false || stripos($aksi, 'Membatalkan') !== false) {
                                            $badgeClass = 'badge-delete';
                                        }
                                    ?>
                                    <span class="badge <?= $badgeClass; ?> p-2 me-2">
                                        <?php
                                            if ($badgeClass === 'badge-insert') echo '<i class="fas fa-plus-circle me-1"></i> TAMBAH';
                                            elseif ($badgeClass === 'badge-update') echo '<i class="fas fa-edit me-1"></i> UBAH';
                                            elseif ($badgeClass === 'badge-delete') echo '<i class="fas fa-trash-alt me-1"></i> HAPUS';
                                            else echo '<i class="fas fa-info-circle me-1"></i> INFO';
                                        ?>
                                    </span>
                                    <span class="font-weight-500 text-dark"><?= $aksi; ?></span>
                                </td>
                                <td>
                                    <span class="ip-pill">
                                        <i class="fas fa-laptop me-1"></i>
                                        <?= esc($row['IP_ADDRESS'] ?? '0.0.0.0'); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr class="no-logs"><td colspan="5" class="text-center text-muted p-4">Belum ada rekaman aktivitas (Tabel audit_logs kosong).</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let latestId = <?= !empty($logs) ? intval(max(array_column($logs, 'ID_LOG'))) : 0; ?>;
    const logsContainer = document.getElementById('logsContainer');
    const btnRefresh = document.getElementById('btnRefresh');
    const spinner = btnRefresh.querySelector('.refresh-spinner');
    let isPolling = false;

    // Helper to format date in dynamic updates
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        const seconds = String(d.getSeconds()).padStart(2, '0');
        return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
    }

    // Function to check for new audit logs via AJAX
    function checkNewLogs(isManual = false) {
        if (isPolling) return;
        isPolling = true;

        if (isManual) {
            btnRefresh.classList.add('refreshing');
            spinner.style.transform = 'rotate(360deg)';
        }

        fetch('<?= base_url("laporan/auditlog"); ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                // Filter new logs that are newer than latestId
                const newLogs = data.filter(log => parseInt(log.ID_LOG) > latestId);
                
                if (newLogs.length > 0) {
                    // Update latestId
                    latestId = Math.max(...data.map(log => parseInt(log.ID_LOG)));

                    // Remove "No logs" placeholder if present
                    const noLogsRow = logsContainer.querySelector('.no-logs');
                    if (noLogsRow) {
                        noLogsRow.remove();
                    }

                    // Prepend new rows in correct order (oldest first to end with newest at the very top)
                    newLogs.reverse().forEach(log => {
                        let badgeClass = 'badge-other';
                        let actionLabel = '<i class="fas fa-info-circle me-1"></i> INFO';
                        const actionText = log.AKSI;

                        if (actionText.toLowerCase().includes('menambahkan') || actionText.toLowerCase().includes('insert')) {
                            badgeClass = 'badge-insert';
                            actionLabel = '<i class="fas fa-plus-circle me-1"></i> TAMBAH';
                        } else if (actionText.toLowerCase().includes('mengubah') || actionText.toLowerCase().includes('update') || actionText.toLowerCase().includes('memperbarui')) {
                            badgeClass = 'badge-update';
                            actionLabel = '<i class="fas fa-edit me-1"></i> UBAH';
                        } else if (actionText.toLowerCase().includes('menghapus') || actionText.toLowerCase().includes('delete') || actionText.toLowerCase().includes('membatalkan')) {
                            badgeClass = 'badge-delete';
                            actionLabel = '<i class="fas fa-trash-alt me-1"></i> HAPUS';
                        }

                        const newRow = document.createElement('tr');
                        newRow.setAttribute('data-id', log.ID_LOG);
                        newRow.className = 'row-new';
                        newRow.innerHTML = `
                            <td>-</td>
                            <td class="text-secondary font-weight-bold" style="font-size: 0.85rem;">
                                ${formatDate(log.TANGGAL_WAKTU)}
                            </td>
                            <td>
                                <span class="user-pill">
                                    <i class="fas fa-user-circle me-1"></i>
                                    ${escapeHtml(log.ID_USER || 'Sistem / Guest')}
                                </span>
                            </td>
                            <td>
                                <span class="badge ${badgeClass} p-2 me-2">${actionLabel}</span>
                                <span class="font-weight-500 text-dark">${escapeHtml(log.AKSI)}</span>
                            </td>
                            <td>
                                <span class="ip-pill">
                                    <i class="fas fa-laptop me-1"></i>
                                    ${escapeHtml(log.IP_ADDRESS || '0.0.0.0')}
                                </span>
                            </td>
                        `;

                        // Insert at the top of the tbody
                        logsContainer.insertBefore(newRow, logsContainer.firstChild);
                    });

                    // Recalculate 'No' numbers for all rows
                    recalculateRowNumbers();
                }
            }
        })
        .catch(error => console.error('Error fetching audit logs:', error))
        .finally(() => {
            isPolling = false;
            if (isManual) {
                setTimeout(() => {
                    btnRefresh.classList.remove('refreshing');
                    spinner.style.transform = 'rotate(0deg)';
                }, 800);
            }
        });
    }

    // Function to recalculate table index numbers
    function recalculateRowNumbers() {
        const rows = logsContainer.querySelectorAll('tr:not(.no-logs)');
        rows.forEach((row, idx) => {
            row.querySelector('td:first-child').textContent = idx + 1;
        });
    }

    // Helper to escape HTML characters
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Polling interval: every 3 seconds (3000 ms)
    setInterval(() => checkNewLogs(false), 3000);

    // Manual refresh event listener
    btnRefresh.addEventListener('click', function(e) {
        e.preventDefault();
        checkNewLogs(true);
    });
});
</script>
<?= $this->endSection(); ?>