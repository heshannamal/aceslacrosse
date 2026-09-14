<style>
.aces-credit-log-panel{--ac:#611eb2;--ac-dark:#4d168f;--ac-soft:#f4edfc;--ink:#171021;margin-top:24px;background:#fff;border:1px solid #e2e8f0;border-radius:22px;box-shadow:0 12px 34px rgba(15,23,42,.05);overflow:hidden}
.aces-credit-log-header{display:flex;justify-content:space-between;align-items:center;gap:14px;padding:20px 22px;border-bottom:1px solid #eee7f5;background:linear-gradient(135deg,#fff,#fcf8ff)}
.aces-credit-log-title{margin:0;font-size:20px;font-weight:900;color:var(--ink)}
.aces-credit-log-subtitle{margin:4px 0 0;color:#64748b;font-size:13px;font-weight:600}
.aces-credit-log-refresh{border:1px solid #171021;background:#fff;color:#171021;border-radius:999px;padding:8px 14px;font-weight:800;white-space:nowrap}
.aces-credit-log-refresh:hover{background:#171021;color:#fff}
.aces-credit-log-body{padding:18px 20px 20px}
.aces-credit-log-table{min-width:920px;margin:0}
.aces-credit-log-table th{font-size:11px;letter-spacing:.04em;text-transform:uppercase;background:#f8fafc;color:#111827;white-space:nowrap;padding:8px 9px}
.aces-credit-log-table td{font-size:13px;vertical-align:middle;padding:8px 9px;white-space:nowrap}
.aces-credit-log-parent small{display:block;color:#64748b;margin-top:2px}
.aces-credit-log-credit{display:inline-flex;align-items:center;justify-content:center;min-width:30px;padding:4px 8px;border-radius:999px;background:#171021;color:#fff;font-size:12px;font-weight:900}
.aces-credit-log-balance{display:inline-flex;align-items:center;padding:5px 10px;border-radius:999px;background:var(--ac-soft);color:var(--ac-dark);font-weight:900;font-size:12px}
.aces-credit-log-note{max-width:260px;overflow:hidden;text-overflow:ellipsis}
.aces-credit-log-footer{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-top:14px}
.aces-credit-log-info{font-size:12px;color:#64748b;font-weight:700}
.aces-credit-log-pagination{display:flex;gap:5px;align-items:center;flex-wrap:wrap}
.aces-credit-log-page{min-width:32px;height:32px;border:1px solid #d7dde6;background:#fff;border-radius:9px;font-size:12px;font-weight:800;color:#171021}
.aces-credit-log-page:hover:not(:disabled){border-color:var(--ac);color:var(--ac)}
.aces-credit-log-page.active{background:#171021;color:#fff;border-color:#171021}
.aces-credit-log-page:disabled{opacity:.4;cursor:not-allowed}
@media(max-width:767px){.aces-credit-log-header{align-items:flex-start;flex-direction:column}.aces-credit-log-refresh{width:100%}}
</style>

<section class="aces-credit-log-panel" id="acesCreditLogPanel">
    <div class="aces-credit-log-header">
        <div>
            <h5 class="aces-credit-log-title">Credit Addition Log</h5>
            <p class="aces-credit-log-subtitle">Admin-added credits with child, parent, balance, and administrator details.</p>
        </div>
        <button type="button" class="aces-credit-log-refresh" id="acesRefreshCreditLogs">
            <i class="fa-solid fa-rotate me-1"></i> Refresh
        </button>
    </div>

    <div class="aces-credit-log-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle aces-credit-log-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Child</th>
                        <th>Parent</th>
                        <th>Credits</th>
                        <th>Credit Balance</th>
                        <th>Added By</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody id="acesCreditLogRows">
                    <tr><td colspan="7" class="text-center text-muted py-4">Loading credit log...</td></tr>
                </tbody>
            </table>
        </div>

        <div class="aces-credit-log-footer">
            <div class="aces-credit-log-info" id="acesCreditLogInfo"></div>
            <div class="aces-credit-log-pagination" id="acesCreditLogPagination"></div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var rowsEl = document.getElementById('acesCreditLogRows');
    var pagerEl = document.getElementById('acesCreditLogPagination');
    var infoEl = document.getElementById('acesCreditLogInfo');
    var refreshBtn = document.getElementById('acesRefreshCreditLogs');
    if (!rowsEl || !pagerEl || !infoEl || !refreshBtn) return;

    var logsUrl = @json(route('admin.parents.booking.credits.logs'));
    var currentPage = 1;

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (char) {
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char];
        });
    }

    function renderRows(logs) {
        if (!logs.length) {
            rowsEl.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No Admin credit additions recorded yet.</td></tr>';
            return;
        }

        rowsEl.innerHTML = logs.map(function (log) {
            var email = log.parent_email ? '<small>'+escapeHtml(log.parent_email)+'</small>' : '';
            var note = log.note ? escapeHtml(log.note) : '—';
            return '<tr>'+
                '<td>'+escapeHtml(log.created_at)+'</td>'+
                '<td><strong>'+escapeHtml(log.child)+'</strong></td>'+
                '<td class="aces-credit-log-parent"><strong>'+escapeHtml(log.parent)+'</strong>'+email+'</td>'+
                '<td><span class="aces-credit-log-credit">+'+Number(log.credits || 0)+'</span></td>'+
                '<td><span class="aces-credit-log-balance">'+Number(log.balance_before || 0)+' → '+Number(log.balance_after || 0)+'</span></td>'+
                '<td>'+escapeHtml(log.admin)+'</td>'+
                '<td class="aces-credit-log-note" title="'+escapeHtml(note)+'">'+note+'</td>'+
            '</tr>';
        }).join('');
    }

    function pageButton(label, page, disabled, active) {
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'aces-credit-log-page' + (active ? ' active' : '');
        button.textContent = label;
        button.disabled = !!disabled;
        if (!disabled) button.addEventListener('click', function () { loadLogs(page); });
        return button;
    }

    function renderPagination(meta) {
        pagerEl.innerHTML = '';
        var last = Math.max(1, Number(meta.last_page || 1));
        var page = Math.max(1, Number(meta.current_page || 1));

        pagerEl.appendChild(pageButton('Prev', Math.max(1, page - 1), page <= 1, false));

        var start = Math.max(1, page - 2);
        var end = Math.min(last, start + 4);
        start = Math.max(1, end - 4);
        for (var i = start; i <= end; i++) {
            pagerEl.appendChild(pageButton(String(i), i, false, i === page));
        }

        pagerEl.appendChild(pageButton('Next', Math.min(last, page + 1), page >= last, false));
    }

    async function loadLogs(page) {
        currentPage = page || 1;
        refreshBtn.disabled = true;
        rowsEl.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fa-solid fa-circle-notch fa-spin me-2"></i>Loading credit log...</td></tr>';

        try {
            var response = await fetch(logsUrl + '?page=' + encodeURIComponent(currentPage), {headers:{Accept:'application/json'}});
            var data = await response.json();
            if (!response.ok || !data.status) throw new Error(data.message || 'Could not load credit log.');

            renderRows(data.logs || []);
            var meta = data.pagination || {current_page:1,last_page:1,total:(data.logs || []).length,from:(data.logs || []).length ? 1 : 0,to:(data.logs || []).length};
            infoEl.textContent = 'Showing ' + Number(meta.from || 0) + ' to ' + Number(meta.to || 0) + ' of ' + Number(meta.total || 0) + ' records';
            renderPagination(meta);
        } catch (error) {
            rowsEl.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-4">'+escapeHtml(error.message || 'Could not load credit log.')+'</td></tr>';
            infoEl.textContent = '';
            pagerEl.innerHTML = '';
        } finally {
            refreshBtn.disabled = false;
        }
    }

    refreshBtn.addEventListener('click', function () { loadLogs(currentPage); });
    loadLogs(1);
});
</script>
