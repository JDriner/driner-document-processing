<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Storage;
class DocumentController extends Controller
{
    public function create()
    {
        return view('create-document');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'documentText' => 'required|string',
        ]);

        // Create a new PHPWord object
        $phpWord = new PhpWord();

        // Add a new section to the document
        $section = $phpWord->addSection();

        // Add the text from the input field to the section
        $section->addText($request->input('documentText'));

        // Rectangle
        $section->addShape(
            'rect',
            [
                'width' => 100,
                'height' => 100,
                'fill' => ['color' => 'FFFF00'],
                'border' => ['color' => 'FF0000', 'size' => 5],
            ]
        );


        // round Rectable
        // $section->addShape(
        //     'roundRect',
        //     [
        //         'width' => 100,
        //         'height' => 100,
        //         'fill' => ['color' => 'FFFF00'],
        //         'border' => ['color' => 'FF0000', 'size' => 10],
        //     ]
        // );

        // Oval
        $section->addShape(
            'oval',
            [
                'width' => 100,
                'height' => 100,
                'fill' => ['color' => 'FFFF00'],
                'border' => ['color' => 'FF0000', 'size' => 10],
            ]
        );

        // Save the document to the public directory
        $fileName = 'custom_document.docx';
        $filePath = public_path($fileName);
        $phpWord->save($filePath, 'Word2007');

        // Return the document as a download response
        return response()->download($filePath);
    }



    /**
     * Show the upload form
     *
     */
    public function showUploadForm()
    {
        return view('upload-and-modify');
    }

    public function modifyDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:doc,docx',
            'circleCount' => 'required|integer|min:1',
            'someText' => 'string',
            'documentName' => 'required|string',
            'circles.*.shape' => 'required|string|in:arc,curve,line,polyline,rect,oval',
            'circles.*.width' => 'required|integer|min:1',
            'circles.*.height' => 'required|integer|min:1',
            'circles.*.top' => 'required|integer',
            'circles.*.left' => 'required|integer',
            'circles.*.outlineColor' => 'required|string',
        ]);

        try {
            // Get the uploaded file and the number of circles to add
            $file = $request->file('document');
            $someText = $request->input('someText');
            $circles = $request->input('circles');
            $fileName = $request->input('documentName').'.docx';
            
            // Load the existing Word document
            $myDocument = IOFactory::load($file->getPathname());
    
            // Adding an empty Section to the document...
            $section = $myDocument->addSection();
            // dd($position);

            if ($someText) {
                // Adding some texts to the document
                $section->addText($someText);
            }

            // Modify the document by adding the shapes
            foreach ($circles as $circle) {
                // Available types 'arc', 'curve', 'line', 'polyline', 'rect', 'oval'
                $shape = $circle['shape'];

                $options = [
                    'frame' => [
                        'width' => $circle['width'],
                        'height' => $circle['height'],
                        'top' => $circle['top'],
                        'left' => $circle['left'],
                        'pos' => 'relative',
                    ],
                    // 'roundness' => 0,
                    'fill' => [
                        'nofill' => true,
                        'color' => '#00FFFFFF'
                    ],
                    'outline' => [
                        'color' => $circle['outlineColor'],
                        'size' => 30
                    ],
                ];


                // ADD THE SHAPE ELEMENT
                $section->addShape($shape, $options);
            }
    
            $filePath = storage_path('app/public/' . $fileName);
            $myDocument->save($filePath, 'Word2007');
    
            // Return the modified document as a download response
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error modifying document: ' . $e->getMessage());
        }
    }
}
