<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('pengguna');

        if ($search = $request->input('search')) {
            $query->where('aktivitas', 'like', "%{$search}%");
        }

        if ($idPengguna = $request->input('id_pengguna')) {
            $query->where('id_pengguna', $idPengguna);
        }

        if ($dari = $request->input('dari')) {
            $query->whereDate('waktu', '>=', $dari);
        }
        if ($sampai = $request->input('sampai')) {
            $query->whereDate('waktu', '<=', $sampai);
        }

        $auditLog = $query->orderByDesc('waktu')->orderByDesc('id_log')->paginate(15)->withQueryString();

        return view('audit-log.index', compact('auditLog'));
    }
}
