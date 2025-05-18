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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
                    <button onclick="modalAction(\'' . url('/user/' . $u->user_id . '/show_ajax') . '\')" class="btn btn-info btn-sm me-1">Detail</button>
                    <button onclick="modalAction(\'' . url('/user/' . $u->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm me-1">Edit</button>
                    <button onclick="modalAction(\'' . url('/user/' . $u->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>
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
            'password' => 'nullable|string|min:6',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'username.unique' => 'Username sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
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

    // public function confirm_ajax($user_id)
    // {
    //     $user = UserModel::with(['admin', 'mahasiswa', 'dosen', 'tendik'])->find($user_id);

    //     if (!$user) {
    //         return view('user.confirm_ajax', compact('user'));
    //     }

    //     return view('user.confirm_ajax', compact('user'));
    // }

    // public function delete_ajax($id)
    // {
    //     try {
    //         $user = UserModel::find($id);
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User tidak ditemukan'
    //             ]);
    //         }

    //         // Karena onDelete cascade sudah ada, hapus langsung user
    //         $user->delete();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'User berhasil dihapus'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Gagal menghapus user: ' . $e->getMessage()
    //         ]);
    //     }
    // }

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



}
