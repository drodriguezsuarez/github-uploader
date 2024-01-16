<?php
/*
Plugin Name: GitHub Uploader
Description: Sube automáticamente el código a GitHub.
Version: 1.0
Author: Daniel Rodriguez Suarez
*/

// Acciones del plugin aquí

require_once ABSPATH . '/path/to/vendor/autoload.php'; // Reemplaza con la ruta correcta

use Gitonomy\Git\Repository;

function subir_a_github() {
    // Ruta al directorio del plugin
    $plugin_directory = plugin_dir_path(__FILE__);

    // Crea un nuevo repositorio Git
    $repo = new Repository($plugin_directory);

    // Configura el nombre y el correo electrónico del autor
    $config = $repo->getConfig();
    $config->set('user.name', 'Tu Nombre');
    $config->set('user.email', 'tu@email.com');

    // Añade todos los archivos al índice
    $index = $repo->getIndex();
    $index->addAll();
    $index->write();

    // Realiza un commit
    $repo->run('commit', array('-m' => 'Primer commit'));

    // Crea un nuevo repositorio en GitHub utilizando la API de GitHub
    $token = 'tu_token_de_acceso_a_la_api_de_github'; // Reemplaza con tu token
    $repo_name = 'nombre_de_tu_repositorio_en_github'; // Reemplaza con el nombre de tu repositorio en GitHub

    $api_url = 'https://api.github.com/user/repos';
    $data = array(
        'name' => $repo_name,
        'private' => false, // Puedes cambiar esto según tus preferencias
    );

    $options = array(
        'http' => array(
            'header' => "Authorization: token $token",
            'method' => 'POST',
            'content' => json_encode($data),
        ),
    );

    $context = stream_context_create($options);
    $result = file_get_contents($api_url, false, $context);

    if ($result === false) {
        error_log('Error al crear el repositorio en GitHub');
    } else {
        echo 'Repositorio creado en GitHub';
    }
}

// Hook para activar la función cuando se activa el plugin (puedes cambiar esto según tus necesidades)
register_activation_hook(__FILE__, 'subir_a_github');

?>
