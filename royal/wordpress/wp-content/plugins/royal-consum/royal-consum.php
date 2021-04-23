<?php
/*
Plugin Name: CONSUM
Description: Panel de Control acciones masivas CONSUM
Version: 1.0
Author: Royal Comunicación

*/

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu()
{
    //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    add_menu_page(
        'ACCIONES CONSUM',
        'ACCIONES CONSUM',
        'edit_posts',
        'menu-consum',
        'my_plugin_options'
    );
}

function my_plugin_options()
{
    if (!current_user_can('edit_posts')) {
        wp_die(__('No tiene suficientes permisos para acceder a esta página.'));
    } ?>

<div class="wrap">



<h3>EXPORTACIÓN CSV</h3>

<form action="#" id="exportar_tiendas" method="post">
	<input type="hidden" id="exportar-tiendas" name="exportar-tiendas" value="1" />
	<p><input type="submit" name="crear-csv" id="crear-csv" value="Crear CSV"></p>
 </form>

<?php
validar_fecha($id_tienda);
global $wpdb;

if ($_POST['exportar-tiendas'] && $_POST['exportar-tiendas'] == 1) {
    exportar_tiendas();
}
?>

 <hr>

<H3>EDITAR TIENDA</H3>
 <form action='' method='post' enctype="multipart/form-data">
   Introduce el ID : <input type='text' name='id-tienda' size='20'>
   <input type='submit' name='editar' value='Editar'>
  </form>

  <?php if (isset($_POST['id-tienda'])) {
      $id_tienda = $_POST['id-tienda'];
      $consulta = "SELECT post_title FROM wp_sabai_content_post WHERE post_id = $id_tienda";
      $resultado = $wpdb->get_results($consulta);
      if ($resultado) {
          $tie = $resultado[0];
          echo "<p><a href='https://www.consum.es/wp-admin/admin.php?page=sabai%2Fdirectory&q=%2Fdirectory%2F" .
              $_POST['id-tienda'] .
              "' target='_blank' >Abrir tienda " .
              $tie->post_title .
              '</a></p>';
      } else {
          echo '<p>Ese identificador no se corresponde con ninguna tienda.</p>';
      }
  } ?>

<hr>

  <H3>CAMBIAR ID DE TIENDA</H3>
 <form action='' method='post' enctype="multipart/form-data">
   Introduce el ID antiguo: <input type='text' name='id-tienda-ant' size='20'><br />
   Introduce el ID nuevo: <input type='text' name='id-tienda-new' size='20'>
   <input type='submit' name='cambiar' value='Cambiar'>
  </form>

  <?php if (isset($_POST['id-tienda-ant'])) {
      $id_tienda_ant = $_POST['id-tienda-ant'];
      $id_tienda_new = $_POST['id-tienda-new'];
      $consulta = "SELECT post_title FROM wp_sabai_content_post WHERE post_id = $id_tienda_ant";
      $resultado = $wpdb->get_results($consulta);
      if ($resultado) {
          $tiend = $resultado[0];
          echo '<p>El Id de la tienda ' .
              $tiend->post_title .
              ' ahora es el ' .
              $id_tienda_new .
              '</p>';

          //Actualizamos el id de la tienda

          $wpdb->update(
              'wp_sabai_content_post', // Nombre de la tabla
              ['post_id' => $id_tienda_new], // Valor a actualizar
              ['post_id' => $id_tienda_ant] // Condición
          );

          // Vamos a actualizar el id en todas las tablas que hacen referencia a esa tienda.

          $wpdb->update(
              'wp_sabai_entity_field_content_activity',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_taxonomy_content_count',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_content_children_count',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_directory_category',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_directory_claim',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_directory_location',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_compradom',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_cpostal',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_direccion',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_ensenya',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_estado',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_gasolinera',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_horario',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_horariov',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_horarioen',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_minusvalidos',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_parking',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_poblacion',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_provincia',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_superficie',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_telefono',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo1',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo2',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo3',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo4',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo5',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo6',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo1v',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo2v',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo3v',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo4v',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo5v',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo6v',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );

          $wpdb->update(
              'wp_sabai_entity_field_field_notas',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_posicioncoord',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_estado',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_id_poblacion',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_id_provincia',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_id_ensena',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_codigo',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_codigo',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_meta_description',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_meta_title',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );

          $wpdb->update(
              'wp_sabai_entity_field_field_ip_tienda',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_notificacion',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );

          /* ACTUALIZACIÓN 18/04/2017 A IDIOMA INGLÉS */

          $wpdb->update(
              'wp_sabai_entity_field_field_festivo1en',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo2en',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo3en',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo4en',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo5en',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_festivo6en',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );

          /*   FIN ACTUALIZACIÓN   */

          $wpdb->update(
              'wp_sabai_entity_field_field_fecha_festivo1',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_fecha_festivo2',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_fecha_festivo3',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_fecha_festivo4',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_fecha_festivo5',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );
          $wpdb->update(
              'wp_sabai_entity_field_field_fecha_festivo6',
              ['entity_id' => $id_tienda_new],
              ['entity_id' => $id_tienda_ant]
          );

          echo "<p>Una vez hayas actualizado el id de la tienda, debes <a target= '_blank' href='" .
              site_url() .
              "/wp-admin/options-general.php?page=sabai/settings'>LIMPIAR LA CACHÉ DE TIENDAS</a> (Clear Caché)</p>";
      } else {
          echo '<p>Ese identificador no se corresponde con ninguna tienda.</p>';
      }
  } ?>



  <hr>





	<H3>IMPORTAR IPs</H3>
 	<form action='' method='post' enctype="multipart/form-data">
   		Importar Archivo : <input type='file' name='archivoips' size='20'>
   		<input type="hidden" id="importar-ips" name="importar-ips" value="1" />
   		<input type='submit' name='Importar' value='Importar'>
  	</form>
  	<p><small>NOTA: Al importar el fichero los datos se sobreescribrán, deben importarse todas las tiendas. (<a href="/csv/ips.csv" download>Descargar ejemplo</a>)</small></p>


 <?php if (isset($_POST['importar-ips'])) {
     echo '<p><b>IMPORTANDO IPS...</b></p>';

     if (is_uploaded_file($_FILES['archivoips']['tmp_name'])) {
         $ruta = get_home_path() . 'csv/' . $_FILES['archivoips']['name'];
         move_uploaded_file($_FILES['archivoips']['tmp_name'], $ruta);
         echo '<p><b>El archivo ' .
             $_FILES['archivoips']['name'] .
             ' se ha subido correctamente</b></p>';
     } else {
         echo '<p>No se ha podido subir el fichero</p>';
     }

     echo '<p><b>Borrando datos antiguos...</b></p>';

     $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_ip_tienda';
     $wpdb->query($deletequery);
     $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_notificacion';
     $wpdb->query($deletequery);

     echo '<p><b>Escribiendo datos nuevos...<b></p>';

     $file_handle = fopen(
         get_home_url() . '/csv/' . $_FILES['archivoips']['name'],
         'r'
     );
     if (fopen(get_home_url() . '/csv/' . $_FILES['archivo']['name'], 'r')) {
         echo '<p><b>fopen() OK. Continuando con la importación...</b></p>';
     } else {
         echo '<p><b>FOPEN() DESACTIVADO</b></p>';
     }

     //SALTAR PRIMERA LINEA
     $line_of_text = fgetcsv($file_handle, 1024, ';');

     while (!feof($file_handle)) {
         $line_of_text = fgetcsv($file_handle, 1024, ';');

         $idtienda = utf8_encode($line_of_text[0]);
         $ip_tienda = utf8_encode($line_of_text[1]);
         $notificacion = utf8_encode($line_of_text[2]);

         insertar_dato($idtienda, 'ip_tienda', $ip_tienda);
         insertar_dato($idtienda, 'notificacion', $notificacion);
     }

     echo '<p><b>IMPORTACION FINALIZADA CON EXITO</b></p>';
 } ?>






<hr>



<H3>IMPORTAR FESTIVOS</H3>
 <form action='' method='post' enctype="multipart/form-data">
   Importar Archivo : <input type='file' name='archivo' size='20'>
   <input type="hidden" id="importar-horarios" name="importar-horarios" value="1" />
   <input type='submit' name='Importar' value='Importar'>
  </form>

  <?php if (isset($_POST['importar-horarios'])) {
      if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
          $ruta = get_home_path() . 'csv/' . $_FILES['archivo']['name'];

          move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
          echo '<p>El archivo ' .
              $_FILES['archivo']['name'] .
              ' se ha subido correctamente</p>';
      } else {
          echo '<p>No se ha podido subir el fichero</p>';
      }

      //VAMOS A LEER EL ARCHIVO

      echo '<p>Borrando datos antiguos...</p>';

      //Borramos todos los valores anteriores

      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo1';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo2';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo3';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo4';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo5';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo6';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo1v';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo2v';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo3v';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo4v';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo5v';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo6v';
      $wpdb->query($deletequery);

      /* ACTUALIZACIÓN 18/04/2017 A IDIOMA INGLÉS */

      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo1en';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo2en';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo3en';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo4en';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo5en';
      $wpdb->query($deletequery);
      $deletequery = 'TRUNCATE TABLE wp_sabai_entity_field_field_festivo6en';
      $wpdb->query($deletequery);

      /* FIN ACTUALIZACIÓN */

      $deletequery =
          'TRUNCATE TABLE wp_sabai_entity_field_field_fecha_festivo1';
      $wpdb->query($deletequery);
      $deletequery =
          'TRUNCATE TABLE wp_sabai_entity_field_field_fecha_festivo2';
      $wpdb->query($deletequery);
      $deletequery =
          'TRUNCATE TABLE wp_sabai_entity_field_field_fecha_festivo3';
      $wpdb->query($deletequery);
      $deletequery =
          'TRUNCATE TABLE wp_sabai_entity_field_field_fecha_festivo4';
      $wpdb->query($deletequery);
      $deletequery =
          'TRUNCATE TABLE wp_sabai_entity_field_field_fecha_festivo5';
      $wpdb->query($deletequery);
      $deletequery =
          'TRUNCATE TABLE wp_sabai_entity_field_field_fecha_festivo6';
      $wpdb->query($deletequery);

      echo '<br />';
      echo '<p>Escribiendo datos nuevos...</p>';

      $file_handle = fopen(
          get_home_url() . '/csv/' . $_FILES['archivo']['name'],
          'r'
      );
      if (fopen(get_home_url() . '/csv/' . $_FILES['archivo']['name'], 'r')) {
          echo 'fopen() OK. Continuando con la importación...<br />';
      } else {
          echo 'FOPEN() DESACTIVADO<br />';
          exit();
      }

      while (!feof($file_handle)) {
          //Segundo while introduce los datos

          $line_of_text = fgetcsv($file_handle, 1024, ';');

          $texto1 = mostrar_dato($line_of_text[0], 'festivo1');
          $texto2 = mostrar_dato($line_of_text[0], 'festivo2');
          $texto3 = mostrar_dato($line_of_text[0], 'festivo3');
          $texto4 = mostrar_dato($line_of_text[0], 'festivo4');
          $texto5 = mostrar_dato($line_of_text[0], 'festivo5');
          $texto6 = mostrar_dato($line_of_text[0], 'festivo6');
          $texto_final = utf8_encode($line_of_text[3]);
          $texto_finalv = utf8_encode($line_of_text[4]);
          $texto_finalen = utf8_encode($line_of_text[5]); // ACTUALIZACIÓN A INGLÉS

          if ($texto1 == '') {
              actualizar_dato($line_of_text[0], 'festivo1', $texto_final);
              actualizar_dato($line_of_text[0], 'festivo1v', $texto_finalv);
              actualizar_dato($line_of_text[0], 'festivo1en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo1');

              if (!$texto_actualizado) {
                  insertar_dato($line_of_text[0], 'festivo1', $texto_final);
                  insertar_dato($line_of_text[0], 'festivo1v', $texto_finalv);
                  insertar_dato($line_of_text[0], 'festivo1en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              }

              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo1');
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';

              actualizar_dato(
                  $line_of_text[0],
                  'fecha_festivo1',
                  $line_of_text[2]
              );
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo1'
              );

              if (!$texto_actualizado) {
                  insertar_dato(
                      $line_of_text[0],
                      'fecha_festivo1',
                      $line_of_text[2]
                  );
              }
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo1'
              );
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';
          } elseif ($texto2 == '') {
              actualizar_dato($line_of_text[0], 'festivo2', $texto_final);
              actualizar_dato($line_of_text[0], 'festivo2v', $texto_finalv);
              actualizar_dato($line_of_text[0], 'festivo2en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo2');
              if (!$texto_actualizado) {
                  insertar_dato($line_of_text[0], 'festivo2', $texto_final);
                  insertar_dato($line_of_text[0], 'festivo2v', $texto_finalv);
                  insertar_dato($line_of_text[0], 'festivo2en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              }
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo2');
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';

              actualizar_dato(
                  $line_of_text[0],
                  'fecha_festivo2',
                  $line_of_text[2]
              );
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo2'
              );
              if (!$texto_actualizado) {
                  insertar_dato(
                      $line_of_text[0],
                      'fecha_festivo2',
                      $line_of_text[2]
                  );
              }
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo2'
              );
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';
          } elseif ($texto3 == '') {
              actualizar_dato($line_of_text[0], 'festivo3', $texto_final);
              actualizar_dato($line_of_text[0], 'festivo3v', $texto_finalv);
              actualizar_dato($line_of_text[0], 'festivo3en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo3');
              if (!$texto_actualizado) {
                  insertar_dato($line_of_text[0], 'festivo3', $texto_final);
                  insertar_dato($line_of_text[0], 'festivo3v', $texto_finalv);
                  insertar_dato($line_of_text[0], 'festivo3en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              }
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo3');
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';

              actualizar_dato(
                  $line_of_text[0],
                  'fecha_festivo3',
                  $line_of_text[2]
              );
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo3'
              );
              if (!$texto_actualizado) {
                  insertar_dato(
                      $line_of_text[0],
                      'fecha_festivo3',
                      $line_of_text[2]
                  );
              }
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo3'
              );
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';
          } elseif ($texto4 == '') {
              actualizar_dato($line_of_text[0], 'festivo4', $texto_final);
              actualizar_dato($line_of_text[0], 'festivo4v', $texto_finalv);
              actualizar_dato($line_of_text[0], 'festivo4en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo4');
              if (!$texto_actualizado) {
                  insertar_dato($line_of_text[0], 'festivo4', $texto_final);
                  insertar_dato($line_of_text[0], 'festivo4v', $texto_finalv);
                  insertar_dato($line_of_text[0], 'festivo4en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              }
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo4');
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';

              actualizar_dato(
                  $line_of_text[0],
                  'fecha_festivo4',
                  $line_of_text[2]
              );
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo4'
              );
              if (!$texto_actualizado) {
                  insertar_dato(
                      $line_of_text[0],
                      'fecha_festivo4',
                      $line_of_text[2]
                  );
              }
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo4'
              );
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';
          } elseif ($texto5 == '') {
              actualizar_dato($line_of_text[0], 'festivo5', $texto_final);
              actualizar_dato($line_of_text[0], 'festivo5v', $texto_finalv);
              actualizar_dato($line_of_text[0], 'festivo5en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo5');
              if (!$texto_actualizado) {
                  insertar_dato($line_of_text[0], 'festivo5', $texto_final);
                  insertar_dato($line_of_text[0], 'festivo5v', $texto_finalv);
                  insertar_dato($line_of_text[0], 'festivo5en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              }
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo5');
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';

              actualizar_dato(
                  $line_of_text[0],
                  'fecha_festivo5',
                  $line_of_text[2]
              );
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo5'
              );
              if (!$texto_actualizado) {
                  insertar_dato(
                      $line_of_text[0],
                      'fecha_festivo5',
                      $line_of_text[2]
                  );
              }
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo5'
              );
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';
          } elseif ($texto6 == '') {
              actualizar_dato($line_of_text[0], 'festivo6', $texto_final);
              actualizar_dato($line_of_text[0], 'festivo6v', $texto_finalv);
              actualizar_dato($line_of_text[0], 'festivo6en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo6');
              if (!$texto_actualizado) {
                  insertar_dato($line_of_text[0], 'festivo6', $texto_final);
                  insertar_dato($line_of_text[0], 'festivo6v', $texto_finalv);
                  insertar_dato($line_of_text[0], 'festivo6en', $texto_finalen); // ACTUALIZACIÓN A INGLÉS
              }
              $texto_actualizado = mostrar_dato($line_of_text[0], 'festivo6');
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';

              actualizar_dato(
                  $line_of_text[0],
                  'fecha_festivo6',
                  $line_of_text[2]
              );
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo6'
              );
              if (!$texto_actualizado) {
                  insertar_dato(
                      $line_of_text[0],
                      'fecha_festivo6',
                      $line_of_text[2]
                  );
              }
              $texto_actualizado = mostrar_dato(
                  $line_of_text[0],
                  'fecha_festivo6'
              );
              echo '<p>Cadena actualizada para ID(' .
                  $line_of_text[0] .
                  '): ' .
                  $texto_actualizado .
                  '</p>';
          }
      }

      fclose($file_handle);

      echo '</div>';
  }

  ?>

  <hr>

  <h3>IMPORTAR DATOS LOCALOO</h3>
  <form action='' method='post' enctype="multipart/form-data">
	Importar Archivo : <input type='file' name='archivo' size='20'>
	<input type="hidden" id="importar-datos-localoo" name="importar-datos-localoo" value="1" />
	<input type='submit' name='Importar' value='Importar'>
   </form>
 
   <?php if (isset($_POST['importar-datos-localoo'])) {
	   	if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
			$ruta = get_home_path() . 'csv/' . $_FILES['archivo']['name'];
			move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
			echo '<p>El archivo ' .$_FILES['archivo']['name'] .' se ha subido correctamente</p>';
		} else {
			echo '<p>No se ha podido subir el fichero</p>';
		}

		//VAMOS A LEER EL ARCHIVO

		echo '<p>Borrando datos antiguos...</p>';

        $deletequery = [];
        
		$deletequery[0] = 'TRUNCATE TABLE wp_sabai_entity_field_field_nombre_empresa';
		$deletequery[1] = 'TRUNCATE TABLE wp_sabai_entity_field_field_direccion_line_1';
		$deletequery[2] = 'TRUNCATE TABLE wp_sabai_entity_field_field_direccion_line_2';
		$deletequery[3] = 'TRUNCATE TABLE wp_sabai_entity_field_field_direccion_line_3';
		$deletequery[4] = 'TRUNCATE TABLE wp_sabai_entity_field_field_direccion_line_4';
		$deletequery[5] = 'TRUNCATE TABLE wp_sabai_entity_field_field_direccion_line_5';
		$deletequery[6] = 'TRUNCATE TABLE wp_sabai_entity_field_field_distrito';
		$deletequery[7] = 'TRUNCATE TABLE wp_sabai_entity_field_field_pais_region';
		$deletequery[8] = 'TRUNCATE TABLE wp_sabai_entity_field_field_other_phones';
		$deletequery[9] = 'TRUNCATE TABLE wp_sabai_entity_field_field_sitio_web';
		$deletequery[10] = 'TRUNCATE TABLE wp_sabai_entity_field_field_categoria_principal';
		$deletequery[11] = 'TRUNCATE TABLE wp_sabai_entity_field_field_categorias_adicionales';
        $deletequery[12] = 'TRUNCATE TABLE wp_sabai_entity_field_field_horario_domingo';
		$deletequery[13] = 'TRUNCATE TABLE wp_sabai_entity_field_field_horario_lunes';
		$deletequery[14] = 'TRUNCATE TABLE wp_sabai_entity_field_field_horario_martes';
		$deletequery[15] = 'TRUNCATE TABLE wp_sabai_entity_field_field_horario_miercoles';
		$deletequery[16] = 'TRUNCATE TABLE wp_sabai_entity_field_field_horario_jueves';
		$deletequery[17] = 'TRUNCATE TABLE wp_sabai_entity_field_field_field_horario_viernes';
		$deletequery[18] = 'TRUNCATE TABLE wp_sabai_entity_field_field_field_horario_sabado';
		$deletequery[19] = 'TRUNCATE TABLE wp_sabai_entity_field_field_field_horario_especial';
		$deletequery[20] = 'TRUNCATE TABLE wp_sabai_entity_field_field_field_descripcion';
		$deletequery[21] = 'TRUNCATE TABLE wp_sabai_entity_field_field_field_fecha_apertura';
		$deletequery[22] = 'TRUNCATE TABLE wp_sabai_entity_field_field_field_etiquetas';
		$deletequery[23] = 'TRUNCATE TABLE wp_sabai_entity_field_field_field_tlf_adwords';
		$deletequery[24] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_wheelchair_accessible_entrance';
		$deletequery[25] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_wheelchair_accessible_parking';
		$deletequery[26] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_wheelchair_accessible_elevator';
		$deletequery[27] = 'TRUNCATE TABLE wp_sabai_entity_field_field_is_owned_by_women';
		$deletequery[28] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_delivery';
		$deletequery[29] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_drive_through';
		$deletequery[30] = 'TRUNCATE TABLE wp_sabai_entity_field_field_serves_dine_in';
		$deletequery[31] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_in_store_shopping';
		$deletequery[32] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_delivery_same_day';
		$deletequery[33] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_no_contact_delivery';
		$deletequery[34] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_takeout';
		$deletequery[35] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_in_store_pickup';
		$deletequery[36] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_curbside_pickup';
		$deletequery[37] = 'TRUNCATE TABLE wp_sabai_entity_field_field_pay_check';
		$deletequery[38] = 'TRUNCATE TABLE wp_sabai_entity_field_field_pay_mobile_nfc';
		$deletequery[39] = 'TRUNCATE TABLE wp_sabai_entity_field_field_requires_cash_only';
		$deletequery[40] = 'TRUNCATE TABLE wp_sabai_entity_field_field_pay_credit_card_types_accepted';
		$deletequery[41] = 'TRUNCATE TABLE wp_sabai_entity_field_field_welcomes_lgbtq';
		$deletequery[42] = 'TRUNCATE TABLE wp_sabai_entity_field_field_sells_food_prepared';
		$deletequery[43] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_onsite_passport_photo';
		$deletequery[44] = 'TRUNCATE TABLE wp_sabai_entity_field_field_sells_organic_products';
		$deletequery[45] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_salad_bar';
		$deletequery[46] = 'TRUNCATE TABLE wp_sabai_entity_field_field_is_sanitizing_between_customers';
		$deletequery[47] = 'TRUNCATE TABLE wp_sabai_entity_field_field_requires_masks_staff';
		$deletequery[48] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_plexiglass_at_checkout';
		$deletequery[49] = 'TRUNCATE TABLE wp_sabai_entity_field_field_requires_masks_customers';
		$deletequery[50] = 'TRUNCATE TABLE wp_sabai_entity_field_field_requires_appointments';
		$deletequery[51] = 'TRUNCATE TABLE wp_sabai_entity_field_field_requires_temperature_check_staff';
		$deletequery[52] = 'TRUNCATE TABLE wp_sabai_entity_field_field_requires_temperature_check_customers';
		$deletequery[53] = 'TRUNCATE TABLE wp_sabai_entity_field_field_has_restroom_public';
		$deletequery[54] = 'TRUNCATE TABLE wp_sabai_entity_field_field_wi_fi';
		$deletequery[55] = 'TRUNCATE TABLE wp_sabai_entity_field_field_url_order_ahead';
		
        for ($i = 0; $i < count($deletequery); $i++){
            $wpdb->query($deletequery[$i]);
        }
      	
		echo '<br />';
		echo '<p>Escribiendo datos nuevos...</p>';

		$file_handle = fopen(get_home_path()."csv/".$_FILES['archivo']['name'],
			'r'
		);

		if (fopen(get_home_path().'csv/'.$_FILES['archivo']['name'], 'r')) {
			echo 'fopen() OK. Continuando con la importación...<br />';
		} else {
			echo 'FOPEN() DESACTIVADO<br />';
			exit();
		}

		while (!feof($file_handle)) {
            $line_of_text = fgetcsv($file_handle, 1024, ';');
            echo $line_of_text[1];
            /*if (!is_int($line_of_text[1])){ continue; }*/
            insertar_dato($line_of_text[1], 'nombre_empresa', $line_of_text[2]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[2].'</p>';
            insertar_dato($line_of_text[1], 'direccion_line_1', $line_of_text[3]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[3].'</p>';
            insertar_dato($line_of_text[1], 'direccion_line_2', $line_of_text[4]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[4].'</p>';
            insertar_dato($line_of_text[1], 'direccion_line_3', $line_of_text[5]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[5].'</p>';
            insertar_dato($line_of_text[1], 'direccion_line_4', $line_of_text[6]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[6].'</p>';
            insertar_dato($line_of_text[1], 'direccion_line_5', $line_of_text[7]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[7].'</p>';
            insertar_dato($line_of_text[1], 'distrito', $line_of_text[8]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[8].'</p>';
            insertar_dato($line_of_text[1], 'pais_region', $line_of_text[11]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[11].'</p>';
            insertar_dato($line_of_text[1], 'other_phones', $line_of_text[14]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[14].'</p>';
            insertar_dato($line_of_text[1], 'sitio_web', $line_of_text[15]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[15].'</p>';
            insertar_dato($line_of_text[1], 'categoria_principal', $line_of_text[16]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[16].'</p>';
            insertar_dato($line_of_text[1], 'categorias_adicionales', $line_of_text[17]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[17].'</p>';
            insertar_dato($line_of_text[1], 'horario_domingo', $line_of_text[18]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[18].'</p>';
            insertar_dato($line_of_text[1], 'horario_lunes', $line_of_text[19]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[19].'</p>';
            insertar_dato($line_of_text[1], 'horario_martes', $line_of_text[20]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[20].'</p>';
            insertar_dato($line_of_text[1], 'horario_miercoles', $line_of_text[21]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[21].'</p>';
            insertar_dato($line_of_text[1], 'horario_jueves', $line_of_text[22]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[22].'</p>';
            insertar_dato($line_of_text[1], 'horario_viernes', $line_of_text[23]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[23].'</p>';
            insertar_dato($line_of_text[1], 'horario_sabado', $line_of_text[24]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[24].'</p>';
            insertar_dato($line_of_text[1], 'horario_especial', $line_of_text[25]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[25].'</p>';
            insertar_dato($line_of_text[1], 'descripcion', $line_of_text[26]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[26].'</p>';
            insertar_dato($line_of_text[1], 'fecha_apertura', $line_of_text[27]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[27].'</p>';
            insertar_dato($line_of_text[1], 'etiquetas', $line_of_text[28]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[28].'</p>';
            insertar_dato($line_of_text[1], 'tlf_adwords', $line_of_text[29]);
            echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[26].'</p>';

            //Ver si es verdadero o falso
            if ($line_of_text[30] == "No" || empty($line_of_text[30])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_wheelchair_accessible_entrance', $band);
            if ($line_of_text[31] == "No" || empty($line_of_text[31])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_wheelchair_accessible_parking', $band);
            if ($line_of_text[32] == "No" || empty($line_of_text[32])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_wheelchair_accessible_elevator', $band);
            if ($line_of_text[33] == "No" || empty($line_of_text[33])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'is_owned_by_women', $band);
            if ($line_of_text[35] == "No" || empty($line_of_text[35])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'serves_dine_in', $band);
            if ($line_of_text[36] == "No" || empty($line_of_text[36])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_drive_through', $band);
            if ($line_of_text[35] == "No" || empty($line_of_text[35])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_delivery', $band);
            if ($line_of_text[37] == "No" || empty($line_of_text[37])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_in_store_shopping', $band);
            if ($line_of_text[38] == "No" || empty($line_of_text[38])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_delivery_same_day', $band);
            if ($line_of_text[39] == "No" || empty($line_of_text[39])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_no_contact_delivery', $band);
            if ($line_of_text[40] == "No" || empty($line_of_text[40])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_takeout', $band);
            if ($line_of_text[41] == "No" || empty($line_of_text[41])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_in_store_pickup', $band);
            if ($line_of_text[42] == "No" || empty($line_of_text[42])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_curbside_pickup', $band);
            if ($line_of_text[43] == "No" || empty($line_of_text[43])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'pay_check', $band);
            if ($line_of_text[44] == "No" || empty($line_of_text[44])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'pay_mobile_nfc', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'requires_cash_only', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'pay_credit_card_types_accepted', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'welcomes_lgbtq', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'sells_food_prepared', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_onsite_passport_photo', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'sells_organic_products', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_salad_bar', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'is_sanitizing_between_customers', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'requires_masks_staff', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_plexiglass_at_checkout', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'requires_masks_customers', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'requires_appointments', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'requires_temperature_check_staff', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'requires_temperature_check_customers', $band);
            if ($line_of_text[45] == "No" || empty($line_of_text[45])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'has_restroom_public', $band);
            if ($line_of_text[68] == "No" || empty($line_of_text[68])){ $band = false; }else{ $band = true; }
            insertar_dato($line_of_text[1], 'wi_fi', $band);

            //Fin

            insertar_dato($line_of_text[1], 'url_order_ahead', $line_of_text[69]);

			echo '<p>Cadena actualizada para ID('.$line_of_text[1].'): '.$line_of_text[19].'</p>';
            //lanzo
		}

		fclose($file_handle);
   }

}

//fin de la función
//funcion
?>

