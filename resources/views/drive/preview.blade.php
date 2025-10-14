<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau: {{ $file->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #viewer-container { border: 1px solid #ccc; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Pratinjau File: {{ $file->name }}</h3>
    <div id="viewer-container"></div>

    <script src="https://unpkg.com/docx-preview@0.1.15/dist/docx-preview.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileUrl = "{{ route('drive.download', $file->id) }}";
            const fileExtension = "{{ pathinfo($file->name, PATHINFO_EXTENSION) }}";
            const container = document.getElementById("viewer-container");

            // Ambil data file dari server
            fetch(fileUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Gagal mengambil file");
                    }
                    return response.blob(); // Ambil file sebagai data biner (blob)
                })
                .then(blob => {
                    if (fileExtension === 'docx') {
                        // Proses file .docx
                        docx.renderAsync(blob, container)
                            .then(x => console.log("docx: render complete."))
                            .catch(error => console.error(error));

                    } else if (fileExtension === 'xlsx') {
                        // Proses file .xlsx
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const data = new Uint8Array(event.target.result);
                            const workbook = XLSX.read(data, { type: 'array' });
                            const firstSheetName = workbook.SheetNames[0];
                            const worksheet = workbook.Sheets[firstSheetName];
                            const html = XLSX.utils.sheet_to_html(worksheet);
                            container.innerHTML = html;
                        };
                        reader.readAsArrayBuffer(blob);
                    } else {
                        container.innerHTML = "<p>Pratinjau tidak didukung untuk tipe file ini.</p>";
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    container.innerHTML = "<p>Gagal memuat pratinjau file.</p>";
                });
        });
    </script>
</body>
</html>