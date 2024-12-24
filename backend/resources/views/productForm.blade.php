<!DOCTYPE html>
<html lang="en">http://localhost/

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        laravel: "#ef3b2d",
                    },
                },
            },
        };
    </script>
    <title>Product Form</title>
</head>

<body class="mb-48">
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <form id="uploadForm" enctype="multipart/form-data" class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
            @csrf

            <!-- Title Field -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full p-2 border rounded-md"
                    placeholder="Enter title" required />
            </div>

            <!-- Category Field -->
            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <input type="text" id="category" name="category" class="mt-1 block w-full p-2 border rounded-md"
                    placeholder="Enter category" required />
            </div>

            <!-- Price Field -->
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" id="price" name="price" class="mt-1 block w-full p-2 border rounded-md"
                    placeholder="Enter price" required />
            </div>

            <!-- Description Field -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full p-2 border rounded-md"
                    placeholder="Enter description" required></textarea>
            </div>

            <!-- File Upload -->
            <div class="mb-4">
                <label for="images" class="block text-sm font-medium text-gray-700">Upload Images</label>
                <input type="file" id="images" name="images[]" multiple
                    class="mt-1 block w-full p-2 border rounded-md" required />
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                    Upload Product
                </button>
            </div>
        </form>

    </div>
</body>

</html>


<script>
    $(document).ready(function() {
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();


            let formData = new FormData(this);


            var title = $('#title').val();
            var category = $('#category').val();
            var price = $('#price').val();
            var description = $('#description').val();


            formData.append('title', title);
            formData.append('category', category);
            formData.append('price', price);
            formData.append('description', description);

            $.ajax({
                url: 'http://127.0.0.1:8000/api/vendor/addproduct',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "Authorization": "Bearer " +
                        "1|LxUoSaiHJwaKquEL2qYKHviWqM0AErC4rLAdrZaq28585df2"
                },
                success: function(response) {
                    alert(response.message);
                    console.log(response.files);
                },
                error: function(xhr, status, error) {
                    console.error('Upload failed:', error);
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
