<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoProgressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'video_id' => ['required', 'integer'],
            'seconds' => ['required', 'integer']
        ]);

        $user = Auth::user();
        $video = Video::find($request->get('video_id'));

        // Clear out any active progresses for the race and user combo
        VideoProgress::where('video_id', $video->id)
            ->where('user_id', $user->id)
            ->delete();

        return VideoProgress::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'percentage' => $this->percentage($request->get('seconds'), $video->end_time),
            'seconds' => $request->get('seconds')
        ]);
    }

    /**
     * @param int $total
     * @param int $number
     * @return int
     */
    public function percentage(int $number, int $total): int
    {
        if ($total > 0) {
            return round(($number * 100) / $total, 0);
        } else {
            return 0;
        }
    }
}
