<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function toggleInterest(Request $request) 
    {
        $guest = auth('guest')->user();
        if (!$guest) {
            return response()->json(['message' => 'Vui lòng đăng nhập để thực hiện thao tác này.'], 401);
        }
        $request->validate([
            'interestable_id'   => 'required|integer',
            'interestable_type' => 'required|string',
        ]);
        
        $guest = auth('guest')->user();
        $interest = $guest->interests()
            ->where('interestable_id', $request->interestable_id)
            ->where('interestable_type', $request->interestable_type)
            ->first();
        if ($interest) {
            $interest->delete();
            return response()->json(['success' => true, 'bookmarked' => false, 'message' => 'Bỏ khỏi mục quan tâm thành công.']);
        } else {
            $newInterest = $guest->interests()->create([
                'interestable_id' => $request->interestable_id,
                'interestable_type' => $request->interestable_type,
            ]);
            return response()->json([
                'success' => true,
                'bookmarked' => true,
                'bookmark_id' => $newInterest->id,
                'message' => 'Đã thêm vào mục quan tâm.'
            ]);
        }
    }

    public function updateInterest(Request $request, $id)
    {
        $guest = auth('guest')->user();
        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập.'], 401);
        }

        $interest = $guest->interests()->find($id);
        if (!$interest) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bản ghi.'], 404);
        }

        $interest->update([
            'notes' => $request->input('notes'),
            'tag' => $request->input('tag'),
        ]);

        return response()->json(['success' => true, 'message' => 'Đã cập nhật thông tin thành công.']);
    }
}
