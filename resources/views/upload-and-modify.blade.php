<!DOCTYPE html>
<html>
<head>
    <title>Upload and Modify Document</title>
</head>
<body>
    <h1>Upload and Modify Document</h1>
    <form action="{{ route('modify.document') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="document">Upload Word Document:</label>
            <input type="file" id="document" name="document" required>
        </div>
        <br>
        <div>
            <label for="circleCount">Number of Circles:</label>
            <input type="number" id="circleCount" name="circleCount" min="1" required>
        </div>
        <br>
        <div id="circles-container"></div>
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

    <script>
        document.getElementById('circleCount').addEventListener('change', function() {
            const count = parseInt(this.value, 10);
            const container = document.getElementById('circles-container');
            container.innerHTML = '';

            for (let i = 0; i < count; i++) {
                container.innerHTML += `
                    <div>
                        <label>Circle ${i + 1} Shape:</label>
                        <select name="circles[${i}][shape]" required>
                            <option value="oval" selected>Oval</option>
                            <option value="arc">Arc</option>
                            <option value="curve">Curve</option>
                            <option value="line">Line</option>
                            <option value="polyline">Polyline</option>
                            <option value="rect">Rect</option>
                        </select>
                        <label>Width:</label>
                        <input type="number" name="circles[${i}][width]" required>
                        <label>Height:</label>
                        <input type="number" name="circles[${i}][height]" required>
                        <label>Top:</label>
                        <input type="number" name="circles[${i}][top]" required>
                        <label>Left:</label>
                        <input type="number" name="circles[${i}][left]" required>
                        <label>Outline Color:</label>
                        <input type="color" name="circles[${i}][outlineColor]" required>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>
