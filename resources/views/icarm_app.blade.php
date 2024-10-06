

<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        html,
           body {
               background-color: #F4F5F7;
               color: #636b6f;
               font-family: 'Nunito', sans-serif;
               height: 100vh;
               justify-content: center;
               height: 100vh;
               text-align: center
            }
            
            h1 {
                font-weight: 600;
                font-size: 40px;
               margin-bottom: 10px;
           }
   
           img {
               border-radius: 25px;
           }
   
   </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ICARM App 📱</title>
    
    <!-- Metadatos para SEO -->
    <meta name="description" content="💒 Amor & Restauración Morelia - Conéctate con nuestra comunidad a través de la app oficial.">
    <meta name="keywords" content="iglesia, amor, restauración, morelia, comunidad, fe, app, cristiana">
    <meta name="author" content="ICARM">

    <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="Amor & Restauración Morelia - App Oficial ">
    <meta property="og:description" content="Descarga la app oficial de Amor & Restauración Morelia y conéctate con nuestra comunidad donde quiera que estés. 🙏">
    <meta property="og:image" content="{{ asset('assets/logo_back.png') }}">
    <meta property="og:url" content="https://www.amoryrestauracionmorelia.com"> 
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Amor & Restauración Morelia - App Oficial 📱">
    <meta name="twitter:description" content="Descarga la app oficial de Amor & Restauración Morelia y conéctate con nuestra comunidad. 🙏💒">
    <meta name="twitter:image" content="{{ asset('assets/logo_back.png') }}">

</head>
<body>

    <h1>Amor & Restauración Morelia</h1>
    <img src="{{ asset('assets/logo_back.png') }}" height="200px" alt="Logo app"> 
    
</body>
</html>

<script>
    const playStoreUrl = "https://play.google.com/store/apps/details?id=com.mtm.icarm";
    const appStoreUrl = "https://apps.apple.com/mx/app/ayr-morelia/id6449270971?l=en-GB";

    const userAgent = navigator.userAgent.toLowerCase();


    // Redirigir según el dispositivo
    if (userAgent.includes('iphone') || userAgent.includes('ipad') || userAgent.includes('macintosh')) {
        // Dispositivos de Apple
        window.location.href = appStoreUrl;
    } else if (userAgent.includes('android') || userAgent.includes('windows')) {
        // Dispositivos Android o Windows
        window.location.href = playStoreUrl;
    } else {
        // Opción por defecto si no se detecta un dispositivo compatible
        console.log("Dispositivo no compatible detectado.");
    }
</script>