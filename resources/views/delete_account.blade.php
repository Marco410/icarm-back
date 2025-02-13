

<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        html,
           body {
               background-color: #F4F5F7;
               color: #1A2228;
               font-family: 'Nunito', sans-serif;
               justify-content: center;
               margin: 0;
               height: 100vh;
               padding:20px;
               display: flex;
            }

            .container {
                max-width: 600px;
                width: 100%;
                text-align: center;
                background-color: white; 
                padding: 20px; 
                border-radius: 10px; 
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
                height: fit-content;

            }

            
            h1 {
                font-weight: 600;
                font-size: 25px;
                margin-bottom: 10px;
           }

           p{
            font-size: 13px;
           }
   
           img {
               border-radius: 25px;
               margin-top: 10px;
           }

           .btn {
            background: #454FD8;
            color: white;
            padding: 5px;
            border-radius: 8px;
            margin: 30px 10px 10px 10px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            border-color: transparent;
           }

           .styled-input {
                width: 95%;
                padding: 10px 15px;
                font-size: 14px;
                border: 2px solid #ccc;
                border-radius: 8px;
                outline: none;
                transition: all 0.3s ease;
                box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            }

            .styled-input:focus {
                border-color: #007bff;
                box-shadow: 2px 2px 8px rgba(0, 123, 255, 0.3);
            }

            .styled-input:hover {
                border-color: #888;
            }
   
   </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AyR Morelia App 📱</title>
    <link rel="icon" href="{{ asset('assets/logo_back.png') }}" type="image/png">

    
    <!-- Metadatos para SEO -->
    <meta name="description" content="💒 Amor & Restauración Morelia - Este espacio está destinado para que puedas eliminar tu cuenta de la aplicación de forma rápida y sencilla.">
    <meta name="keywords" content="iglesia, amor, restauración, morelia, comunidad, fe, app, cristiana">
    <meta name="author" content="ICARM">

    <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="Eliminar cuenta | Amor & Restauración Morelia | App Oficial ">
    <meta property="og:description" content="Este espacio está destinado para que puedas eliminar tu cuenta de la aplicación de forma rápida y sencilla. 🙏">
    <meta property="og:image" content="{{ asset('assets/logo_back.png') }}">
    <meta property="og:url" content="https://www.amoryrestauracionmorelia.com"> 
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Eliminar cuenta | Amor & Restauración Morelia - App Oficial 📱">
    <meta name="twitter:description" content="Este espacio está destinado para que puedas eliminar tu cuenta de la aplicación de forma rápida y sencilla. 🙏">
    <meta name="twitter:image" content="{{ asset('assets/logo_back.png') }}">

</head>
<body>
    
    <div class="container">
            <form id="deleteAccountForm">
            <h1>Amor & Restauración Morelia</h1>
            <h2>Eliminar mi Cuenta</h2>
            <a href="https://www.amoryrestauracionmorelia.com">
                <img src="{{ asset('assets/logo_back.png') }}" height="120px" alt="Logo app"> 
            </a>
            <p>Este espacio está destinado para que puedas eliminar tu cuenta de la aplicación de forma rápida y sencilla.</p>
            <p>✉️ Ingresa tu correo y, si se encuentra en nuestra base de datos, procederemos a eliminar tu cuenta en un momento.</p>
            <p>⚠️ Esta acción es irreversible.</p>

            <div style="display: flex; flex-direction: column; text-align: center; padding: 10px;">
                <input type="email" id="email" name="email" class="styled-input" placeholder="Escribe aquí..." required />
                
                <button type="submit" class="btn">Eliminar Cuenta</button>
            </div>
            
            <p>Si deseas volver a la app o contactar con nosotros, consulta nuestras redes sociales o visita nuestra página web <a href="https://www.amoryrestauracionmorelia.com">www.amoryrestauracionmorelia.com</a></p>
        </form>
    </div>
        
    </body>
</html>

<script>
     document.getElementById("deleteAccountForm").addEventListener("submit", function(event) {
        event.preventDefault(); 

        const email = document.getElementById("email").value;

        if (!email) {
            alert("Por favor, ingresa tu correo.");
            return;
        }

        const confirmDelete = confirm("⚠️ ¿Estás seguro de que deseas eliminar tu cuenta? Esta acción es irreversible.");

        if (confirmDelete) {
            fetch("https://www.amoryrestauracionmorelia.com/api/auth/delete", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if(data.code == 404){
                    alert(data.description.message || "No pudimos encontrar una cuenta asociada a este correo.");
                }else{
                    document.getElementById("deleteAccountForm").reset();
                    alert(data.message || "Tu cuenta ha sido eliminada con éxito.");
                }
            })
            .catch(error => {
                alert("Hubo un error al eliminar la cuenta. Inténtalo nuevamente.");
                console.error("Error:", error);
            });
        }
    });

</script>