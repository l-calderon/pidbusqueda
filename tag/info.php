<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "roxana";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID del usuario desde la solicitud GET y validarlo
$user_id = $_GET['dni'];
if (!is_numeric($user_id)) {
    die("Invalid user ID");
}

// Consulta a la base de datos usando consultas preparadas
$sql = "SELECT * FROM users WHERE documento = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Obtener datos del usuario
        $user = $result->fetch_assoc();
    } else {
        die("No se encontraron resultados para el ID de usuario ingresado.");
    }

    $stmt->close();
} else {
    die("Error preparando la consulta: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAG - Datos de Maleta</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHqT9Hi0WxfQyZjK3+1bF7/5Z1eF13fS3VQgWIpFF2gjc+2a2U3FgptwLw1So4sc3fpEYfBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">

</head>

<body>
    <section class="bg-gray-100 dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6 ">
            <div class="mx-auto text-center mb-8 lg:mb-16 flex justify-center">
                <a href="index.html">
                    <img src="../src/logo-horizontal.svg" alt="" width="600px">
                </a>
            </div>

            <hr>

            <!-- primera seccion -->
            <div class="block w-full p-6 my-8 bg-white border border-gray-200 rounded-lg shadow">

                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class=" w-5">
                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7
                             512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                    </svg>
                    <p class=" text-xl m-4 font-bold">Información del Pasajero</p>
                </div>
                <div class="flex flex-col items-center justify-center mx-auto">
                    <p class='mt-4 text-2xl'>
                        <?php echo htmlspecialchars(explode(" ", $user["name"])[0] . " " . explode(" ", $user["apellidos"])[0]); ?>
                    </p>
                </div>

                <hr class="my-4">

                <div class="grid grid-cols-2 my-4">
                    <div class="flex flex-row items-center gap-2">
                        <div class=" bg-gray-200 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class=" w-4">
                                <path d="M0 96l576 0c0-35.3-28.7-64-64-64L64 32C28.7 32 0 60.7 0 96zm0 32L0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7
                                     64-64l0-288L0 128zM64 405.3c0-29.5 23.9-53.3 53.3-53.3l117.3 0c29.5 0 53.3 23.9 53.3 53.3c0 5.9-4.8 10.7-10.7 10.7L74.7
                                      416c-5.9 0-10.7-4.8-10.7-10.7zM176 192a64 64 0 1 1 0 128 64 64 0 1 1 0-128zm176 16c0-8.8 7.2-16 16-16l128 0c8.8 0 16
                                       7.2 16 16s-7.2 16-16 16l-128 0c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16l128 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-128
                                        0c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16l128 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-128 0c-8.8 0-16-7.2-16-16z" />
                            </svg>
                        </div>
                        <div>
                            <p class=" text-xs">Documento</p>
                            <p class=" text-lg">
                                <?php echo htmlspecialchars($user["tipo_documento"] ?? "--"); ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-row items-center gap-2">
                        <div class="bg-gray-200 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4">

                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM164.1 325.5C182 346.2 212.6 368 256 368s74-21.8 91.9-42.5c5.8-6.7 15.9-7.4
                                     22.6-1.6s7.4 15.9 1.6 22.6C349.8 372.1 311.1 400 256 400s-93.8-27.9-116.1-53.5c-5.8-6.7-5.1-16.8 1.6-22.6s16.8-5.1 22.6 1.6zM144.4
                                      208a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm192-32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" />
                            </svg>
                        </div>
                        <div>
                            <p class=" text-xs">Numero de Documento</p>
                            <p class="text-lg">
                                <?php echo htmlspecialchars($user["num_documento"] ?? "--"); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 my-4">
                    <div class="flex flex-row items-center gap-2">
                        <div class="bg-gray-200 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4">

                                <path d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48
                                            64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8
      0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32
       0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0
        8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32
         0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8
          0-16 7.2-16 16z" />
                            </svg>
                        </div>
                        <div>
                            <p class=" text-xs">F. de Emisión</p>
                            <p class="text-lg">
                                <?php echo htmlspecialchars($user["fecha_emi"] ?? "--"); ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-row items-center gap-2">
                        <div class=" bg-gray-200 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4">

                                <path d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48
                                     64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8
                                      0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32
                                       0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0
                                        8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32
                                         0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8
                                          0-16 7.2-16 16z" />
                            </svg>
                        </div>
                        <div>
                            <p class=" text-xs">F. de Vencimiento</p>
                            <p class="text-lg">
                                <?php echo htmlspecialchars($user["fecha_venc"] ?? "--"); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- segunda seccion -->
            <div class="block w-full p-6 my-8 bg-white border border-gray-200 rounded-lg shadow">

                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="w-4">
                        <path d="M208 352c114.9 0 208-78.8 208-176S322.9 0 208 0S0 78.8 0 176c0 38.6 14.7 74.3 39.6 103.4c-3.5 9.4-8.7 17.7-14.2 24.7c-4.8 6.2-9.7 11-13.3
         14.3c-1.8 1.6-3.3 2.9-4.3 3.7c-.5 .4-.9 .7-1.1 .8l-.2 .2s0 0 0 0s0 0 0 0C1 327.2-1.4 334.4 .8 340.9S9.1 352 16 352c21.8 0 43.8-5.6 62.1-12.5c9.2-3.5
          17.8-7.4 25.2-11.4C134.1 343.3 169.8 352 208 352zM448 176c0 112.3-99.1 196.9-216.5 207C255.8 457.4 336.4 512 432 512c38.2 0 73.9-8.7 104.7-23.9c7.5
           4 16 7.9 25.2 11.4c18.3 6.9 40.3 12.5 62.1 12.5c6.9 0 13.1-4.5 15.2-11.1c2.1-6.6-.2-13.8-5.8-17.9c0 0 0 0 0 0s0 0 0
            0l-.2-.2c-.2-.2-.6-.4-1.1-.8c-1-.8-2.5-2-4.3-3.7c-3.6-3.3-8.5-8.1-13.3-14.3c-5.5-7-10.7-15.4-14.2-24.7c24.9-29 39.6-64.7
             39.6-103.4c0-92.8-84.9-168.9-192.6-175.5c.4 5.1 .6 10.3 .6 15.5z" />
                    </svg>
                    <p class="text-xl m-4 font-bold">Informacion de Maletas</p>
                </div>

                <hr class="py-2">

                <div class="grid grid-cols-2 sm:grid-cols-1">
                    <div>
                        <p class="text-gray-400 text-sm">Imagen de maleta 8kg</p>
                        <p class="text-black text-lg">
                            <?php echo htmlspecialchars($user["equipaje_8kg"] ?? "--"); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Imagen de maleta 23kg</p>
                        <p class="text-black text-lg">
                            <?php echo htmlspecialchars($user["equipaje_8k"] ?? "--"); ?>
                        </p>
                    </div>
                </div>

                <hr class="py-2">

                <div class="grid grid-cols-2 sm:grid-cols-1">
                    <div>
                        <p class="text-gray-400 text-sm">Descripción maleta 8kg</p>
                        <p class="text-black text-lg">
                            <?php echo htmlspecialchars($user["descrip_8kg"] ?? "--"); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Descripción maleta 23kg</p>
                        <p class="text-black text-lg">
                            <?php echo htmlspecialchars($user["descrip_23kg"] ?? "--"); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- passboarding seccion -->
            <div class="block w-full p-6 my-8 bg-white border border-gray-200 rounded-lg shadow">

                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="w-4">
                        <path d="M208 352c114.9 0 208-78.8 208-176S322.9 0 208 0S0 78.8 0 176c0 38.6 14.7 74.3 39.6 103.4c-3.5 9.4-8.7 17.7-14.2 24.7c-4.8 6.2-9.7 11-13.3
                         14.3c-1.8 1.6-3.3 2.9-4.3 3.7c-.5 .4-.9 .7-1.1 .8l-.2 .2s0 0 0 0s0 0 0 0C1 327.2-1.4 334.4 .8 340.9S9.1 352 16 352c21.8 0 43.8-5.6 62.1-12.5c9.2-3.5
                          17.8-7.4 25.2-11.4C134.1 343.3 169.8 352 208 352zM448 176c0 112.3-99.1 196.9-216.5 207C255.8 457.4 336.4 512 432 512c38.2 0 73.9-8.7 104.7-23.9c7.5
                           4 16 7.9 25.2 11.4c18.3 6.9 40.3 12.5 62.1 12.5c6.9 0 13.1-4.5 15.2-11.1c2.1-6.6-.2-13.8-5.8-17.9c0 0 0 0 0 0s0 0 0
                            0l-.2-.2c-.2-.2-.6-.4-1.1-.8c-1-.8-2.5-2-4.3-3.7c-3.6-3.3-8.5-8.1-13.3-14.3c-5.5-7-10.7-15.4-14.2-24.7c24.9-29 39.6-64.7
                             39.6-103.4c0-92.8-84.9-168.9-192.6-175.5c.4 5.1 .6 10.3 .6 15.5z" />
                    </svg>
                    <p class="text-xl m-4 font-bold">Informacion de Passboarding</p>
                </div>

                <hr class="py-2">

                <div class="grid grid-cols-1 sm:grid-cols-1">
                    <div>
                        <p class="text-gray-400 text-sm">Imagen de pass boarding</p>
                        <p class="text-black text-lg">
                            <?php echo htmlspecialchars($user["pass_board"] ?? "Solo si la aerolinea lo permitio"); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="block w-full p-6 border border-blue-700 bg-blue-700 rounded-lg shadow">
                <h5 class="text-white font-bold text-xl py-2">Descripci+ón de la Web</h5>
                <p class="text-white font-light text-base	">
                    Esta información es solo para el uso del usuario confindencial,
                    para tener en cuenta datos de sus maletas, documentos y/o passboarding.
                </p>
            </div>

        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

    <script>
        document.getElementById('sendLocation').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(sendLocationToWhatsApp, showError);
            } else {
                alert('La geolocalización no es soportada por este navegador.');
            }
        });

        function sendLocationToWhatsApp(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            var phoneNumber = "51952172143"; // Reemplaza con el número de teléfono al que quieres enviar el mensaje (con código de país, sin signos de "+" ni espacios)
            var whatsappMessage = `https://wa.me/${phoneNumber}?text=Mi%20ubicación%20actual%20es:%20https://www.google.com/maps?q=${latitude},${longitude}`;
            window.open(whatsappMessage, '_blank');
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("El usuario ha denegado la solicitud de geolocalización.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("La información de la ubicación no está disponible.");
                    break;
                case error.TIMEOUT:
                    alert("La solicitud para obtener la ubicación ha expirado.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Ha ocurrido un error desconocido.");
                    break;
            }
        }
    </script>

</body>

</html>