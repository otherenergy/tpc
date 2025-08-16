import mysql.connector
import os
import errno
import shutil
import requests
from ftplib import FTP
import base64
import time

mydb = mysql.connector.connect(host="gnpo-server.mysql.database.azure.com", user="gnpoRoot", password="6P8upHCSiHoHNxL", database="smartcret_dev")
# mydb = mysql.connector.connect(host="localhost", user="root", password="", database="smartcret_dev")
file_path='C:/xampp/htdocs/'
base_url='smartcret_new/'

carpeta_a_eliminar = "C:/xampp/htdocs/smartcret_new"
try:
    shutil.rmtree(carpeta_a_eliminar)
    print(f"La carpeta {carpeta_a_eliminar} ha sido eliminada exitosamente.")
except Exception as e:
    print(f"Error al eliminar la carpeta: {e}")

shutil.copytree("plantilla_smartcret/admin", file_path+base_url+'/'+'admin')
shutil.copytree("plantilla_smartcret/assets", file_path+base_url+'/'+'assets')
shutil.copytree("plantilla_smartcret/config", file_path+base_url+'/'+'config')
shutil.copytree("plantilla_smartcret/checkout", file_path+base_url+'/'+'checkout')
shutil.copytree("plantilla_smartcret/calculadora-presupuestos", file_path+base_url+'/'+'calculadora-presupuestos')
# shutil.copytree("plantilla_smartcret/carrito", file_path+base_url+'/'+'carrito')
shutil.copytree("plantilla_smartcret/class", file_path+base_url+'/'+'class')
shutil.copytree("plantilla_smartcret/cookies", file_path+base_url+'/'+'cookies')
# shutil.copytree("plantilla_smartcret/fichas-tecnicas", file_path+base_url+'/'+'fichas-tecnicas')
shutil.copytree("plantilla_smartcret/includes", file_path+base_url+'/'+'includes')
shutil.copytree("plantilla_smartcret/lista_productos", file_path+base_url+'/'+'lista_productos')
# shutil.copytree("plantilla_smartcret/mailings", file_path+base_url+'/'+'mailings')
# shutil.copytree("plantilla_smartcret/mailings_test", file_path+base_url+'/'+'mailings_test')
# shutil.copytree("plantilla_smartcret/panel", file_path+base_url+'/'+'panel')
# os.makedirs(file_path+base_url+'/'+'schemas', exist_ok=True)

# Lista para almacenar los directorios creados
created_directories = []

def crear_carpeta(idioma):
    global created_directories
    try:
        os.makedirs(file_path+base_url+idioma, exist_ok=True)
        os.makedirs(file_path+base_url+idioma+'/'+'blog', exist_ok=True)
        os.makedirs(file_path+base_url+idioma+'/'+'panel', exist_ok=True)
        os.makedirs(file_path+base_url+idioma+'/'+'schemas', exist_ok=True)
        # shutil.copytree("plantilla_smartcret/mailings/vendor", file_path+base_url+idioma+'/'+'mailings/vendor')

        with open("C:/xampp/htdocs/plantilla_smartcret/.htaccess", 'r', encoding = 'utf-8') as f:
            contenido_htaccess = f.read()

        with open("C:/xampp/htdocs/smartcret_new/.htaccess", 'wt', encoding = 'utf-8') as f:
            f.write(contenido_htaccess)


        with open("C:/xampp/htdocs/plantilla_smartcret/.env", 'r', encoding = 'utf-8') as f:
            contenido_htaccess = f.read()

        with open("C:/xampp/htdocs/smartcret_new/.env", 'wt', encoding = 'utf-8') as f:
            f.write(contenido_htaccess)

        created_directories.append(idioma)

    except OSError as e:
        if e.errno != errno.EEXIST:
            raise
    print('Carpeta '+idioma+' creada correctamente')


def copiar_plantilla(url,id_tipo):
    try:
        if id_tipo == 5:
            # print('Producto '+url+' detectado. Preparando para copiar en: '+file_path+base_url+idioma+'/'+url+'.php')
            shutil.copy("plantilla_smartcret/plantilla_producto.php", file_path+base_url+idioma+'/'+url+'.php')
            # print('Plantilla de producto copiada correctamente en '+file_path+base_url+idioma+'/'+url+'.php')
        elif id_tipo == 3:
            shutil.copy("plantilla_smartcret/plantilla_tienda.php", file_path+base_url+idioma+'/'+url+'.php')

        elif id_tipo == 16:
            shutil.copy("plantilla_smartcret/plantilla_tienda_kits.php", file_path+base_url+idioma+'/'+url+'.php')

        elif id_tipo == 17:
            shutil.copy("plantilla_smartcret/plantilla_pintura_azulejos.php", file_path+base_url+idioma+'/'+url+'.php')

        elif id_tipo == 2:
            shutil.copy("plantilla_smartcret/plantilla_core.php", file_path+base_url+idioma+'/'+url+'.php')
        #else:
            #url = 'index'
            #shutil.copy("plantilla_smartcret/plantilla_home.php", file_path+base_url+idioma+'/'+url+'.php')

        elif id_tipo == 4:
            shutil.copy("plantilla_smartcret/plantilla_blog.php", file_path+base_url+idioma+'/blog/index.php')

        elif id_tipo == 6:
            shutil.copy("plantilla_smartcret/plantilla_post.php", file_path+base_url+idioma+'/blog/'+url+'.php')

        if id_tipo == 7:
            # print('Producto '+url+' detectado. Preparando para copiar en: '+file_path+base_url+idioma+'/'+url+'.php')
            shutil.copy("plantilla_smartcret/plantilla_contenido_general.php", file_path+base_url+idioma+'/'+url+'.php')
            # print('Plantilla de producto copiada correctamente en '+file_path+base_url+idioma+'/'+url+'.php')

        if id_tipo == 1:
            # print('Producto '+url+' detectado. Preparando para copiar en: '+file_path+base_url+idioma+'/'+url+'.php')
            shutil.copy("plantilla_smartcret/plantilla_home.php", file_path+base_url+idioma+'/index.php')
            # print('Plantilla de producto copiada correctamente en '+file_path+base_url+idioma+'/'+url+'.php')

        # if id_tipo == 8:
        #     # print('Producto '+url+' detectado. Preparando para copiar en: 'file_path+base_url+idioma+'/calculadora/index.php')
        #     shutil.copytree("plantilla_smartcret/calculadora-presupuestos", file_path+base_url+idioma+'/calculadora_presupuestos')
        #     # print('Plantilla de producto copiada correctamente en 'file_path+base_url+idioma+'/calculadora/index.php')

        if id_tipo == 9:
            # print('Producto '+url+' detectado. Preparando para copiar en: '+file_path+base_url+idioma+'/'+url+'.php')
            shutil.copy("plantilla_smartcret/plantilla_login_signup_pass.php", file_path+base_url+idioma+'/'+url+'.php')
            # shutil.copy("plantilla_smartcret/plantilla_signup.php", file_path+base_url+idioma+'/'+url+'.php')
            # print('Plantilla de producto copiada correctamente en '+file_path+base_url+idioma+'/'+url+'.php')

        if id_tipo == 10:
            # print('Producto '+url+' detectado. Preparando para copiar en: '+file_path+base_url+idioma+'/'+url+'.php')
            shutil.copy("plantilla_smartcret/plantilla_404.php", file_path+base_url+idioma+'/'+url+'.php')
            # print('Plantilla de producto copiada correctamente en '+file_path+base_url+idioma+'/'+url+'.php')

        # if id_tipo == 11:
            # print('Pagina '+url+' detectado. Preparando para copiar en: '+file_path+base_url+idioma+'/mailings/'+url+'.php')
            # shutil.copy("plantilla_smartcret/plantilla_email.php", file_path+base_url+idioma+'/mailings/'+url+'.php')
            # print('Plantilla de producto copiada correctamente en '+file_path+base_url+idioma+'/'+url+'.php')

        elif id_tipo == 13:
            shutil.copy("plantilla_smartcret/plantilla_panel_datos.php", file_path+base_url+idioma+'/panel/'+url+'.php')
            shutil.copytree("plantilla_smartcret/panel/includes", file_path+base_url+idioma+'/panel/includes')
        elif id_tipo == 14:
            shutil.copy("plantilla_smartcret/plantilla_panel_pedidos.php", file_path+base_url+idioma+'/panel/'+url+'.php')
        elif id_tipo == 15:
            shutil.copy("plantilla_smartcret/plantilla_microcemento_listo.php", file_path+base_url+idioma+'/'+url+'.php')

        elif id_tipo == 18:
            shutil.copy("plantilla_smartcret/plantilla_hormigon.php", file_path+base_url+idioma+'/'+url+'.php')

        elif id_tipo == 19:
            shutil.copy("plantilla_smartcret/plantilla_ofertas.php", file_path+base_url+idioma+'/'+url+'.php')


    except:
        print('Error copiando plantilla')


def modificar_archivo(id_tipo, url, id_url, idioma, id_idioma, id_producto):
    print('url:',url, 'id_url:',id_url, 'idioma:',idioma, 'id_idioma:',id_idioma, 'id_producto:',id_producto)
    try:
        if url != '/':
            if id_tipo == 6:
                path_archivo=file_path+base_url+idioma+'/blog/'+url+'.php'
                print(id_url,path_archivo)
                nuevotexto='<?php \n$id_tipo="' + str(id_tipo) + '";\n$id_url="' + str(id_url) + '";' + '\n' + '$idioma_url="'+ idioma +'";\n' + '$url="' + url + '";\n' + '$id_idioma="' + str(id_idioma) +  '";\n?>'
                try:
                    with open(path_archivo, 'r', encoding = 'utf-8') as f:
                        contenido = f.read()
                except Exception as error:
                    print("An exception occurred:", error)
                with open(path_archivo, 'w', encoding = 'utf-8') as f:
                    f.seek(0, 0)
                    f.write(nuevotexto + '\n' + contenido)
            elif id_tipo == 4:
                path_archivo=file_path+base_url+idioma+'/blog/index.php'
                print(id_url,path_archivo)
                nuevotexto='<?php \n$id_tipo="' + str(id_tipo) + '";\n$id_url="' + str(id_url) + '";' + '\n' + '$idioma_url="'+ idioma +'";\n' + '$url="' + url + '";\n' + '$id_idioma="' + str(id_idioma) +  '";\n?>'
                try:
                    with open(path_archivo, 'r', encoding = 'utf-8') as f:
                        contenido = f.read()
                except Exception as error:
                    print("An exception occurred:", error)
                with open(path_archivo, 'w', encoding = 'utf-8') as f:
                    f.seek(0, 0)
                    f.write(nuevotexto + '\n' + contenido)
            elif id_tipo == 11:
                path_archivo=file_path+base_url+idioma+'/mailings/'+url+'.php'
                print(id_url,path_archivo)
                nuevotexto='<?php \n$id_tipo="' + str(id_tipo) + '";\n$id_url="' + str(id_url) + '";' + '\n' + '$idioma_url="'+ idioma +'";\n' + '$url="' + url + '";\n' + '$id_idioma="' + str(id_idioma) +  '";\n?>'
                try:
                    with open(path_archivo, 'r', encoding = 'utf-8') as f:
                        contenido = f.read()
                except Exception as error:
                    print("An exception occurred:", error)
                with open(path_archivo, 'w', encoding = 'utf-8') as f:
                    f.seek(0, 0)
                    f.write(nuevotexto + '\n' + contenido)
            elif id_tipo == 13 or id_tipo == 14:
                path_archivo=file_path+base_url+idioma+'/panel/'+url+'.php'
                print(id_url,path_archivo)
                nuevotexto='<?php \n$id_tipo="' + str(id_tipo) + '";\n$id_url="' + str(id_url) + '";' + '\n' + '$idioma_url="'+ idioma +'";\n' + '$url="' + url + '";\n' + '$id_idioma="' + str(id_idioma) +  '";\n?>'
                try:
                    with open(path_archivo, 'r', encoding = 'utf-8') as f:
                        contenido = f.read()
                except Exception as error:
                    print("An exception occurred:", error)
                with open(path_archivo, 'w', encoding = 'utf-8') as f:
                    f.seek(0, 0)
                    f.write(nuevotexto + '\n' + contenido)
            else:
                path_archivo=file_path+base_url+idioma+'/'+url+'.php'
                print(id_url,path_archivo)

                nuevotexto = '<?php \n$id_tipo="' + str(id_tipo) + '";\n$id_url="' + str(id_url) + '";\n' + '$idioma_url="' + idioma + '";\n' + '$url="' + url + '";\n' + '$id_idioma="' + str(id_idioma) + '";\n' + (('$id_producto="' + str(id_producto) + '";\n') if id_producto is not None else '') + '?>'
                try:
                    with open(path_archivo, 'r', encoding = 'utf-8') as f:
                        contenido = f.read()
                except Exception as error:
                    print("An exception occurred:", error)
                with open(path_archivo, 'w', encoding = 'utf-8') as f:
                    f.seek(0, 0)
                    f.write(nuevotexto + '\n' + contenido)
        else:
            url = 'index'
            path_archivo=file_path+base_url+idioma+'/'+url+'.php'
            print(id_url,path_archivo)
            nuevotexto='<?php \n$id_tipo="' + str(id_tipo) + '";\n$id_url="' + str(id_url) + '";' + '\n' + '$idioma_url="'+ idioma +'";\n' + '$url="' + url + '";\n' + '$id_idioma="' + str(id_idioma) +  '";\n?>'
            with open(path_archivo, 'r', encoding = 'utf-8') as f:
                contenido = f.read()
            with open(path_archivo, 'w', encoding = 'utf-8') as f:
                f.seek(0, 0)
                f.write(nuevotexto + '\n' + contenido)
    except:
        print('Error modificando archivo')


idioma_cursor = mydb.cursor()
idioma_cursor.execute("SELECT * FROM idiomas")
idiomas = idioma_cursor.fetchall()

for x in idiomas:
  id_idioma=x[0]
  idioma=x[2]
  pais=x[3]
  if pais==None:
    idioma=idioma
  else:
    idioma=idioma+'-'+pais

  crear_carpeta(idioma)
  urls_cursor = mydb.cursor()

  consulta_urls_cursor="SELECT urls.id_url, urls.id_tipo, urls.id_idioma, urls.valor, productos.id AS id_producto FROM urls LEFT JOIN productos ON urls.id_url = productos.id_url AND productos.es_variante = 0 WHERE urls.id_idioma = %s;"
  id_idioma=(id_idioma,)
  urls_cursor.execute(consulta_urls_cursor,id_idioma)
  urls=urls_cursor.fetchall()
  for y in urls:
    id_url=y[0]

    # if y[0] == 123:
    #     print("EEEEEEE")

    id_tipo=y[1]
    id_idioma=y[2]
    url=y[3]
    id_producto=y[4]
    copiar_plantilla(url, id_tipo)
    modificar_archivo(id_tipo, url, id_url, idioma, id_idioma, id_producto)

# Genera los schemas una vez esten todos los archivos creados
# print(created_directories)
# generar_schemas(created_directories)
