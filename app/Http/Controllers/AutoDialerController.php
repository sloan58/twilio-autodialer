<?php

namespace App\Http\Controllers;


use App\Services\PlaceTwilioCallService;
use Carbon\Carbon;
use App\Models\LogFile;
use App\Models\BulkFile;
use Keboola\Csv\CsvFile;
use App\Models\AudioMessage;
use Illuminate\Http\Request;
use App\Jobs\TwilioBulkCallJob;
use App\Jobs\PlaceTwilioCallJob;
use App\Models\VerifiedPhoneNumber;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class AutoDialerController extends Controller
{
    /**
     *  Show the AutoDialer index page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the Users verified Twilio Phone Numbers
        $verifiedPhoneNumbers = \Auth::user()->verifiedPhoneNumbers->pluck('friendly_name', 'id');

        // Get the Users loaded Audio Messages
        $audioMessages = \Auth::user()->audioMessages()->pluck('file_name', 'id')->toArray();

        return view('autodialer.index', compact('verifiedPhoneNumbers', 'audioMessages'));
    }


    /**
     *  Place a Twilio Call
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeCall(Request $request)
    {
        // Validate the form input
        $this->validate($request, [
            'number' => 'required',
            'say' => 'required',
            'type' => 'required',
            'caller_id' => 'required',
        ]);

        $number = substr($request->number, -10);
        // If this request is to play an audio file, generate the URL for the file location.
        // Otherwise, just use the text entered in the form.
        if($request->audioMessage) {
            $audioMessage = AudioMessage::find($request->say);
            $say = env('APP_URL') . '/storage/audio/' . $audioMessage->file_name;
        } else {
            $say = $request->say;
        }
        $type = $request->type;
        $callerId = VerifiedPhoneNumber::find($request->caller_id)->phone_number;

        $call = (new PlaceTwilioCallService(
            [$number,$say,$type, $callerId],
            \Auth::user()->id
        ))->call();

        if(!$call) {
            return redirect()->action('AutoDialerController@index')->with('danger', 'There was an error processing your call.  Please check the Call Detail Records.');
        }

        return redirect()->action('AutoDialerController@index')->with('info', 'Twilio Call Submitted!  Check the call logs for status.');

    }

    /**
     *  Show the AutoDialer Bulk Index page
     *
     * @return \Illuminate\View\View
     */
    public function bulkIndex()
    {
        // The bulk job files
        $bulkFiles = \Auth::user()->bulkFiles()->orderBy('created_at')->paginate(15);

        // Get the Users verified Twilio Phone Numbers
        $verifiedPhoneNumbers = \Auth::user()->verifiedPhoneNumbers->pluck('friendly_name', 'id');

        // Get the Users loaded Audio Messages
        $audioMessages = \Auth::user()->audioMessages()->pluck('file_name', 'id')->toArray();

        return view('autodialer.bulk.index', compact('bulkFiles', 'verifiedPhoneNumbers', 'audioMessages'));
    }

    public function bulkShow($id)
    {
        $bulk = BulkFile::find($id);
        $cdrs = $bulk->cdrs()->paginate(15);
        return view('autodialer.bulk.show', compact('cdrs', 'bulk'));
    }

    /**
     *  Process AutoDialer Bulk Call Request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkStore(Request $request)
    {
        // Validate the form input
        $this->validate($request, [
            'file' => 'required',
            'say' => 'required',
            'type' => 'required',
            'caller_id' => 'required',
        ]);

        if (!$request->file('file')|| ($request->file('file')->getClientMimeType() != "text/csv" && $request->file('file')->getClientOriginalExtension() != "csv"))
        {
            return redirect()->back()->with('danger', 'File type invalid.  Please use a CSV file format.');
        }

        // Store the file
        $fileName = Carbon::now()->timestamp . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->storeAs(
            'bulkfiles', $fileName , 'public'
        );

        // Create the Bulk File record
        $bulkFile = BulkFile::create([
            'file_name' => $fileName,
            'status' => 'Processing',
            'created_by' => \Auth::user()->id
        ]);

        // If this request is to play an audio file, generate the URL for the file location.
        // Otherwise, just use the text entered in the form.
        if($request->audioMessage) {
            $audioMessage = AudioMessage::find($request->say);
            $say = env('APP_URL') . '/storage/audio/' . $audioMessage->file_name;
        } else {
            $say = $request->say;
        }

        $type = $request->type;
        $callerId = VerifiedPhoneNumber::find($request->caller_id)->phone_number;
        $fileNameAndPath = storage_path() . '/app/public/bulkfiles/' . $fileName;

        // Create a new Symfony Process to count lines in the bulk file
        \Log::info('Create Bulk Process - about to count rows in this file: ', ["wc -l $fileNameAndPath | awk '{print $1}'"]);
        $process = new Process("wc -l $fileNameAndPath | awk '{print $1}'");
        $process->run();
        $res = trim($process->getOutput());
        \Log::info('Create Bulk Process - After grep we found: ', [$res]);

        $chunkAmt = floor($res / 4);
        \Log::info('Create Bulk Process - Setting chunk amount to: ', [$chunkAmt]);

        // Move the CSV rows to an array
        $csvFile = new CsvFile($fileNameAndPath);;
        $callRequests = [];
        foreach($csvFile as $row) {
            $callRequests[] = $row;
        }

        // Dispatch Bulk Dialer Jobs.  If we have more than 4 rows, split them into chunks.
        if($chunkAmt) {
            foreach(array_chunk($callRequests, $chunkAmt) as $chunk) {
                $this->dispatch(new TwilioBulkCallJob($chunk, $say, $type, $callerId, \Auth::user(), $bulkFile));
            }
        } else {
            $this->dispatch(new TwilioBulkCallJob($callRequests, $say, $type, $callerId, \Auth::user(), $bulkFile));
        }

        return redirect()->back()->with('info', 'Bulk Job Submitted!  Check the call logs for status.');
    }

    /**
     *  Destroy AutoDialer Bulk File and CDR's
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy($id)
    {
        $bulkfile = BulkFile::find($id);

        $logFiles = $bulkfile->logFiles;
        foreach($logFiles as $logFile) {
            Storage::delete('public/logfiles/' . $logFile->path);
        }
        Storage::delete('public/bulkfiles/' . $bulkfile->file_name);
        BulkFile::destroy($bulkfile->id);

        return redirect()->back()->with('info', 'Bulk File Deleted!');

    }

    /**
     *  Load a log file and attach to a bulk process
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkLogfile(Request $request, $id)
    {
        $fileName = $fileName = Carbon::now()->timestamp . '_' . $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs(
            'logfiles', $fileName , 'public'
        );

        if($path) {
            $logFile = LogFile::firstOrNew([
                'path' => $path
            ]);

            $logFile->bulk_file_id = $id;
            $logFile->save();
            return response()->json('success', 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error uploading file',
                'code' => 400
            ], 400);
        }
    }

    public function bulkProcess($id)
    {

        // Increase timeout for large files
        set_time_limit(120);

        // Get the BulkFile Object
        $bulkFile = BulkFile::find($id);
        \Log::info('Process Call Logs - Found Bulk File: ', [$bulkFile]);

        // Get the associated LogFiles
        $logFiles = $bulkFile->logFiles;
        \Log::info('Process Call Logs - Found Log Files: ', [$logFiles]);

        // If there are LogFiles, start to prcess
        if($logFiles->count()) {
        \Log::info('Process Call Logs - We have log files');

            // Get all the CDR's for this Bulk File
            $calls = $bulkFile->cdrs;

            // If there are CDR's, check each one within the LogFiles
            if($calls->count()) {
                \Log::info('Process Call Logs - There were CDRs: ', [$calls->count()]);

                // Create the output report
                $csvFile = new CsvFile(storage_path() . '/app/public/processed/processed_' . $bulkFile->file_name);
                \Log::info('Process Call Logs - Opened CSV: ', [$bulkFile->file_name]);

                // Set the report headers
                $headers = [
                    'Dialed Number',
                    'Caller Id',
                    'Call Type',
                    'Message',
                    'Successful',
                    'Fail Reason',
                    'Matching Log File',

                ];
                $csvFile->writeRow($headers);

                // Loop each call and check if it appears in the logs
                foreach($calls as $call) {
                    \Log::debug('Process Call Logs - Processing Call: ', [$call]);

                    // Check each LogFile for the call
                    foreach($logFiles as $logFile) {
                        \Log::debug('Process Call Logs - Processing Log File: ', [$logFile]);

                        // Look for the 10 digit called number
                        $dialedNumber = substr($call->dialednumber, -10);
                        \Log::debug('Process Call Logs - Looking for number: ', [$dialedNumber]);

                        // Create a new Symfony Process to call grep on the LogFile with the dialed number
                        \Log::debug('Process Call Logs - about to grep for number: ', ["grep -c $dialedNumber " . storage_path('app/public/' . $logFile->getOriginal('path'))]);
                        $process = new Process("grep -c $dialedNumber " . storage_path('app/public/' . $logFile->getOriginal('path')));
                        $process->run();
                        $res = trim($process->getOutput());
                        \Log::debug('Process Call Logs - After grep we found: ', [$res]);

                        // If we have a result, the dialed number was found in the LogFile
                        if($res) {
                            \Log::debug('Process Call Logs - Success!  Writing row:');
                            $csvFile->writeRow([$call->dialednumber, $call->callerid, $call->calltype, $call->message, $call->successful, $call->failreason, $logFile->path]);
                            continue 2;
                        }
                    }
                    // No match was found in any log file
                    \Log::debug('Process Call Logs - Fail. No record found.');
                    $csvFile->writeRow([$call->dialednumber, $call->callerid, $call->calltype, $call->message, $call->successful, $call->failreason, ""]);
                }
            }
            \Log::info('Process Call Logs - There were no CDRs: ', [$calls->count()]);
            return response()->download(storage_path() . '/app/public/processed/processed_' . $bulkFile->file_name, $bulkFile->file_name);
        } else {
            return redirect()->back()->with('danger', 'Sorry, no log files have been loaded!');
        }
    }
}
