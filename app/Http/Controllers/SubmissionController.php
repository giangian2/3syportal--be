<?php

namespace App\Http\Controllers;

use App\Events\SubmissionCreated;
use App\Events\DocumentApproved;
use App\Events\DocumentRefused;
use App\Events\DocumentUploaded;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Submission;
use App\Enums\SubmissionStatus;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use BenSampo\Enum\Rules\EnumValue;
use App\Jobs\SendDocumentApprovedMail;
use App\Jobs\UploadSubmissionDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Throwable;

class SubmissionController extends Controller
{
    public function index(Request $request, User $user)
    {
        $sender = auth()->user();

        if(!Gate::allows('view', $user)){
            abort(403);
        }

        $submissions = DB::table('submissions')->where('to_user', $user->id)->get();

        foreach ($submissions as $submission) {
            if ($submission->document_path != NULL)
                $submission->document_path = $url = Storage::disk('s3')->temporaryUrl($submission->document_path, now()->addMinutes(5));
        }

        return response()->json([
            'status' => true,
            'submissions' => $submissions
        ]);
    }

    public function show(Request $request, User $user, Submission $submission)
    {
        $sender = auth()->user();

        if(!Gate::allows('view', $user)){
            abort(403);
        }

        $submission = DB::table('submissions')->where('to_user', $user->id)
            ->where('id', $submission->id)
            ->get();

        return response()->json([
            'status' => true,
            'submission' => $submission
        ]);
    }

    public function update(Request $request, User $user, Submission $submission)
    {

        $request->validate([
            'document_type' => 'nullable|string',
            'status' => ['nullable', new EnumValue(SubmissionStatus::class,false)],
            'notes' => 'nullable|string',
            'document_name' => 'nullable|string'
        ]);

        if(!Gate::allows('view', $user) || !Gate::allows('update', $submission)){
            abort(403);
        }

        if ($request->status == SubmissionStatus::Valid()) {
            //event(new DocumentApproved($user, $submission));
            $this->dispatch(new UploadSubmissionDocument( $user,$submission));
            $this->dispatch(new SendDocumentApprovedMail( $user, $submission));
            exec('php artisan queue:work --once');

        } else if ($request->status == SubmissionStatus::DocumentRefused()) {
            event(new DocumentRefused($user, $submission));
        }

        $submission->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Submission Updated successfully!",
            'submission' => $submission
        ], 200);


    }

    public function update_document(Request $request, User $user, Submission $submission)
    {

        $request->validate([
            'document' => 'required',
            'status' => ['required', new EnumValue(SubmissionStatus::class,false)],
        ]);

        if(!Gate::allows('view', $user) || !Gate::allows('update', $submission)){
            abort(403);
        }

        $doc = FileController::decode_base64($request->document);
        $extension = FileController::get_file_extension($request->document);
        $doc_path = 'users/' . $user->id . '/submissions/' . $submission->id . '/file.' . $extension;
        FileController::store_file($doc_path, $doc, 's3');

        $submission->document_path = $doc_path;
        $submission->status = $request->status;
        $submission->save();

        $employee = User::find($submission->from_user);

        if ($submission->status == SubmissionStatus::Valid()) {
            event(new DocumentUploaded($user, $employee, $submission));
        } else {
            if ($submission->status != SubmissionStatus::SignatureRequired()) {
                event(new DocumentUploaded($employee, $user, $submission));
            }
        }

        return response()->json([
            'status' => true,
            'message' => "Document uploaded successfully!",
            'submission' => $submission
        ], 200);
    }

    public function delete(Request $request, User $user, Submission $submission)
    {

        if(!Gate::allows('view', $user) || !Gate::allows('update', $submission)){
            abort(403);
        }

        DB::table('reminder_mails')->where('submission_id', $submission->id)->delete();

        DB::table('submissions')->where('id', $submission->id)->delete();

        FileController::delete_folder('/users/' . $user->id . '/submissions/' . $submission->id);

        return response()->json([
            'status' => true,
            'message' => "Submission Deleted successfully!",
        ], 200);
    }

    public function create(Request $request, User $user)
    {
        $sender = auth()->user();
        $receiver = $user->id;

        $request->validate([
            'document_type' => 'required|string',
            'notes' => 'required|string',
            'document_name' => 'required|string',
            'status' => ['required', new EnumValue(SubmissionStatus::class,false)],
        ]);

        $new_submission = Submission::create([
            'document_type' => $request->document_type,
            'status' => $request->status,
            'notes' => $request->notes,
            'document_name' => $request->document_name,
            'to_user' => $receiver,
            'from_user' => $sender->id
        ]);

        if ($request->status != SubmissionStatus::Valid()) {
            event(new SubmissionCreated($user, $new_submission));
        }

        return response()->json([
            'message' => 'Submission Created Succesfully!',
            'submission' => $new_submission
        ], 201);
    }


}
