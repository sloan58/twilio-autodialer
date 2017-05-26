<?php

namespace App\Http\Controllers;

use App\Models\AudioMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioMessagesController extends Controller
{
    /**
     *  Show the Audio Messages index page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $audioMessages = \Auth::user()->audioMessages()->orderBy('created_at', 'desc')->paginate(15);

        return view('audio-messages.index', compact('audioMessages'));
    }

    public function store(Request $request)
    {
        // Validate the form input
        $this->validate($request, [
            'file' => 'required|mimetypes:audio/mpeg',
        ]);

        // Store the file
        $fileName = \Auth::user()->id . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->storeAs(
            'audio', $fileName , 'public'
        );

        AudioMessage::firstOrCreate([
            'file_name' => $fileName,
            'user_id' => \Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Audio Message added!');
    }

    public function destroy(AudioMessage $audioMessage)
    {
        $audioMessage->delete();
        Storage::delete('public/audio/' . $audioMessage->file_name);
        return redirect()->back()->with('info', 'Audio Message deleted!');
    }
}
