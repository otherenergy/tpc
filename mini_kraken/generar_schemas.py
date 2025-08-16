import os
import json
import mysql.connector
from datetime import datetime, timedelta
from dotenv import load_dotenv
import re
import logging

# Configuración del registro
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname=s - %(message)s')
logger = logging.getLogger(__name__)

load_dotenv()

db_config = {
    'user': os.getenv("USER"),
    'password': os.getenv("PASSWORD"),
    'host': os.getenv("HOST"),
    'database': os.getenv("DATABASE"),
}

base_path = "smartcret_new/"

def get_db_connection():
    try:
        conn = mysql.connector.connect(**db_config)
        return conn
    except mysql.connector.Error as err:
        logger.error(f"Database connection error: {err}")
        return None

def obtener_datos(cursor, id_producto, id_idioma):
    query = '''
        SELECT nombre
        FROM productos_nombres
        WHERE id_producto = %s AND id_idioma = %s
    '''
    cursor.execute(query, (id_producto, id_idioma))
    result = cursor.fetchone()
    return result['nombre'] if result else None

def fetch_data(cursor, id_url, id_idioma, es_variante):
    variantes_con_acabado_y_juntas = {2, 812}
    query = '''
        SELECT 
            'producto' AS tipo,
            p.id,
            p.color,
            p.sku,
            p.ean AS gtin13,
            '' AS name,
            p.miniatura AS image,
            pp.precio AS price,
            m.moneda AS priceCurrency,
            pp.id_idioma,
            a.valor AS color_value,
            '' AS url_title,
            '' AS url_description,
            %s AS id_url
        FROM 
            productos p
        INNER JOIN 
            productos_precios pp ON p.id = pp.id_producto
        INNER JOIN 
            atributos a ON p.color = a.id
        INNER JOIN
            monedas m ON pp.id_idioma = m.id_idioma
        WHERE 
            p.es_variante = %s AND
            p.publicado = 1 AND
            pp.id_idioma = %s
    '''
    if es_variante in variantes_con_acabado_y_juntas:
        query += ' AND p.acabado = 1 AND p.juntas = 1'

    query += '''
        UNION ALL
        SELECT 
            'url' AS tipo,
            NULL AS id,
            NULL AS color,
            NULL AS sku,
            NULL AS gtin13,
            NULL AS name,
            NULL AS image,
            NULL AS price,
            NULL AS priceCurrency,
            NULL AS id_idioma,
            NULL AS color_value,
            url_metas.title AS url_title,
            url_metas.description AS url_description,
            urls.id_url AS id_url
        FROM 
            urls
        JOIN 
            url_metas ON url_metas.id_url = urls.id_url
        WHERE 
            urls.id_url = %s AND
            urls.id_idioma = %s AND
            url_metas.id_idioma = %s;
    '''
    cursor.execute(query, (id_url, es_variante, id_idioma, id_url, id_idioma, id_idioma))
    return cursor.fetchall()

def procesar_datos(data, provided_url_valor, product_name, idioma_url):
    product_url = provided_url_valor
    product_description = ""
    main_image = ""
    general_price = None
    general_price_currency = None

    product_variants = []

    for row in data:
        if row['tipo'] == 'url':
            product_description = row['url_description']
        elif row['tipo'] == 'producto':
            if row["priceCurrency"] == "€":
                row["priceCurrency"] = "EUR"
            if idioma_url == "en-us" and row["priceCurrency"] == "EUR":
                row["priceCurrency"] = "USD"
            if general_price is None and general_price_currency is None:
                general_price = row["price"]
                general_price_currency = row["priceCurrency"]
            product_variants.append(row)
            if main_image == "":
                main_image = row['image']
    
    return product_url, product_name, product_description, main_image, product_variants, general_price, general_price_currency

def generate_schema(product_data):
    schema = {
        "@context": "https://schema.org/",
        "@type": "ProductGroup",
        "url": product_data['url'],
        "name": product_data['name'],
        "description": product_data['description'],
        "image": product_data['image'],
        "brand": {
            "@type": "Brand",
            "name": "Smartcret"
        },
        "manufacturer": {
            "@type": "Organization",
            "name": "Smartcret",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Pol. Ind. Mas de Tous",
                "addressLocality": "Pobla de Vallbona",
                "postalCode": "46185",
                "addressRegion": "Valencia",
                "addressCountry": "España"
            },
            "image": "https://www.smartcret.com/assets/img/logo-smartcret.png",
            "logo": "https://www.smartcret.com/assets/img/logo-smartcret.png",
            "url": "https://www.smartcret.com",
            "legalName": "Grupo Negocios PO SLU",
            "telephone": "+34674409942",
            "taxID": "B97539076"
        },
        "category": "Home Improvement > Paint",
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "5",
            "reviewCount": "17"
        },
        "priceCurrency": product_data['priceCurrency'],
        "price": product_data['price'],
        "hasVariant": product_data['variants']
    }
    return schema

def guardar_schema(schema, directory, filename):
    json_ld = json.dumps(schema, ensure_ascii=False, indent=2)
    
    txt_content = f'<script type="application/ld+json">\n{json_ld}\n</script>'

    directory = os.path.join(directory, "schemas")
    if not os.path.exists(directory):
        os.makedirs(directory)
    
    with open(os.path.join(directory, filename), 'w', encoding='utf-8') as file:
        file.write(txt_content)

    print(f'Product schema generated successfully and saved to "{os.path.join(directory, filename)}".')

def sanitize_filename(name):
    return re.sub(r'[\\/*?:"<>|]', "_", name)

def extract_conditions_from_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        content = file.read()
    
    conditions = {}
    conditions['id_tipo'] = extraer_variables(content, r'\$id_tipo="(\d+)"')
    conditions['id_url'] = extraer_variables(content, r'\$id_url="(\d+)"')
    conditions['idioma_url'] = extraer_variables(content, r'\$idioma_url="([\w\-]+)"')
    conditions['url'] = extraer_variables(content, r'\$url="([\w\-]+)"')
    conditions['id_idioma'] = extraer_variables(content, r'\$id_idioma="(\d+)"')
    conditions['id_producto'] = extraer_variables(content, r'\$id_producto="(\d+)"')
    
    return conditions

def extraer_variables(content, pattern):
    match = re.search(pattern, content)
    return match.group(1) if match else None

def generar_schemas(directorios):
    conn = get_db_connection()
    if conn is None:
        logger.error("Unable to establish database connection.")
        return
    
    cursor = conn.cursor(dictionary=True)
    
    for root, dirs, files in os.walk(base_path):
        for dir_name in directorios:
            if dir_name in dirs:
                for file_name in os.listdir(os.path.join(root, dir_name)):
                    if file_name.endswith('.php'):
                        file_path = os.path.join(root, dir_name, file_name)
                        conditions = extract_conditions_from_file(file_path)
                        
                        # Verificar que todas las variables requeridas no sean None
                        if all(conditions.values()):
                            if conditions['id_tipo'] == "5":
                                id_url = int(conditions['id_url'])
                                idioma_url = conditions['idioma_url']
                                url = conditions['url']
                                id_idioma = int(conditions['id_idioma'])
                                id_producto = int(conditions['id_producto'])
                                es_variante = id_producto
                                
                                product_name = obtener_datos(cursor, id_producto, id_idioma)
                                if product_name is None:
                                    # logger.warning(f"Product name not found for id_producto={id_producto} and id_idioma={id_idioma}. Skipping file {file_path}.")
                                    continue

                                data = fetch_data(cursor, id_url, id_idioma, es_variante)
                                if not data:
                                    # logger.warning(f"No data found for id_url={id_url}, id_idioma={id_idioma}, es_variante={es_variante}. Skipping file {file_path}.")
                                    continue

                                product_url, product_name, product_description, main_image, product_variants, general_price, general_price_currency = procesar_datos(data, url, product_name, idioma_url)

                                product_data = {
                                    "url": product_url,
                                    "name": product_name,
                                    "description": product_description,
                                    "image": f"https://www.smartcret.com/assets/img/{main_image}",
                                    "priceCurrency": general_price_currency,
                                    "price": general_price,
                                    "variants": []
                                }

                                for variant in product_variants:
                                    variant_data = {
                                        "@type": "Product",
                                        "name": f"{product_name} - Color {variant['color_value']}",
                                        "color": variant["color_value"],
                                        "image": f"https://www.smartcret.com/assets/img/{variant['image']}",
                                        "sku": variant["sku"],
                                        "gtin13": variant["gtin13"],
                                        "offers": {
                                            "@type": "Offer",
                                            "priceCurrency": variant["priceCurrency"],
                                            "price": variant["price"],
                                            "availability": "https://schema.org/InStock",
                                            "priceValidUntil": (datetime.now() + timedelta(days=365)).strftime('%Y-%m-%d')
                                        }
                                    }
                                    product_data["variants"].append(variant_data)

                                schema = generate_schema(product_data)
                                if schema:
                                    safe_name = sanitize_filename(url)
                                    guardar_schema(schema, os.path.join(root, dir_name), f'{safe_name}.php')
                        # else:
                            # logger.warning(f"Conditions not met for file: {file_path}. Missing variables.")
            # else:
                # logger.warning(f'Directory {dir_name} not found in {root}')
    
    cursor.close()
    conn.close()

if __name__ == "__main__":
    directorios = []
    generar_schemas(directorios)
