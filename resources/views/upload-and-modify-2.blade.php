<!DOCTYPE html>
<html>
<head>
    <title>Upload and Modify Document NUMBER 2</title>
</head>
<body>
    <h1>Upload and Modify Document NUMBER 2</h1>
    <form action="{{ route('modify.document2') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="document">Upload Word Document:</label>
            <input type="file" id="document" name="document" required>
        </div>
        <br>
        <div>
            <label for="someText">Additional Texts:</label>
            <input type="text" id="someText" name="someText">
        </div>
        <br>
        <div>
            <label for="documentName">Enter new name for the document:</label>
            <input type="text" id="documentName" name="documentName" required>
        </div>
        <br>
        <button type="submit">Modify and Download Document</button>
    </form>

</body>
</html>
