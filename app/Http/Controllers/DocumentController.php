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



        // // Add a shape (circle) without fill color for transparency effect
        // $shapeStyle = [
        //     // 'type' => Shape::OVAL,
        //     'type' => 'oval',
        //     'width' => 100,
        //     'height' => 100,
        //     'borderColor' => '000000',
        //     'borderSize' => 2,
        //     'wrap' => 'behind',
        // ];
        // $shape = $section->addShape('oval', $shapeStyle);
        // // Add a text box near the circle
        // $textboxStyle = [
        //     'width' => 100,
        //     'height' => 100,
        //     'borderColor' => 'FFFFFF', // Transparent border
        //     'borderSize' => 0,
        // ];
        // $textbox = $section->addTextBox($textboxStyle);
        // $textbox->addText('Your text here', ['bold' => true]);


        // $textbox->setPosHorizontal('center');
        // $textbox->setPosVertical('center');
        
        $imagePath = "../public/images/hollow_circle.png";

        $options = [
            'width'         => 200,
            'height'        => 200,
            'marginTop'     => -1,
            'marginLeft'    => -1,
            'wrappingStyle' => 'infront',
            'positioning' => 'relative'
            // 'frame' => [
            //     'width' => 100,
            //     'height' =>1200,
            //     'top' => -400,
            //     'left' => 100,
            //     'pos' => 'absolute',
            // ],
            // // 'roundness' => 0,
            // 'fill' => [
            //     'color' => '#FFFFFF',
            //     'opacity' => 0
            // ],
            // 'outline' => [
            //     'unit' => '5',
            //     'color' => '#FF0000',
            //     'line' => 'thinThick'
            // ],
            // 'weight' => 5,
            // 'wrap' => 'behind',
            // 'wrappingStyle' => 'behind',
        ];
        $section->addImage(
            $imagePath,
            $options
        );
        // Add the text from the input field to the section
        $section->addText($request->input('documentText'));
        // // Add a textbox
        // $section->addTextBox([
        //     'roundness' => 0.5,
        //     'bgColor' => '#00FFFFFF',
        //     'fill' => ['color' => '#33CC99', 'bgColor' => '#33CC99'],
        //     'shading' => ['fill' => '#0000ffff'],
        //     // 'bgColor' => ['fill' => '#0000ffff']
        // ]);

        
        // $options = [
        //     'frame' => [
        //         'width' => 200,
        //         'height' => 200,
        //         'top' => -400,
        //         'left' => 100,
        //         'pos' => 'absolute',
        //     ],
        //     // 'roundness' => 0,
        //     'fill' => [
        //         'color' => '#FFFFFF',
        //         'opacity' => 0
        //     ],
        //     'outline' => [
        //         'unit' => '5',
        //         'color' => '#FF0000',
        //         'line' => 'thinThick'
        //     ],
        //     'weight' => 5,
        //     'wrap' => 'behind',
        //     'wrappingStyle' => 'behind',
        // ];

        // $options2 = [
        //     'frame' => [
        //         'width' => 200,
        //         'height' => 200,
        //         'top' => -400,
        //         'left' => 10,
        //         'pos' => 'absolute',
        //     ],
        //     // 'roundness' => 0,
        //     'fill' => [
        //         'color' => '#000000',
        //         'opacity' => 0
        //     ],
        //     'outline' => [
        //         'unit' => '5',
        //         'color' => '#FF0000',
        //         'line' => 'thinThick'
        //     ],
        //     'wrap' => 'behind',
        //     'wrappingStyle' => 'behind',
        // ];

        // // Oval
        // $section->addShape(
        //     'line',
        //     $options
        // );

        // // Oval
        // $section->addShape(
        //     'line',
        //     $options2
        // );

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




    // THIRD
    /**
     * Show the upload form
     *
     */
    public function showUploadForm2()
    {
        return view('upload-and-modify-2');
    }

    public function modifyDocument2(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:doc,docx',
            'someText' => 'string',
            'documentName' => 'required|string',
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
            // $section = $myDocument->addSection();
            $sections = $myDocument->getSections();
            $section = $sections[0];
            // $imagePath = "../public/images/hollow_circle.png";
            $imagePath = public_path('images/hollow_circle.png');

            // Debugging: Check if the image path is correct and file is readable
            if (!file_exists($imagePath) || !is_readable($imagePath)) {
                throw new \Exception("Image file not found or not readable at path: $imagePath");
            }
            $options = [
                'width'         => 200,
                'height'        => 300,
                'wrappingStyle' => 'infront',
                'positioning' => 'absolute',
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_LEFT,
                'posHorizontalRel' => 'page',
                'posVerticalRel' => 'page',
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
                // 'marginTop'     => 2000,
                // 'marginLeft'    => 1000,
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(20),
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(20),
            ];
            $section->addImage(
                $imagePath,
                $options
            );

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
