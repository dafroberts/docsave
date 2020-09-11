<?php

namespace App\Http\Controllers;

use App\Document;
use App\Tag;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class DocumentController extends Controller
{
    /**
     * Returns a list of all unsorted documents
     */
    public function unsorted() {
        // Get all documents in the unsorted folder
        $docs = array_map(function($doc) {
            $this_doc = new File(Storage::disk('documents')->path($doc));

            return [
                'full_url' => Storage::disk('documents')->url($doc),
                'filename' => $this_doc->getFilename(),
                'extension' => $this_doc->getExtension(),
            ];
        }, Storage::disk('documents')->allFiles('unsorted'));

        return response()->json([
            'unsorted' => $docs,
            'folder_url' => Storage::disk('documents')->url(''),
        ]);
    }

    public function latest() {
        return response()->json([
            'documents' => Document::orderBy('id', 'DESC')->limit(4)->get(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'documents' => Document::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: Validation

        // Copy the file from the unsorted folder to the sorted one (with a unique name)
        $originalDocRelativePath = 'unsorted/'.$request->input('fileName');
        $originalDocPath = Storage::disk('documents')->path($originalDocRelativePath);
        $originalDocFile = new File($originalDocPath);
        $newDocFilename = uniqid().'_'.time().'.'.$originalDocFile->getExtension();
        Storage::disk('documents')->copy($originalDocRelativePath, 'sorted/'.$newDocFilename);
        
        // Create new document
        $document = new Document([
            'name' => $request->input('name'),
            'location' => $newDocFilename,
            'created_at' => $request->input('date') ?? null,
        ]);
        
        // Create any new tags
        // Loop through the provided tags and create any ones that don't exist and find existing ones and add them to the 'tags to assign' array
        $tagsToAssign = [];
        $tags = $request->input('tags');
        if(is_array($tags)) {
            foreach($tags as $tag) {
                if(isset($tag['id'])) {
                    $tagsToAssign[] = $tag['id'];
                } else {
                    $newTag = new Tag([
                        'name' => $tag['text'],
                    ]);
                    $newTag->save();
                    $tagsToAssign[] = $newTag->id;
                }
            }
        }

        // Save the document
        $document->save();

        // Synchronise the tags to the newly created document
        $document->tags()->sync($tagsToAssign);

        // Delete the untracked file
        Storage::disk('documents')->delete($originalDocRelativePath);

        // TODO: Return success message
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }

    public function generatePdfThumbnail() {
        $pdf = new Pdf(Storage::disk('documents')->path('5f57e0df20883_1599594719.pdf'));
        $pdf->saveImage(Storage::disk('documents')->path('previews/test.jpg'));
    }

    public function search(Request $r) {
        $search = explode(' ', $r->input('query'));

        if($search) {
            $results = Document::whereHas('tags', function(Builder $query) use($search) {
                $query->whereIn('name', $search);
            });

            foreach($search as $s) {
                $results = $results->orWhere('name', 'like', '%'.$s.'%');
            }
            
            $results = $results->get();
        } else {
            $results = [];
        }

        return response()->json([
            'results' => $results,
        ]);
    }

    public function download(Document $document) {
        return Storage::disk('documents')->download('sorted/'.$document->location, $document->name.'.pdf');
    }
}
