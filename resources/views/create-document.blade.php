
<!DOCTYPE html>
<html>
<head>
    <title>Create Document</title>
</head>
<body>
    <h1>Create Document</h1>
    <form action="{{ route('create.document') }}" method="POST">
        @csrf
        <label for="documentText">Enter text for the document:</label><br>
        <input type="text" id="documentText" name="documentText" required><br><br>
        <button type="submit">Save Document</button>
    </form>
</body>
</html>
