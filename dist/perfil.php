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

// Obtener el dni del usuario desde la solicitud GET y validarlo
$dni = $_GET['dni'];
if (!is_numeric($dni)) {
    die("Invalid DNI");
}

// Consulta a la base de datos usando consultas preparadas para obtener los datos del usuario
$sql = "SELECT * FROM users WHERE documento = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    // Vincular el parámetro
    $stmt->bind_param("i", $dni); // Asegúrate de que el tipo coincide (entero)

    // Ejecutar la consulta
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Obtener datos del usuario
        $user = $result->fetch_assoc();

        // Obtener el ID del usuario
        $user_id = $user['id'];

        // Realizar una segunda consulta para obtener los detalles del usuario desde otra tabla
        $sql_health = "SELECT * FROM tr_health_sheet WHERE userId = ?";
        $stmt_health = $conn->prepare($sql_health);
        if ($stmt_health) {
            $stmt_health->bind_param("i", $user_id);
            $stmt_health->execute();
            $result_health = $stmt_health->get_result();

            // Verificar si se encontraron resultados
            if ($result_health->num_rows > 0) {
                // Obtener detalles del usuario
                $user_health = $result_health->fetch_assoc();
            } else {
                $user_health = null; // No se encontraron detalles adicionales
            }

            $stmt_health->close();
        } else {
            die("Error preparando la consulta para detalles del usuario: " . $conn->error);
        }

        // Tercera consulta: Obtener actividades del usuario
        $sql_nutricional = "SELECT * FROM tr_sheet_nutritional WHERE userId = ?";
        $stmt_nutricional = $conn->prepare($sql_nutricional);
        if ($stmt_nutricional) {
            $stmt_nutricional->bind_param("i", $user_id);
            $stmt_nutricional->execute();
            $result_nutricional = $stmt_nutricional->get_result();

            if ($result_nutricional->num_rows > 0) {
                // Obtener todas las actividades del usuario
                $user_nutricional = $result_nutricional->fetch_assoc();
            } else {
                $user_nutricional = null;
            }

            $stmt_nutricional->close();
        } else {
            die("Error preparando la consulta para nutricion del usuario: " . $conn->error);
        }
    } else {
        die("No se encontraron resultados para el DNI ingresado.");
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
    <title>PID - Pulsera de Identificacion</title>
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

            <div class="flex flex-row">
                <a href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER']); ?>" class="flex flex-row gap-2 border-2 border-gray-900 rounded-full px-8 py-2 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-2">
                        <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3
                             256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                    </svg>
                    <p>Regresar</p>
                </a>
            </div>

            <h3 class="m-4 text-center font-base text-3xl">Información del Pasajero</h3>


            <div id="accordion-collapse" data-accordion="collapse" class="bg-white">
                <h2 id="accordion-collapse-heading-1">
                    <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
                        <span>Datos Personales</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">


                        <div class="flex flex-col items-center justify-center mx-auto">
                            <img class="w-40 rounded-full border-2 border-gray-600" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png" alt="Bonnie Avatar">
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Nombres</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["name"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Apellidos</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["apellidos"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-2 my-4">
                            <div>
                                <p class="text-gray-400 text-sm">Documento</p>
                                <p class="text-black text-lg">
                                    <?php echo htmlspecialchars($user["tip_documento"] ?? "--"); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">N° de Documento</p>
                                <p class="text-black text-lg">
                                    <?php echo htmlspecialchars($user["documento"] ?? "--"); ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-2 my-4">
                            <div>
                                <p class="text-gray-400 text-sm">Género</p>
                                <p class="text-black text-lg">
                                    <?php echo htmlspecialchars($user["sexo"] ?? "--"); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Fecha de Nacimiento</p>
                                <p class="text-black text-lg">
                                    <?php echo htmlspecialchars($user["nacimiento"] ?? "--"); ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Teléfono</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["telefono"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Correo</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["email"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Tipo de Viajero</p>
                            <p class="text-black text-lg">Alumno</p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Colegio</p>
                            <p class="text-black text-lg">El Buen Pastor</p>
                        </div>

                        <hr class="my-2">

                        <!--<div class="grid grid-cols-2 my-4">
                            <div>
                                <p class="text-gray-400 text-sm">Año</p>
                                <p class="text-black text-lg">5to</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Sección</p>
                                <p class="text-black text-lg">F</p>
                            </div>
                        </div>-->

                    </div>
                </div>

                <h2 id="accordion-collapse-heading-2">
                    <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-2" aria-expanded="false" aria-controls="accordion-collapse-body-2">
                        <span>Sobre Mí</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-collapse-body-2" class="hidden" aria-labelledby="accordion-collapse-heading-2">
                    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Hobbies</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["hobbies"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Deportes</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["deportes"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Plato Favorito</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["plato_fav"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Color</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["color_fav"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Actitud Relacional</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user["acti_relacional"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Otras Conductas</p>
                            <p class="text-black text-lg">Otras Conductas</p>
                        </div>

                    </div>
                </div>

                <h2 id="accordion-collapse-heading-3">
                    <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-3" aria-expanded="false" aria-controls="accordion-collapse-body-3">
                        <span>Responsable del Grupo</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Tipo</p>
                            <p class="text-black text-lg">Responsable</p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Nombres y Apellidos</p>
                            <p class="text-black text-lg">Sandra Quispe</p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Telefono</p>
                            <p class="text-black text-lg">993 540 492</p>
                        </div>
                    </div>
                </div>

                <h2 id="accordion-collapse-heading-4">
                    <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-4" aria-expanded="false" aria-controls="accordion-collapse-body-4">
                        <span>Ficha de Salud</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-collapse-body-4" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">

                        <div class="grid grid-cols-2 my-4">
                            <div class="flex flex-row">
                                <a class="flex flex-row gap-2 border-2 border-gray-900 rounded-full px-8 py-2 bg-white">
                                    <p>Decargar</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-3">
                                        <path d="M169.4 502.6c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3
                                             0L224 402.7 224 32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 370.7L86.6 
                                             329.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="flex flex-row">
                                <a class="flex flex-row gap-2 border-2 border-gray-900 rounded-full px-8 py-2 bg-white">
                                    <p>Enviar</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4" fill="green">
                                        <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7
                                         68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8
                                          18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9
                                           56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7
                                            5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7
                                             .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1
                                              5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6
                                               32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                                    </svg>
                                </a>
                            </div>
                        </div>


                        <div class="grid grid-cols-2 my-4">
                            <div>
                                <p class="text-gray-400 text-sm">Grupo Sanguíneo</p>
                                <p class="text-black text-lg">
                                    <?php echo htmlspecialchars($user_health["grupo_sanguineo"] ?? "--"); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Factor RH</p>
                                <p class="text-black text-lg">
                                    <?php echo htmlspecialchars($user_health["factor_rh"] ?? "--"); ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-3 my-4">
                            <div class="col-span-2">
                                <p class="text-black text-lg ">¿Recibes algun tratamiento de salud actual?</p>
                            </div>
                            <div>
                                <p class="text-black text-lg text-center">
                                    <?php echo htmlspecialchars($user_health["tratamiento"] ?? "--"); ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-3 my-4">
                            <div class="col-span-2">
                                <p class="text-black text-lg ">¿Presenta alguna enfermedad Pre-existente?</p>
                            </div>
                            <div>
                                <p class="text-black text-lg text-center">
                                    <?php echo htmlspecialchars($user_health["tratamiento"] ?? "--"); ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-3 my-4">
                            <div class="col-span-2">
                                <p class="text-black text-lg ">¿Es usted alérgico a algun medicamento?</p>
                            </div>
                            <div>
                                <p class="text-black text-lg text-center">
                                    <?php echo htmlspecialchars($user_health["tratamiento"] ?? "--"); ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-3 my-4">
                            <div class="col-span-2">
                                <p class="text-black text-lg ">¿Presenta alguna alergia adicional?</p>
                            </div>
                            <div>
                                <p class="text-black text-lg text-center">
                                    <?php echo htmlspecialchars($user_health["tratamiento"] ?? "--"); ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <div>
                                <p class="text-gray-400 text-sm">Inmunizaciones Recibidas</p>

                                <div class="mx-auto max-w-screen-sm">

                                    <?php $inmunizacion = $user_health["inmunizacion"] ?? "--";
                                    $inmunizacion_array = $inmunizacion !== "--" ? explode(',', $inmunizacion) : [];
                                    ?>
                                    <?php if (!empty($inmunizacion_array)) : ?>
                                        <ul>
                                            <?php foreach ($inmunizacion_array as $dato) : ?>
                                                <li class="text-black text-lg"><?php echo htmlspecialchars($dato); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else : ?>
                                        <p class="text-black text-lg">No tiene ninguna inmunizacion.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Vacunas Adicionales</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_health["vacunas_adicionales"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Vacunas de Covid</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_health["vacunas_covid"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Efectos Secundarios</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_health["efectos_secundarios"] ?? "--"); ?>
                            </p>
                        </div>
                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Informacion Adicional</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_health["informacion_adicional_salud"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Tarjeta de Seguro Médico</p>
                            <p class="text-black text-lg">Assist Card Peru</p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Tarjeta de Seguro Privado</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_health["tarjeta_seguro_privado"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-2 my-4">
                            <p class=" flex items-center">Historial Médico</p>
                            <div class="flex flex-row justify-end">
                                <a class="flex flex-col gap-2 border-2 border-gray-900 rounded-sm px-6 bg-white text-center justify-center items-center py-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-3">
                                        <path d="M169.4 502.6c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3
                                             0L224 402.7 224 32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 370.7L86.6 
                                             329.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128z" />
                                    </svg>
                                    <p>Descargar</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 id="accordion-collapse-heading-5">
                    <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-5" aria-expanded="false" aria-controls="accordion-collapse-body-5">
                        <span>Ficha Nutricional</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-collapse-body-5" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">

                        <div class="grid grid-cols-2 my-4">
                            <div class="flex flex-row">
                                <a class="flex flex-row gap-2 border-2 border-gray-900 rounded-full px-8 py-2 bg-white">
                                    <p>Decargar</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-3">
                                        <path d="M169.4 502.6c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3
                                             0L224 402.7 224 32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 370.7L86.6 
                                             329.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="flex flex-row">
                                <a class="flex flex-row gap-2 border-2 border-gray-900 rounded-full px-8 py-2 bg-white">
                                    <p>Enviar</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4" fill="green">
                                        <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7
                                         68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8
                                          18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9
                                           56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7
                                            5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7
                                             .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1
                                              5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6
                                               32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 my-4">
                            <div>
                                <p class="text-gray-400 text-sm">Peso</p>
                                <p class="text-black text-lg">
                                    <?php echo htmlspecialchars($user_nutricional["peso"] ?? "--"); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Talla</p>
                                <p class="text-black text-lg">
                                    <?php echo htmlspecialchars($user_nutricional["talla"] ?? "--"); ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Actividad Física</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_nutricional["actividad"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Tipos de Alimentación</p>
                            <p class="text-black text-lg">
                            <?php echo htmlspecialchars($user_nutricional["alimentacion"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Alergias Alimentarias</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_nutricional["alergias"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">¿Que alimentos no consume?</p>
                            <p class="text-black text-lg">
                            <?php echo htmlspecialchars($user_nutricional["no_consume"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Habitos Alimentarios</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_nutricional["habitos"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">Preferencias de Comidas Alimentarios</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_nutricional["pref_comida"] ?? "--"); ?>
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="grid grid-cols-1 my-4">
                            <p class="text-gray-400 text-sm">¿Sigues algun tipo de dieta?</p>
                            <p class="text-black text-lg">
                                <?php echo htmlspecialchars($user_nutricional["tipo_dieta"] ?? "--"); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="block w-full p-6 border border-blue-700 bg-blue-700 rounded-lg shadow mt-8">
                <h5 class="text-white font-bold text-xl py-2">Exención de Responsabilidad</h5>
                <p class="text-white font-light text-base	">
                    Esta información es confindencial y es solo para el uso en caso de emergencia,
                    por lo que cualquier uso indebido será sancionado de acuerdo a ley.
                </p>
            </div>

        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

</body>

</html>