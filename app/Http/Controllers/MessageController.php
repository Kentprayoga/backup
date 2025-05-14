<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index(Request $request)
    {
        $adminId = Auth::id(); 

        // Jika request adalah POST (admin mengirimkan pesan)
        if ($request->isMethod('post')) {
            $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            // Menyimpan pesan
            $selectedUser = User::findOrFail($request->user_id);

            Message::create([
                'sender_id' => $adminId,
                'receiver_id' => $selectedUser->id,
                'message' => $request->message,
            ]);

            // Redirect kembali ke halaman chat dengan user yang dipilih
            return redirect()->route('admin.chat', ['user_id' => $selectedUser->id])->with('success', 'Pesan berhasil dikirim.');
        }

        // Menampilkan daftar user yang dapat di-chat
        $userIds = Message::where('receiver_id', $adminId)
                    ->orWhere('sender_id', $adminId)
                    ->pluck('sender_id')
                    ->merge(Message::where('receiver_id', $adminId)->pluck('receiver_id'))
                    ->unique()
                    ->reject(fn($id) => $id == $adminId);

        $users = User::whereIn('id', $userIds)->get();

        $selectedUser = null;
        $messages = collect();

        // Jika ada user_id, tampilkan chat dengan user tersebut
        if ($request->has('user_id')) {
            $selectedUser = User::findOrFail($request->user_id);

            $messages = Message::where(function ($q) use ($adminId, $selectedUser) {
                    $q->where('sender_id', $adminId)->where('receiver_id', $selectedUser->id);
                })->orWhere(function ($q) use ($adminId, $selectedUser) {
                    $q->where('sender_id', $selectedUser->id)->where('receiver_id', $adminId);
                })->orderBy('created_at')->get();
        }

        return view('chat.index', compact('users', 'selectedUser', 'messages'));

        
    }
        public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        
        // Hapus pesan
        $message->delete();

        return redirect()->route('admin.chat', ['user_id' => $message->receiver_id])
                         ->with('success', 'Pesan berhasil dihapus!');
    }

    // Fungsi untuk menghapus semua pesan antara admin dan user tertentu
    public function clearMessages(Request $request, $userId)
    {
        // Menghapus semua pesan antara admin dan user tertentu
        Message::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $userId);
        })
        ->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $userId);
        })
        ->delete();

        return redirect()->route('admin.chat', ['user_id' => $userId])
                         ->with('success', 'Semua pesan berhasil dihapus!');
    }




}