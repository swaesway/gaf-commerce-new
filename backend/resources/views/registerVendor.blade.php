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
        <form id="registerVendor" enctype="multipart/form-data" class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
            @csrf

            <input type="file" id="pob" name="pob" />

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                    register vendor
                </button>
            </div>
        </form>

    </div>
</body>

</html>


<script>
    $(document).ready(function() {
        $('#registerVendor').on('submit', function(e) {
            e.preventDefault();


            let formData = new FormData(this);


            formData.append('shopname', "Admoooooooooooooooo");
            formData.append('email', "admo@gmail.com");
            formData.append('telephone', "0201806401");
            formData.append('location', "Accra");
            formData.append('region', "Greater Accra");
            formData.append('password', "1234567890");
            formData.append('password_confirmation', "1234567890");

            $.ajax({
                url: 'http://127.0.0.1:8000/api/vendor/register',
                type: 'POST',
                data: formData,

                processData: false,
                contentType: false,

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
