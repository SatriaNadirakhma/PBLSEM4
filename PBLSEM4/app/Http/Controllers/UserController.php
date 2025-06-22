<?php

namespace App\Http\Controllers;
use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\TendikModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User'],
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem',
        ];

        $activeMenu = 'user';
        $roles = ['admin', 'mahasiswa', 'dosen', 'tendik'];


        return view('user.index', compact('breadcrumb', 'page', 'activeMenu', 'roles'));
    }

    public function list(Request $request)
    {
        $query = UserModel::with(['admin', 'mahasiswa', 'dosen', 'tendik'])->select('user.*');

                // Searching
                $query = UserModel::select('user.*', 
                    DB::raw('
                        CASE 
                            WHEN user.role = "admin" THEN admin.admin_nama
                            WHEN user.role = "mahasiswa" THEN mahasiswa.mahasiswa_nama
                            WHEN user.role = "dosen" THEN dosen.dosen_nama
                            WHEN user.role = "tendik" THEN tendik.tendik_nama
                            ELSE "-"
                        END as nama_lengkap
                    ')
                )
                ->leftJoin('admin', 'admin.admin_id', '=', 'user.username')
                ->leftJoin('mahasiswa', 'mahasiswa.nim', '=', 'user.username')
                ->leftJoin('dosen', 'dosen.nidn', '=', 'user.username')
                ->leftJoin('tendik', 'tendik.nip', '=', 'user.username');

        if ($request->has('search') && isset($request->search['value']) && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('user.username', 'like', "%{$search}%")
                ->orWhere('admin.admin_nama', 'like', "%{$search}%")
                ->orWhere('mahasiswa.mahasiswa_nama', 'like', "%{$search}%")
                ->orWhere('dosen.dosen_nama', 'like', "%{$search}%")
                ->orWhere('tendik.tendik_nama', 'like', "%{$search}%");
            });
        }


            $query->with(['admin', 'mahasiswa', 'dosen', 'tendik']);

        // Filter berdasarkan role (admin, mahasiswa, dosen, tendik)
        if ($request->has('filter_role') && $request->filter_role != '') {
            $query->where('role', $request->filter_role);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_lengkap', function ($u) {
                return $u->admin->admin_nama ?? 
                    $u->mahasiswa->mahasiswa_nama ?? 
                    $u->dosen->dosen_nama ?? 
                    $u->tendik->tendik_nama ?? '-';
            })
            ->addColumn('profile', function ($u) {
                $url = $u->profile 
                    ? asset('storage/' . $u->profile)
                    : asset('img/default-profile.png');
                return '<img src="' . $url . '" width="50" class="rounded border">';
            })

            ->addColumn('aksi', function ($u) {
                return '
                    <button onclick="modalAction(\'' . url('/user/' . $u->user_id . '/show_ajax') . '\')"
                        class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                        <i class="fa fa-eye me-1"></i> Detail 
                    </button>

                    <button onclick="modalAction(\'' . url('/user/' . $u->user_id . '/edit_ajax') . '\')" 
                        class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                        <i class="fa fa-edit me-1"></i> Edit
                    </button>
                    <button onclick="modalAction(\'' . url('/user/' . $u->user_id . '/delete_ajax') . '\')"  
                        class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                        <i class="fa fa-trash me-1"></i> Hapus
                    </button>
                ';
            })
            ->rawColumns(['profile', 'aksi'])
            ->make(true);
    }

    public function show_ajax($id)
    {
        $user = UserModel::with(['admin', 'mahasiswa', 'dosen', 'tendik'])->find($id);
        return view('user.show_ajax', compact('user'));
    }

    // Mengambil data nama lengkap berdasar role, untuk AJAX
    public function getNamaByRole($role)
    {
        switch ($role) {
            case 'admin':
                $data = \App\Models\AdminModel::doesntHave('user')->get(['admin_id as id', 'admin_nama as nama_lengkap']);
                break;
            case 'mahasiswa':
                $data = \App\Models\MahasiswaModel::doesntHave('user')->get(['mahasiswa_id as id', 'mahasiswa_nama as nama_lengkap']);
                break;
            case 'dosen':
                $data = \App\Models\DosenModel::doesntHave('user')->get(['dosen_id as id', 'dosen_nama as nama_lengkap']);
                break;
            case 'tendik':
                $data = \App\Models\TendikModel::doesntHave('user')->get(['tendik_id as id', 'tendik_nama as nama_lengkap']);
                break;
            default:
                return response()->json([], 400);
        }

        return response()->json($data);
    }

    public function getDetailByRole($role, $id)
    {
        switch($role) {
            case 'mahasiswa':
                $data = MahasiswaModel::find($id);
                return response()->json(['nim' => $data->nim]);
            case 'dosen':
                $data = DosenModel::find($id);
                return response()->json(['nidn' => $data->nidn]);
            case 'tendik':
                $data = TendikModel::find($id);
                return response()->json(['nip' => $data->nip]);
            default:
                return response()->json([], 404);
        }
    }

   public function create_ajax()
    {
        $roles = ['admin', 'mahasiswa', 'dosen', 'tendik'];

        $availableUsers = [
            'admin' => AdminModel::whereNotIn('admin_id', UserModel::where('role', 'admin')->pluck('username'))->get(),
            'mahasiswa' => MahasiswaModel::whereNotIn('nim', UserModel::where('role', 'mahasiswa')->pluck('username'))->get(),
            'dosen' => DosenModel::whereNotIn('nidn', UserModel::where('role', 'dosen')->pluck('username'))->get(),
            'tendik' => TendikModel::whereNotIn('nip', UserModel::where('role', 'tendik')->pluck('username'))->get(),
        ];

        return view('user.create_ajax', compact('roles', 'availableUsers'));
    }



    // Simpan user baru via ajax
    public function store_ajax(Request $request)
    {
        $rules = [
            'role' => 'required|in:admin,mahasiswa,dosen,tendik',
            'username' => 'required|string|unique:user,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:5',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'username.unique' => 'Username sudah terdaftar',
            'password.min' => 'Password minimal 5 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msgField' => $validator->errors(),
                'message' => 'Validasi gagal, cek inputan anda!'
            ]);
        }

        DB::beginTransaction();
        try {
            $data = new UserModel();
            $data->role = $request->role;
            $data->username = $request->username;
            $data->email = $request->email; 

            // Simpan relasi nama lengkap
            if ($request->role === 'dosen') {
                $data->dosen_id = $request->input('dosen_id');
            } elseif ($request->role === 'mahasiswa') {
                $data->mahasiswa_id = $request->input('mahasiswa_id');
            } elseif ($request->role === 'tendik') {
                $data->tendik_id = $request->input('tendik_id');
            }

            // Password hanya set jika diisi, jika kosong diabaikan
            if ($request->password) {
                $data->password = Hash::make($request->password);
            }

            // Upload profile jika ada
            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                $fileName = 'profile_' . $request->username . '_' . time() . '.' . $file->extension();
                $path = $file->storeAs('profile', $fileName, 'public');
                $data->profile = $path;
            }

            $data->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function edit_ajax($user_id)
    {
        $user = UserModel::with(['admin', 'mahasiswa', 'dosen', 'tendik'])->find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        $roles = ['admin', 'mahasiswa', 'dosen', 'tendik'];

        $availableUsers = [
            'admin' => AdminModel::whereNotIn('admin_id', UserModel::where('role', 'admin')->pluck('username'))->get(),
            'mahasiswa' => MahasiswaModel::whereNotIn('nim', UserModel::where('role', 'mahasiswa')->pluck('username'))->get(),
            'dosen' => DosenModel::whereNotIn('nidn', UserModel::where('role', 'dosen')->pluck('username'))->get(),
            'tendik' => TendikModel::whereNotIn('nip', UserModel::where('role', 'tendik')->pluck('username'))->get(),
        ];

        return view('user.edit_ajax', compact('user', 'roles', 'availableUsers'));
    }

    public function update_ajax(Request $request, $user_id)
{
    $user = UserModel::find($user_id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User tidak ditemukan',
        ]);
    }

    // Validasi input
    $validator = Validator::make($request->all(), [
        'username' => 'required|min:3|unique:user,username,' . $user_id . ',user_id',
        'email' => 'required|email|unique:user,email,' . $user_id . ',user_id',
        'password' => 'nullable|min:5',
        'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal',
            'msgField' => $validator->errors()
        ]);
    }

    // Update data user
    $user->username = $request->username;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // Handle foto profil
    if ($request->hasFile('profile')) {
        $file = $request->file('profile');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/profile'), $filename);
        $user->profile = $filename;
    }

    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'Data user berhasil diperbarui.'
    ]);
}


    public function confirm_ajax($user_id)
    {
        $user = UserModel::with(['admin', 'mahasiswa', 'dosen', 'tendik'])->find($user_id);

        if (!$user) {
            return view('user.confirm_ajax', compact('user'));
        }

        return view('user.confirm_ajax', compact('user'));
    }

    public function delete_ajax($id)
    {
        try {
            $user = UserModel::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ]);
            }

            // Karena onDelete cascade sudah ada, hapus langsung user
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ]);
        }
    }

    // Menampilkan form import tendik
    public function import()
    {
        return view('user.import');
    }

    // Import data tendik dari file Excel via AJAX
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'file_user' => ['required', 'file', 'mimes:xlsx', 'max:1024']
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ]);
            }

            try {
                $file = $request->file('file_user');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(null, false, true, true);

                $insertData = [];

                foreach ($rows as $index => $row) {
                    if ($index === 1) continue; // lewati header

                    // Pastikan email dan username minimal terisi
                    if (!isset($row['A'], $row['B'], $row['C'])) continue;

                    $insertData[] = [
                        'email'       => $row['A'], // misalnya kolom A = email
                        'username'    => $row['B'], // kolom B = username
                        'password'    => Hash::make($row['C']), // kolom C = password
                        'profile'     => $row['D'] ?? null,     // kolom D = profile (opsional)
                        'role'        => $row['E'] ?? 'mahasiswa', // kolom E = role (default mahasiswa)
                        'admin_id'    => $row['F'] ?? null,
                        'mahasiswa_id'=> $row['G'] ?? null,
                        'dosen_id'    => $row['H'] ?? null,
                        'tendik_id'   => $row['I'] ?? null,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }

                if (count($insertData) > 0) {
                    UserModel::insertOrIgnore($insertData);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data user berhasil diimport',
                        'jumlah' => count($insertData)
                    ]);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data valid yang ditemukan'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi error saat membaca file',
                    'error' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Akses tidak diizinkan'
        ]);
    }

    public function export_excel()
    {
        $users = UserModel::select('email', 'username', 'profile', 'role', 'admin_id', 'mahasiswa_id', 'dosen_id', 'tendik_id')
            ->orderBy('user_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Username');
        $sheet->setCellValue('D1', 'Profile');
        $sheet->setCellValue('E1', 'Role');
        $sheet->setCellValue('F1', 'Admin ID');
        $sheet->setCellValue('G1', 'Mahasiswa ID');
        $sheet->setCellValue('H1', 'Dosen ID');
        $sheet->setCellValue('I1', 'Tendik ID');

        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $user->email);
            $sheet->setCellValue('C' . $row, $user->username);

            // Tambahkan gambar profil
            if ($user->profile && file_exists(public_path('storage/' . $user->profile))) {
                $drawing = new Drawing();
                $drawing->setPath(public_path('storage/' . $user->profile));
                $drawing->setHeight(40); // Atur tinggi gambar
                $drawing->setCoordinates('D' . $row);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($sheet);
                $sheet->getRowDimension($row)->setRowHeight(45); // Sesuaikan tinggi baris
            } else {
                // Pakai default profile dari public/img/default-profile.png
                $defaultProfilePath = public_path('img/default-profile.png');
                if (file_exists($defaultProfilePath)) {
                    $drawing = new Drawing();
                    $drawing->setPath($defaultProfilePath);
                    $drawing->setHeight(40);
                    $drawing->setCoordinates('D' . $row);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                    $sheet->getRowDimension($row)->setRowHeight(45);
                } else {
                    $sheet->setCellValue('D' . $row, 'No Image');
                }
            }

            $sheet->setCellValue('E' . $row, $user->role);
            $sheet->setCellValue('F' . $row, $user->admin_id);
            $sheet->setCellValue('G' . $row, $user->mahasiswa_id);
            $sheet->setCellValue('H' . $row, $user->dosen_id);
            $sheet->setCellValue('I' . $row, $user->tendik_id);

            $row++;
        }

        // Auto-size kolom (kecuali D karena gambar)
        foreach (['A', 'B', 'C', 'E', 'F', 'G', 'H', 'I'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data User');

        $filename = 'Data_User_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function profile() 
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
        }

        $breadcrumb = (object) [
            'title' => 'Profile User',
            'list' => ['Home', 'Profile']
        ];

        $page = (object) [
            'title' => 'Profil Pengguna'
        ];

        $activeMenu = 'profile';

        return view('user.profile', compact('user', 'breadcrumb', 'page', 'activeMenu'));
    }

    // Update foto profile user 
    public function updatePhoto(Request $request)
    {
        // Validasi file
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $user = auth()->user();

            if (!$user) {
                return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
            }

            $userId = $user->user_id;

            $userModel = UserModel::find($userId);

            if (!$userModel) {
                return redirect('/login')->with('error', 'User tidak ditemukan');
            }

            // Hapus foto profil lama jika ada
            if ($userModel->profile && Storage::disk('public')->exists($userModel->profile)) {
                Storage::disk('public')->delete($userModel->profile);
            }

            // Simpan foto profil baru di folder 'profile'
            $fileName = 'profile_' . $userId . '_' . time() . '.' . $request->profile_picture->extension();
            $path = $request->profile_picture->storeAs('profile', $fileName, 'public');

            // Update database kolom 'profile'
            $userModel->profile = $path;
            $userModel->save();

            return redirect()->back()->with('success', 'Foto profile berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }


    public function export_pdf()
    {
        $users = UserModel::select('email', 'username', 'profile', 'role', 'admin_id', 'mahasiswa_id', 'dosen_id', 'tendik_id')
            ->orderBy('user_id')
            ->get();

        $pdf = Pdf::loadView('user.export_pdf', compact('users'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('Data_User_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function showForgotPasswordForm()
{

    return view('auth.forgot-password');
}

/**
 * Proses pengiriman email reset password
 */
public function sendResetLinkEmail(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|exists:user,username',
        'email' => 'required|email|exists:user,email',
    ], [
        'username.required' => 'Username wajib diisi',
        'username.exists' => 'Username tidak ditemukan',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.exists' => 'Email tidak terdaftar',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Cek apakah username dan email cocok
    $user = UserModel::where('username', $request->username)
                    ->where('email', $request->email)
                    ->first();

    if (!$user) {
        return redirect()->back()
            ->withErrors(['email' => 'Username dan email tidak cocok'])
            ->withInput();
    }

    try {
        // Generate token reset password
        $token = Str::random(60);
        
        // Simpan token ke database (buat tabel password_resets jika belum ada)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Kirim email
        Mail::to($request->email)->send(new ResetPasswordMail($user, $token));

        return redirect()->back()->with('status', 'Link reset password telah dikirim ke email Anda!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage()])
            ->withInput();
    }
}

/**
 * Menampilkan form reset password
 */
public function showResetPasswordForm($token)
{ 
    return view('auth.reset-password', ['token' => $token]);
}

/**
 * Proses reset password
 */
public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'token' => 'required',
        'email' => 'required|email|exists:user,email',
        'password' => 'required|min:5|confirmed',
    ], [
        'email.required' => 'Email wajib diisi',
        'email.exists' => 'Email tidak terdaftar',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 5 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Cek token di database
    $passwordReset = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
        return redirect()->back()
            ->withErrors(['token' => 'Token reset password tidak valid atau sudah kadaluarsa']);
    }

    // Cek apakah token sudah kadaluarsa (24 jam)
    if (now()->diffInHours($passwordReset->created_at) > 24) {
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return redirect()->back()
            ->withErrors(['token' => 'Token reset password sudah kadaluarsa']);
    }

    try {
        // Update password user
        $user = UserModel::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token dari database
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withErrors(['password' => 'Gagal mereset password: ' . $e->getMessage()]);
    }
}


}
