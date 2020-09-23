<?php

namespace App\Http\Controllers;

use App\Backup;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $backups = Backup::orderBy('created_at', 'DESC')->get();

        return response()->json($backups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Create the zip archive
        $zip = new ZipArchive;
   
        // Generate the filename
        $fileName = 'backup_'.uniqid().'_'.time().'.zip';
   
        if ($zip->open(public_path('storage/backups/'.$fileName), ZipArchive::CREATE) === TRUE) {
            $unosorted_files = Storage::disk('documents')->files('unsorted');
            $sorted_files = Storage::disk('documents')->files('sorted');

            $file_count = count($unosorted_files) + count($sorted_files);

            foreach($unosorted_files as $key => $file) {
                $zip->addFile(public_path('storage/documents/'.$file), basename('unsorted/'.$file));
            }

            foreach($sorted_files as $key => $file) {
                $zip->addFile(public_path('storage/documents/'.$file), basename('sorted/'.$file));
            }
             
            $zip->close();
        }

        // Create the new backup entry
        $backup_db_entry = new Backup([
            'filename' => $fileName,
            'file_count' => $file_count,
            'file_size' => filesize(public_path('storage/backups/'.$fileName)),
        ]);

        $backup_db_entry->save();

        return response()->json([
            'status' => 'success',
            'backup' => $backup_db_entry,
        ]);
    }

    public function download(Backup $backup) {
        return Storage::disk('public')->download('backups/'.$backup->filename, $backup->filename);
    }

}
