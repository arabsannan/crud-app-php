<?php




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
	<title>Gallery</title>
</head>
<body>
        
    <!-- Gallery images -->
    <section class="overflow-hidden text-gray-700 ">
    

        <div class="container px-5 py-2 mx-auto lg:pt-6 lg:px-32">
            <div class="grid grid-cols-3 ">
                <a href="home.php" class="text-blue-500 hover:underline font-medium">
					Back to home 
				</a>
                <h1 class="text-xl text-center text-blue-800 font-bold py-6">YOUR GALLERY</h1>
            </div>

            <div class="flex flex-wrap -m-1 md:-m-2 overflow-auto">
                <div class="flex flex-wrap w-1/3">
                    <div class="w-full p-1 md:p-2">
                    <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg"
                        src="../assets/img/landscape.jfif">
                    </div>
                </div>
                <div class="flex flex-wrap w-1/3">
                    <div class="w-full p-1 md:p-2">
                    <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg"
                        src="../assets/img/landscape2.jfif">
                    </div>
                </div>
                <div class="flex flex-wrap w-1/3">
                    <div class="w-full p-1 md:p-2">
                    <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg"
                        src="../assets/img/landscape.jfif">
                    </div>
                </div>
                <div class="flex flex-wrap w-1/3">
                    <div class="w-full p-1 md:p-2">
                    <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg"
                        src="../assets/img/landscape1.jfif">
                    </div>
                </div>
                <div class="flex flex-wrap w-1/3">
                    <div class="w-full p-1 md:p-2">
                    <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg"
                        src="../assets/img/landscape1.jfif">
                    </div>
                </div>
                <div class="flex flex-wrap w-1/3">
                    <div class="w-full p-1 md:p-2">
                    <img alt="gallery" class="block object-cover object-center w-full h-full rounded-lg"
                        src="../assets/img/landscape2.jfif">
                    </div>
                </div>
            </div>
            <!-- Add a photo button -->
            <button
                type="submit"
                class="inline-block mt-7 py-3 px-3 my-4 bg-blue-800 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                data-mdb-ripple="true"
                data-mdb-ripple-color="light" name="addPhotoBtn"
            >
                Add a new photo
            </button>
        </div>

        
    </section>
    
</body>
</html>